<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'My Laravel Site'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'Default meta description for your site.'); ?>">

    <link rel="icon" href="<?php echo e(asset('favicon.png')); ?>" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="canonical" href="<?php echo e(url()->current()); ?>" />

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <meta name="theme-color" content="#d67a00">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body>

    
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH /Users/solob/dev/tokesi/resources/views/layouts/app.blade.php ENDPATH**/ ?>