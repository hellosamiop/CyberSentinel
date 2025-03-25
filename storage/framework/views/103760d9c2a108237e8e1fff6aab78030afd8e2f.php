
<?php $__env->startSection('content'); ?>
    <div class="py-20 mx-auto max-w-7xl">
        <div class="mx-auto px-4 py-4">
            <a href="<?php echo e(route('domains.create')); ?>"
               class="bg-wave-500 hover:bg-wave-700 text-white font-bold py-2 px-4 rounded-full">
                Add Domain
            </a>
        </div>
        <div class="container bg-white mx-auto p-5 border" style="border-radius: 15px">
            <div style="overflow-x: auto">
                <table class="w-full">
                    <thead class="border-b">
                    <tr class="text-left">
                        <th class="py-2 px-4">Name</th>
                        <th class="py-2 px-4">Domain URL</th>
                        <th class="py-2 px-4">Industry</th>
                        <th class="py-2 px-4">Verified</th>
                        <th class="py-2 px-4">Score</th>
                        <th class="py-2 px-4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $domains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="py-2 px-4"><?php echo e($domain->name); ?></td>
                            <td class="py-2 px-4"><?php echo e($domain->domain_url); ?></td>
                            <td class="py-2 px-4"><?php echo e($domain->industry->name ?? 'N/A'); ?></td>
                            <td class="py-2 px-4"><span class="rounded-full" style="background-color: <?php echo e($domain->verified ? '#84ca2a' : '#d4d4d4'); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                            <td class="py-2 px-4"><?php echo e($domain->score ?? 'N/A'); ?></td>
                            <td class="py-2 px-4 flex flex-col sm:flex-row sm:gap-5">
                                <a href="<?php echo e(route('domains.show', $domain)); ?>"
                                   class="bg-wave-500 hover:bg-wave-700 text-white font-bold py-2 px-4 rounded-full"
                                >View</a>
                                <a href="<?php echo e(route('domains.edit', $domain)); ?>"
                                   class="bg-indigo-400 hover:bg-indigo-400 text-white font-bold py-2 px-4 rounded-full"
                                >Edit</a>
                                <button type="button" onclick="deleteDomain(<?php echo e($domain->id); ?>)"
                                        class="bg-red-500 hover:bg-wave-700 text-white font-bold py-2 px-4 rounded-full"
                                >Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
    <script>
        function deleteDomain(id) {
            Swal.fire({
                title: 'Are you sure you want to remove this domain?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#95d148',
                cancelButtonColor: '#d4d4d4',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = '<?php echo e(route('domains.destroy', 'domain_id')); ?>'
                    url = url.replace('domain_id', id)

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Content-Type': 'application/json',
                        },
                    }).then(response => {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Your domain has been deleted.',
                            icon: 'success',
                            confirmButtonColor: '#95d148',
                            confirmButtonText: 'OK'
                        }).then((r) => {
                            window.location.reload()
                        })
                    })
                }
            })
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('theme::layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\FYP\cybersentinel\resources\views/user/domains/index.blade.php ENDPATH**/ ?>