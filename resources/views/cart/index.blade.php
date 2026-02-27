@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<section class="page-hero">
    <div class="page-hero-container">
        <h1 class="page-hero-title">Shopping Cart</h1>
        <p class="page-hero-subtitle">Review your items before checkout</p>
    </div>
    <div class="page-hero-decoration"></div>
</section>

<section class="cart-page">
    <div class="cart-page-container">
        <div class="cart-content">
            <div id="cartPageItems" class="cart-items-list">
                <!-- Cart items will be loaded here via JavaScript -->
            </div>
        </div>
        
        <div class="cart-sidebar">
            <div class="cart-summary-card">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="cartSubtotal">£0.00</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>Calculated at checkout</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-row summary-total">
                    <span>Total</span>
                    <span id="cartTotal">£0.00</span>
                </div>
                <a href="{{ route('checkout') }}" class="btn btn-primary btn-block">Proceed to Checkout</a>
                <a href="{{ route('shop.index') }}" class="btn btn-outline btn-block">Continue Shopping</a>
            </div>
        </div>
    </div>
</section>

<style>
.cart-page {
    padding: 3rem 0;
    background: var(--color-cream);
}

.cart-page-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 2rem;
}

.cart-content {
    background: var(--color-white);
    border-radius: 8px;
    padding: 2rem;
}

.cart-items-list {
    min-height: 300px;
}

.cart-item {
    display: grid;
    grid-template-columns: 100px 1fr auto;
    gap: 1.5rem;
    padding: 1.5rem 0;
    border-bottom: 1px solid var(--color-border);
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item-image {
    width: 100px;
    height: 140px;
    background: var(--color-cream);
    border-radius: 4px;
    overflow: hidden;
}

.cart-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cart-item-details h4 {
    font-family: var(--font-serif);
    font-size: 1.1rem;
    color: var(--color-text-dark);
    margin-bottom: 0.5rem;
}

.cart-item-price {
    font-size: 1rem;
    color: var(--color-gold);
    font-weight: 600;
    margin-bottom: 1rem;
}

.cart-item-quantity {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.qty-btn-cart {
    width: 32px;
    height: 32px;
    border: 1px solid var(--color-border);
    background: var(--color-white);
    border-radius: 4px;
    cursor: pointer;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.qty-btn-cart:hover {
    background: var(--color-cream);
    border-color: var(--color-gold);
}

.qty-display {
    min-width: 40px;
    text-align: center;
    font-weight: 600;
    color: var(--color-text-dark);
}

.cart-item-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: space-between;
}

.cart-item-subtotal {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--color-text-dark);
}

.remove-btn {
    background: none;
    border: none;
    color: #e53e3e;
    cursor: pointer;
    font-size: 0.9rem;
    padding: 0.5rem;
    transition: opacity 0.2s;
}

.remove-btn:hover {
    opacity: 0.7;
}

.cart-sidebar {
    position: sticky;
    top: 100px;
    height: fit-content;
}

.cart-summary-card {
    background: var(--color-white);
    border-radius: 8px;
    padding: 1.5rem;
}

.cart-summary-card h3 {
    font-family: var(--font-serif);
    font-size: 1.25rem;
    color: var(--color-text-dark);
    margin-bottom: 1.5rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    font-size: 0.95rem;
    color: var(--color-text-dark);
}

.summary-divider {
    height: 1px;
    background: var(--color-border);
    margin: 1.5rem 0;
}

.summary-total {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.btn-block {
    display: block;
    width: 100%;
    text-align: center;
    margin-bottom: 0.75rem;
}

.empty-cart {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-cart-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    opacity: 0.3;
}

.empty-cart h3 {
    font-family: var(--font-serif);
    font-size: 1.5rem;
    color: var(--color-text-dark);
    margin-bottom: 0.75rem;
}

.empty-cart p {
    color: var(--color-text-light);
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .cart-page-container {
        grid-template-columns: 1fr;
    }
    
    .cart-sidebar {
        position: static;
    }
    
    .cart-item {
        grid-template-columns: 80px 1fr;
        gap: 1rem;
    }
    
    .cart-item-actions {
        grid-column: 1 / -1;
        flex-direction: row;
        justify-content: space-between;
        margin-top: 1rem;
    }
}
</style>

<script>
// Load and render cart on page load
async function loadCartPage() {
    try {
        const response = await fetch('/api/cart');
        const data = await response.json();
        
        if (data.success) {
            renderCartPage(data.items, data.total);
        }
    } catch (error) {
        console.error('Failed to load cart:', error);
    }
}

function renderCartPage(items, total) {
    const container = document.getElementById('cartPageItems');
    const subtotalEl = document.getElementById('cartSubtotal');
    const totalEl = document.getElementById('cartTotal');
    
    if (!items || items.length === 0) {
        container.innerHTML = `
            <div class="empty-cart">
                <svg class="empty-cart-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M4.00488 16V4H2.00488V2H5.00488C5.55717 2 6.00488 2.44772 6.00488 3V15H18.4433L20.4433 7H8.00488V5H21.7241C22.2764 5 22.7241 5.44772 22.7241 6C22.7241 6.08176 22.7141 6.16322 22.6942 6.24254L20.1942 16.2425C20.083 16.6877 19.683 17 19.2241 17H5.00488C4.4526 17 4.00488 16.5523 4.00488 16ZM6.00488 23C4.90031 23 4.00488 22.1046 4.00488 21C4.00488 19.8954 4.90031 19 6.00488 19C7.10945 19 8.00488 19.8954 8.00488 21C8.00488 22.1046 7.10945 23 6.00488 23ZM18.0049 23C16.9003 23 16.0049 22.1046 16.0049 21C16.0049 19.8954 16.9003 19 18.0049 19C19.1094 19 20.0049 19.8954 20.0049 21C20.0049 22.1046 19.1094 23 18.0049 23Z"/>
                </svg>
                <h3>Your cart is empty</h3>
                <p>Add some books to get started</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary">Browse Books</a>
            </div>
        `;
        return;
    }
    
    container.innerHTML = items.map(item => `
        <div class="cart-item">
            <div class="cart-item-image">
                <img src="${item.image || '/images/placeholder.jpg'}" alt="${item.name}">
            </div>
            <div class="cart-item-details">
                <h4>${item.name}</h4>
                <p class="cart-item-price">£${item.price.toFixed(2)}</p>
                <div class="cart-item-quantity">
                    <button class="qty-btn-cart" onclick="updateCartQuantity(${item.id}, -1)">−</button>
                    <span class="qty-display">${item.quantity}</span>
                    <button class="qty-btn-cart" onclick="updateCartQuantity(${item.id}, 1)">+</button>
                </div>
            </div>
            <div class="cart-item-actions">
                <span class="cart-item-subtotal">£${item.subtotal.toFixed(2)}</span>
                <button class="remove-btn" onclick="removeCartItem(${item.id})">Remove</button>
            </div>
        </div>
    `).join('');
    
    subtotalEl.textContent = `£${total.toFixed(2)}`;
    totalEl.textContent = `£${total.toFixed(2)}`;
}

async function updateCartQuantity(itemId, change) {
    try {
        const item = await fetch('/api/cart').then(r => r.json()).then(d => d.items.find(i => i.id === itemId));
        const newQuantity = item.quantity + change;
        
        if (newQuantity <= 0) {
            return removeCartItem(itemId);
        }
        
        const response = await fetch(`/api/cart/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity: newQuantity })
        });
        
        const data = await response.json();
        if (data.success) {
            loadCartPage();
            updateCartBadge();
        }
    } catch (error) {
        console.error('Failed to update quantity:', error);
    }
}

async function removeCartItem(itemId) {
    if (!confirm('Remove this item from cart?')) return;
    
    try {
        const response = await fetch(`/api/cart/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        if (data.success) {
            loadCartPage();
            updateCartBadge();
        }
    } catch (error) {
        console.error('Failed to remove item:', error);
    }
}

document.addEventListener('DOMContentLoaded', loadCartPage);
</script>
@endsection
