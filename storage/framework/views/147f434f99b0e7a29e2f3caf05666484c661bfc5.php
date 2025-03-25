<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Scan Report - <?php echo e($domain->name); ?></title>
    <style>
        /* Inline minimal CSS for PDF output */
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .box {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 15px;
            padding: 15px 30px;
            text-align: center;
        }

        .shadow {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 18px;
            margin: 0;
        }

        .chart {
            height: 150px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .section {
            margin-bottom: 20px;
        }

        .logo-box {
            background-color: #004080; /* dark blue background */
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: 20px auto;
            max-width: 400px;
        }

        .logo-box h1 {
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 28px;
            letter-spacing: 2px;
            margin: 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="logo-box">
        <h1>CyberSentinel Scan Report</h1>
    </div>
    <div class="grid">
        <div class="box shadow" style="flex: 1;">
            <h1><?php echo e($domain->name); ?></h1>
        </div>
        <div class="box shadow" style="flex: 1;">
            <h1><?php echo e($domain->domain_url); ?></h1>
        </div>
        <div class="box shadow" style="flex: 1;">
            <h1><?php echo e($domain->industry->name ?? ''); ?></h1>
        </div>
        <div class="box shadow" style="flex: 1;">
            <h1><?php echo e($domain->country->name ?? ''); ?></h1>
        </div>
        <div class="box shadow" style="flex: 1;">
            <img src="<?php echo e($domain->logo); ?>" alt="Logo" style="max-height: 150px; width: auto;">
        </div>
    </div>

    <!-- Placeholder for Score Chart -->
    <div class="box shadow" style="margin-top: 20px;">
        <h4>Score History</h4>
        <!-- For PDF, you might consider rendering a pre-generated image for the chart -->
        <div class="chart">
            <img src="<?php echo e($chartImageUrl ?? ''); ?>" alt="Score Chart" style="width: 100%; height: 150px;">
        </div>
    </div>

    <!-- Website Health Score Section -->
    <div class="box shadow section">
        <h4><strong>WEBSITE HEALTH SCORE</strong></h4>
        <div class="grid">
            <?php $__currentLoopData = $data['health_data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $health): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="box shadow" style="flex: 1;">
                    <div>
                        <strong><?php echo e($health['class']); ?></strong>
                    </div>
                    <h1 style="font-size: 30px;"><?php echo e($health['score']); ?></h1>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Attack Class Likelihood Section -->
    <div class="box shadow section">
        <h4><strong>ATTACK CLASS LIKELIHOOD</strong></h4>
        <div class="grid">
            <?php $__currentLoopData = $data['attack_data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attack): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="box shadow" style="flex: 1;">
                    <div>
                        <strong><?php echo e($attack['class']); ?></strong>
                    </div>
                    <h1 style="font-size: 30px; color: <?php echo e($attack['color']); ?>"><?php echo e($attack['likelihood']); ?></h1>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Additional Scan Data / Details -->
    <div class="box shadow section">
        <h2>Scan Details</h2>
        <p><strong>Domain:</strong> <?php echo e($domain->domain_url); ?></p>
    </div>

    <!-- Alerts Table with Comments Column -->
    <div class="box shadow section">
        <h2>Scan Alerts</h2>
        <?php if($alerts->count()): ?>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Alert Name</th>
                    <th>Type</th>
                    <th>Risk</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <?php if($alert->owasp_value): ?>
                            <td><?php echo e($alert->owasp_value->id); ?></td>
                            <td><?php echo e($alert->owasp_value->alert_name); ?></td>
                            <td><?php echo e($alert->owasp_value->alert_type); ?></td>
                            <td><?php echo e($alert->owasp_value->alert_risk); ?></td>
                        <?php else: ?>
                            <td><?php echo e($alert->id); ?></td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>N/A</td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No alerts found for this scan.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
<?php /**PATH D:\FYP\cybersentinel\resources\views/reports/scan_report.blade.php ENDPATH**/ ?>