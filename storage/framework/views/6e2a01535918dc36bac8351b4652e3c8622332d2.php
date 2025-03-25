
<?php $__env->startSection('content'); ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <div class="py-20 mx-auto max-w-7xl">
        <div class="container mx-auto p-5">
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                <div class="bg-white col-span-1 shadow text-center border"
                     style="border-radius: 15px; padding: 15px 30px">
                    <h1><?php echo e($domain->name); ?></h1>
                </div>
                <div class="bg-white col-span-1 shadow text-center border"
                     style="border-radius: 15px; padding: 15px 30px">
                    <h1><?php echo e($domain->domain_url); ?></h1>
                </div>
                <div class="bg-white col-span-1 shadow text-center border"
                     style="border-radius: 15px; padding: 15px 30px">
                    <h1><?php echo e($domain->industry->name); ?></h1>
                </div>
                <div class="bg-white col-span-1 shadow text-center border"
                     style="border-radius: 15px; padding: 15px 30px">
                    <h1><?php echo e($domain->country->name); ?></h1>
                </div>
                <div class="bg-white col-span-1 shadow text-center border"
                     style="border-radius: 15px; padding: 15px 30px">
                    <h1><?php echo e($domain->latest_score); ?></h1>
                </div>
                <div class="bg-white col-span-1 shadow text-center border"
                     style="border-radius: 15px; padding: 15px 30px">
                    <img src="<?php echo e($domain->logo); ?>" alt="" style="min-height: 150px">
                </div>
                <div class="bg-white col-span-4 shadow text-center border"
                     style="border-radius: 15px; padding: 15px 30px">
                    <div id="score_chart"></div>
                </div>
                <div class="bg-white col-span-5 shadow border" style="border-radius: 15px; padding: 15px 30px">
                    <h4 class="mb-3"><strong>WEBSITE HEALTH SCORE</strong></h4>
                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-4">
                        <?php $__currentLoopData = $data['health_data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $health): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white col-span-1 flex flex-col gap-2 justify-between items-center shadow border"
                                 style="border-radius: 15px; padding: 15px 30px">
                                <div class="flex gap-2 justify-center items-center">
                                    <i class="icon voyager-settings"></i>
                                    <span><?php echo e($health['class']); ?></span>
                                </div>
                                <h1 style="font-size: 30px"><?php echo e($health['score']); ?></h1>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                </div>
                <div class="bg-white col-span-5 shadow border" style="border-radius: 15px; padding: 15px 30px">
                    <h4 class="mb-3"><strong>ATTACK CLASS LIKELIHOOD</strong></h4>
                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-4">
                        <?php $__currentLoopData = $data['attack_data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attack): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white col-span-1 flex flex-col gap-2 justify-between items-center shadow border"
                                 style="border-radius: 15px; padding: 15px 30px">
                                <div class="flex gap-2 justify-center items-center">
                                    <div class="icon voyager-settings"></div>
                                    <span><?php echo e($attack['class']); ?></span>
                                </div>
                                <h1 style="font-size: 30px; color: <?php echo e($attack['color']); ?>"><?php echo e($attack['likelihood']); ?></h1>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const scores = <?php echo json_encode($domain->scoreHistory(), 15, 512) ?>;

        const options = {
            chart: {
                type: 'line',
                height: 150
            },
            series: [{
                name: 'Score',
                data: scores
            }],
            xaxis: {
                labels: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                }
            }
        };
        const chart = new ApexCharts(document.querySelector("#score_chart"), options);
        chart.render();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('theme::layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\FYP\cybersentinel\resources\views/user/domains/show.blade.php ENDPATH**/ ?>