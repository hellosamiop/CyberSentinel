<header x-data="{ mobileMenuOpen: false }" class="relative z-30 <?php if(Request::is('/')): ?><?php echo e('bg-white'); ?><?php else: ?><?php echo e('bg-gray-50'); ?><?php endif; ?>">
    <div class="px-8 mx-auto xl:px-5 max-w-7xl">
        <div class="flex items-center justify-between h-24 border-b-2 border-gray-100 md:justify-start md:space-x-6">
            <div class="inline-flex">
            <!-- data-replace='{ "translate-y-12": "translate-y-0", "scale-110": "scale-100", "opacity-0": "opacity-100" }' -->
                <a href="<?php echo e(route('wave.home')); ?>" class="flex items-center justify-center space-x-3 transition-all duration-1000 ease-out transform text-wave-500">
                    <?php if(Voyager::image(theme('logo'))): ?>
                        <img class="h-9" src="<?php echo e(Voyager::image(theme('logo'))); ?>" alt="Company name">
                    <?php else: ?>
                        <img class="h-9" src="<?php echo e(asset('assets/images/logo.png')); ?>" alt="Company name">
                    <?php endif; ?>
                </a>
            </div>
            <div class="flex justify-end flex-grow -my-2 -mr-2 md:hidden">
                <button @click="mobileMenuOpen = true" type="button" class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                    <svg class="w-6 h-6" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                </button>
            </div>

            <!-- This is the homepage nav when a user is not logged in -->
            <?php if(auth()->guest()): ?>
                <?php echo $__env->make('theme::menus.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php else: ?> <!-- Otherwise we want to show the menu for the logged in user -->
                <?php echo $__env->make('theme::menus.authenticated', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

        </div>
    </div>

    <?php if(auth()->guest()): ?>
        <?php echo $__env->make('theme::menus.guest-mobile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <?php echo $__env->make('theme::menus.authenticated-mobile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
</header>
<?php /**PATH D:\FYP\cybersentinel\resources\views/themes/tailwind/partials/header.blade.php ENDPATH**/ ?>