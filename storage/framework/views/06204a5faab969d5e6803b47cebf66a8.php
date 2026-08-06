<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <style>
            /* Focus visible indicators for WCAG 2.1 AA Compliance */
            *:focus-visible {
                outline: 3px solid #4f46e5 !important;
                outline-offset: 2px !important;
            }
        </style>
    </head>
    <body class="antialiased" style="font-family: 'Cairo', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';">
        <!-- Accessibility: Skip to main content link -->
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:right-2 bg-indigo-600 text-white px-4 py-2 z-50 rounded-lg font-bold shadow-lg">
            <?php echo e(__('تخطي إلى المحتوى الرئيسي')); ?>

        </a>

        <div class="min-h-screen bg-gray-100 <?php echo e(app()->getLocale() == 'ar' ? 'sm:pr-64' : 'sm:pl-64'); ?>">
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Page Heading -->
            <?php if(isset($header)): ?>
                <!-- Show page header on mobile only -->
                <header class="bg-white shadow sm:hidden">
                    <div class="max-w-7xl mx-auto py-4 px-4">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            <!-- Page Content -->
            <main id="main-content" tabindex="-1" class="focus:outline-none">
                <?php echo e($slot); ?>

            </main>
        </div>
    </body>
</html>
<?php /**PATH E:\programing\flutter project\fast order\resources\views\layouts\app.blade.php ENDPATH**/ ?>