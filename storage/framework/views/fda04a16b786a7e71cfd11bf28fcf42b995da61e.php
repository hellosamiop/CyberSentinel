<!DOCTYPE html>
<html>
<head>
    <title>Laravel Logs</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>Laravel Logs</h1>
<form action="<?php echo e(route('clear-logs')); ?>" method="get">
    <button type="submit" style="float: right; background-color: #cc3e3e">Clear Logs</button>
</form>
<table>
    <thead>
    <tr>
        <th>Log Entry</th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $logEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($entry); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
</body>
</html>
<?php /**PATH D:\FYP\cybersentinel\resources\views/logs.blade.php ENDPATH**/ ?>