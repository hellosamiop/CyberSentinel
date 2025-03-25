
<?php $__env->startSection('content'); ?>
    <div class="py-20 mx-auto max-w-7xl">
        <div class="container mx-auto px-4">
            <div class="mx-auto bg-white p-6 shadow" style="border-radius: 15px">
                <?php if($errors->any()): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                         style="border-radius: 15px;" role="alert">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="text-sm"><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <h2 class="text-2xl font-bold mb-4">Scan a Domain</h2>
                <form action="<?php echo e(route('scans.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <label for="domain_id" class="block text-sm font-semibold text-gray-600">Select a Domain:</label>
                        <select name="domain_id" id="domain_id" class="mt-1 p-2 w-full border"
                                style="border-radius: 15px" required>
                            <option value="">Select a Domain</option>
                            <?php $__currentLoopData = $domains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($domain->id); ?>"><?php echo e($domain->name); ?> (<?php echo e($domain->domain_url); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="flex justify-end items-center space-x-4">
                        <button type="submit"
                                class="bg-wave-500 hover:bg-wave-700 text-white font-bold py-2 px-4 rounded">
                            Scan
                        </button>
                        <a href="<?php echo e(route('scans.index')); ?>"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
    <script>
        document.getElementById('logo').addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('logo_preview').setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('theme::layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\FYP\cybersentinel\resources\views/user/scans/create.blade.php ENDPATH**/ ?>