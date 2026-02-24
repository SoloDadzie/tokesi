<?php

namespace App\Observers;

use App\Models\Order;
use App\Mail\OrderShipped;
use App\Mail\OrderDelivered;
use App\Mail\OrderCancelled;
use App\Mail\AddressUpdated;
use App\Mail\AdminAddressUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Handle status changes
        if ($order->isDirty('status')) {
            $this->handleStatusChange($order);
        }

        // Handle address changes
        if (isset($order->addressChanged) && $order->addressChanged) {
            $this->handleAddressChange($order);
        }
    }

    /**
     * Handle status changes and send appropriate emails
     */
    protected function handleStatusChange(Order $order): void
    {
        $oldStatus = $order->getOriginal('status');
        $newStatus = $order->status;

        try {
            // Order shipped
            if ($newStatus === 'shipped' && $oldStatus !== 'shipped') {
                $this->sendEmail(
                    fn() => Mail::to($order->email)->send(new OrderShipped($order)),
                    "Order shipped email sent to {$order->email} for order {$order->order_number}"
                );
            }

            // Order delivered
            if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
                $this->sendEmail(
                    fn() => Mail::to($order->email)->send(new OrderDelivered($order)),
                    "Order delivered email sent to {$order->email} for order {$order->order_number}"
                );
            }

            // Order cancelled
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                // Send to customer
                $this->sendEmail(
                    fn() => Mail::to($order->email)->send(new OrderCancelled($order, 'customer')),
                    "Order cancelled email sent to customer for order {$order->order_number}"
                );
                
                // Send to admin
                $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL'));
                if ($adminEmail) {
                    $this->sendEmail(
                        fn() => Mail::to($adminEmail)->send(new OrderCancelled($order, 'admin')),
                        "Order cancelled email sent to admin for order {$order->order_number}"
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to send order status email: " . $e->getMessage(), [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $newStatus
            ]);
        }
    }

    /**
     * Handle address changes and send notifications
     */
    protected function handleAddressChange(Order $order): void
    {
        // Don't send email if order is already shipped/delivered/cancelled
        if (!$order->canUpdateAddress()) {
            return;
        }

        $oldEmail = $order->getOriginal('email');
        $newEmail = $order->email;

        // If email changed, send to both old and new email
        if ($oldEmail !== $newEmail) {
            // Send to old email
            $this->sendEmail(
                fn() => Mail::to($oldEmail)->send(new AddressUpdated($order, $oldEmail, 'old')),
                "Address update notification sent to old email ({$oldEmail}) for order {$order->order_number}"
            );
            
            // Send to new email
            $this->sendEmail(
                fn() => Mail::to($newEmail)->send(new AddressUpdated($order, $newEmail, 'new')),
                "Address update notification sent to new email ({$newEmail}) for order {$order->order_number}"
            );
        } else {
            // Email didn't change, just send to current email
            $this->sendEmail(
                fn() => Mail::to($order->email)->send(new AddressUpdated($order, $order->email, 'current')),
                "Address update notification sent to {$order->email} for order {$order->order_number}"
            );
        }

        // Always notify admin
        $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL'));
        if ($adminEmail) {
            $this->sendEmail(
                fn() => Mail::to($adminEmail)->send(new AdminAddressUpdated($order, $oldEmail)),
                "Admin address update notification sent for order {$order->order_number}"
            );
        }
    }

    /**
     * Send email with error handling
     * 
     * @param callable $mailCallback
     * @param string $successMessage
     * @return bool
     */
    protected function sendEmail(callable $mailCallback, string $successMessage): bool
    {
        try {
            $mailCallback();
            Log::info($successMessage);
            return true;
        } catch (\Exception $e) {
            // Log the error but don't throw - we don't want email failures to break the update
            Log::warning("Email sending failed (non-critical): " . $e->getMessage());
            return false;
        }
    }
}