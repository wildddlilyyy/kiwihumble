<!doctype html>
<html lang="zh-Hant">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <title><?php echo e($title ?? config('app.name')); ?></title>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="min-h-screen bg-kiwi-cream text-kiwi-ink antialiased">
        <?php echo e($slot); ?>

    </body>
</html>
<?php /**PATH C:\Users\User\Documents\KiwiHumble\resources\views/components/layouts/app.blade.php ENDPATH**/ ?>