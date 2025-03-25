<?php use Carbon\Carbon; ?>

<?php $__env->startSection('content'); ?>
    <div class="py-20 mx-auto max-w-7xl">
        <div class="mx-auto px-4 py-4">
            <a href="<?php echo e(route('scans.create')); ?>"
               class="bg-wave-500 hover:bg-wave-700 text-white font-bold py-2 px-4 rounded-full">
                New Scan
            </a>
        </div>
        <div class="container bg-white mx-auto p-5 border" style="border-radius: 15px">
            <div style="overflow-x: auto">
                <table class="w-full">
                    <thead class="border-b">
                    <tr class="text-left">
                        <th class="py-2 px-4">ID</th>
                        <th class="py-2 px-4">Domain URL</th>
                        <th class="py-2 px-6">Status</th>
                        <th class="py-2 px-4">Type</th>
                        <th class="py-2 px-4">Date</th>

                        <th class="py-2 px-4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $scans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="py-2 px-4"><?php echo e($scan->id); ?></td>
                            <td class="py-2 px-4"><?php echo e($scan->domain->domain_url); ?></td>
                            <td class="py-2 pl-4">
                                <div class="flex items-center">
                                    <img id="scan-loading-<?php echo e($scan->id); ?>" width="50" height="50" src="<?php echo e(asset('assets/images/loading.gif')); ?>" alt=""
                                         <?php if($scan->isComplete()): ?> style="display:none" <?php endif; ?>>
                                    <div class="mt-3">
                                        <span id="scan-status-<?php echo e($scan->id); ?>"><?php echo e($scan->isComplete() ? 'Complete' : 'In Progress'); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-4"><?php echo e($scan->domain->industry->name ?? 'N/A'); ?></td>
                            <td class="py-2 px-4"><?php echo e(Carbon::parse($scan->created_at)->toDateString()); ?></td>

                            <td id="scan-actions-<?php echo e($scan->id); ?>"
                                data-domain-show="<?php echo e(route('domains.show', $scan->domain->id)); ?>"
                                data-scan-report="<?php echo e(route('scans.report', $scan->id)); ?>"
                                data-scan-delete="<?php echo e(route('scans.destroy', $scan->id)); ?>"
                                class="py-2 px-4 flex flex-col sm:flex-row sm:gap-5">
                                <?php if(!$scan->isComplete()): ?>
                                    <img width="50" height="50" src="<?php echo e(asset('assets/images/loading.gif')); ?>" alt="">
                                <?php else: ?>
                                    <a href="<?php echo e(route('domains.show', $scan->domain->id)); ?>"
                                       class="bg-wave-500 hover:bg-wave-700 text-white font-bold py-2 px-4 rounded-full">
                                        View
                                    </a>
                                    <a href="<?php echo e(route('scans.report', $scan->id)); ?>"
                                       class="bg-indigo-400 hover:bg-indigo-400 text-white font-bold py-2 px-4 rounded-full">
                                        Report
                                    </a>
                                <?php endif; ?>
                                    <form action="<?php echo e(route('scans.destroy', $scan->id)); ?>" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this scan?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full">
                                            Delete
                                        </button>
                                    </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function updateScanStatuses() {
            fetch('<?php echo e(route("scans.status")); ?>')
                .then(response => response.json())
                .then(statuses => {
                    for (const [scanId, status] of Object.entries(statuses)) {
                        const statusElement = document.getElementById('scan-status-' + scanId);
                        if (statusElement && statusElement.innerText !== status) {
                            statusElement.innerText = status;

                            // If status indicates the scan is complete, update the UI
                            if (status.toLowerCase().includes('complete')) {
                                // Hide the loading image in the status column
                                const loadingImage = document.getElementById('scan-loading-' + scanId);
                                if (loadingImage) {
                                    loadingImage.style.display = 'none';
                                }

                                // Update the actions cell to show buttons
                                const actionsCell = document.getElementById('scan-actions-' + scanId);
                                if (actionsCell && actionsCell.getAttribute('data-updated') !== 'true') {
                                    const domainShowUrl = actionsCell.getAttribute('data-domain-show');
                                    const scanReportUrl = actionsCell.getAttribute('data-scan-report');
                                    const scanDeleteUrl = actionsCell.getAttribute('data-scan-delete');
                                    actionsCell.innerHTML = `
                                        <a href="${domainShowUrl}" class="bg-wave-500 hover:bg-wave-700 text-white font-bold py-2 px-4 rounded-full">
                                            View
                                        </a>
                                        <a href="${scanReportUrl}" class="bg-indigo-400 hover:bg-indigo-400 text-white font-bold py-2 px-4 rounded-full">
                                            Report
                                        </a>
                                        <form action="${scanDeleteUrl}" method="POST" onsubmit="return confirm('Are you sure you want to delete this scan?');" style="display: inline-block;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full">
                                                Delete
                                            </button>
                                        </form>`;
                                    actionsCell.setAttribute('data-updated', 'true');
                                }
                            }
                        }
                    }
                })
                .catch(error => console.error('Error fetching scan statuses:', error));
        }

        setInterval(updateScanStatuses, 2000);
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('theme::layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\FYP\cybersentinel\resources\views/user/scans/index.blade.php ENDPATH**/ ?>