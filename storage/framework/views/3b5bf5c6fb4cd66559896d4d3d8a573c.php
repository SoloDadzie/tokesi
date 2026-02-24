<!--[if BLOCK]><![endif]--><?php if(filament()->hasUnsavedChangesAlerts()): ?>
        <?php
        $__scriptKey = '568181609-0';
        ob_start();
    ?>
        <script>
            setUpUnsavedActionChangesAlert({
                resolveLivewireComponentUsing: () => window.Livewire.find('<?php echo e($_instance->getId()); ?>'),
                $wire,
            })
        </script>
        <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->
<?php /**PATH /Users/solob/dev/tokesi/vendor/filament/filament/resources/views/components/unsaved-action-changes-alert.blade.php ENDPATH**/ ?>