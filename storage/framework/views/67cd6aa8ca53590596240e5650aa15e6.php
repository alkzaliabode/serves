 

<?php $__env->startSection('title', 'إنشاء موقف يومي'); ?>
<?php $__env->startSection('page_title', 'إنشاء موقف يومي جديد'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('daily-statuses.index')); ?>">الموقف اليومي</a></li>
    <li class="breadcrumb-item active">إنشاء</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        
        <?php echo $__env->make('daily_statuses.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/daily_statuses/create.blade.php ENDPATH**/ ?>