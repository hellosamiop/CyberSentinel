<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>

    <?php if(isset($seo->title)): ?>
        <title><?php echo e($seo->title); ?></title>
    <?php else: ?>
        <title><?php echo e(setting('site.title', 'Cyberenew') . ' - ' . setting('site.description', 'The Software as a Service Starter Kit built on Laravel & Voyager')); ?></title>
    <?php endif; ?>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge"> <!-- † -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="url" content="<?php echo e(url('/')); ?>">

    <link rel="icon" href="<?php echo e(setting('site.favicon', asset('assets/images/dark_icon.png'))); ?>" type="image/x-icon">

    
    <?php if(isset($seo->title) && isset($seo->description) && isset($seo->image)): ?>
        <meta property="og:title" content="<?php echo e($seo->title); ?>">
        <meta property="og:url" content="<?php echo e(Request::url()); ?>">
        <meta property="og:image" content="<?php echo e($seo->image); ?>">
        <meta property="og:type" content="<?php if(isset($seo->type)): ?><?php echo e($seo->type); ?><?php else: ?><?php echo e('article'); ?><?php endif; ?>">
        <meta property="og:description" content="<?php echo e($seo->description); ?>">
        <meta property="og:site_name" content="<?php echo e(setting('site.title')); ?>">

        <meta itemprop="name" content="<?php echo e($seo->title); ?>">
        <meta itemprop="description" content="<?php echo e($seo->description); ?>">
        <meta itemprop="image" content="<?php echo e($seo->image); ?>">

        <?php if(isset($seo->image_w) && isset($seo->image_h)): ?>
            <meta property="og:image:width" content="<?php echo e($seo->image_w); ?>">
            <meta property="og:image:height" content="<?php echo e($seo->image_h); ?>">
        <?php endif; ?>
    <?php endif; ?>

    <meta name="robots" content="index,follow">
    <meta name="googlebot" content="index,follow">

    <?php if(isset($seo->description)): ?>
        <meta name="description" content="<?php echo e($seo->description); ?>">
    <?php endif; ?>

    <!-- Styles -->
    <link href="<?php echo e(asset('themes/' . $theme->folder . '/css/app.css')); ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

</head>
<body
    class="flex flex-col min-h-screen <?php if(Request::is('/')): ?><?php echo e('bg-white'); ?><?php else: ?><?php echo e('bg-gray-50'); ?><?php endif; ?> <?php if(config('wave.dev_bar')): ?><?php echo e('pb-10'); ?><?php endif; ?>">

<?php echo $__env->make('theme::partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<main class="flex-grow">
    <?php echo $__env->yieldContent('content'); ?>
</main>


<?php echo $__env->make('theme::partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php if(config('wave.dev_bar')): ?>
    <?php echo $__env->make('theme::partials.dev_bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<!-- Full Screen Loader -->
<div id="fullscreenLoader"
     class="fixed inset-0 top-0 left-0 z-50 flex flex-col items-center justify-center hidden w-full h-full bg-gray-900 opacity-50">
    <svg class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
         viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <p id="fullscreenLoaderMessage" class="mt-4 text-sm font-medium text-white uppercase"></p>
</div>
<!-- End Full Loader -->


<?php echo $__env->make('theme::partials.toast', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if(session('message')): ?>
    <script>setTimeout(function () {
            popToast("<?php echo e(session('message_type')); ?>", "<?php echo e(session('message')); ?>");
        }, 10);</script>
<?php endif; ?>
<?php echo view("wave::checkout")->render(); ?>


</body>
</html>
<?php /**PATH D:\FYP\cybersentinel\resources\views/themes/tailwind/layouts/app.blade.php ENDPATH**/ ?>