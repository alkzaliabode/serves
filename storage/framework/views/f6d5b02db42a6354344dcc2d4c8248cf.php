 

<?php $__env->startSection('title', 'قائمة المواقف اليومية'); ?> 

<?php $__env->startSection('page_title', 'المواقف اليومية'); ?> 

<?php $__env->startSection('breadcrumb'); ?> 
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">المواقف اليومية</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <style>
        /* أنماط البطاقات لتكون شفافة مع تباين جيد للنص */
        .card {
            background-color: rgba(255, 255, 255, 0.2) !important; /* خلفية شفافة للبطاقات */
            border-radius: 0.75rem; /* حواف مستديرة */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* ظل أوضح */
            border: none !important; /* إزالة أي حدود */
        }

        .card-header {
            background-color: rgba(255, 255, 255, 0.3) !important; /* خلفية رأس البطاقة أكثر شفافية */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important; /* حدود شفافة */
        }
        .card-title,
        .card-header .btn {
            color: white !important; /* لون نص أبيض لعناوين البطاقات والأزرار */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7); /* ظل للنص لتحسين القراءة */
        }

        .card-body {
            color: white; /* لون نص أبيض لجسم البطاقة */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); /* ظل خفيف للنص */
        }

        /* أنماط الجدول داخل البطاقة */
        .table {
            color: white; /* لون نص أبيض للجدول بالكامل */
        }
        .table thead th {
            background-color: rgba(0, 123, 255, 0.3) !important; /* خلفية زرقاء شفافة لرؤوس الجدول */
            color: white !important; /* لون نص أبيض لرؤوس الجدول */
            border-color: rgba(255, 255, 255, 0.3) !important; /* حدود بيضاء شفافة */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }
        .table tbody td {
            border-color: rgba(255, 255, 255, 0.1) !important; /* حدود بيضاء شفافة للصفوف */
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05) !important; /* تظليل خفيف للصفوف الفردية */
        }
        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.15) !important; /* تأثير تحويم أكثر وضوحاً */
        }

        /* أنماط أزرار وعناصر التحكم في الفلاتر */
        .form-control,
        .form-select {
            background-color: rgba(255, 255, 255, 0.7) !important; /* خلفية شفافة للحقول */
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: #333 !important; /* لون نص داكن للحقول */
        }
        .form-control::placeholder {
            color: #666 !important;
        }
        .form-control:focus,
        .form-select:focus {
            background-color: rgba(255, 255, 255, 0.9) !important;
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25) !important;
        }
        /* لتلوين نص الـ <option> داخل الـ <select> عندما يكون الخلفية داكنة */
        .form-select option {
            background-color: #2c3e50; /* خلفية داكنة لخيار القائمة */
            color: white; /* نص أبيض لخيار القائمة */
        }

        .btn-primary {
            background-color: #007bff !important;
            border-color: #007bff !important;
            transition: background-color 0.3s ease;
            color: white !important; /* ضمان لون النص أبيض */
        }
        .btn-primary:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
        }

        .btn-success {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            transition: background-color 0.3s ease;
            color: white !important; /* ضمان لون النص أبيض */
        }
        .btn-success:hover {
            background-color: #218838 !important;
            border-color: #218838 !important;
        }

        .btn-info {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
            color: white !important;
        }
        .btn-info:hover {
            background-color: #138496 !important;
            border-color: #138496 !important;
        }

        .btn-warning {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
            color: #212529 !important; /* لون نص داكن لزر التحذير */
        }
        .btn-warning:hover {
            background-color: #e0a800 !important;
            border-color: #e0a800 !important;
        }

        .btn-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important;
        }
        .btn-danger:hover {
            background-color: #c82333 !important;
            border-color: #bd2130 !important;
        }

        .btn-secondary {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: white !important;
        }
        .btn-secondary:hover {
            background-color: #5a6268 !important;
            border-color: #545b62 !important;
        }

        /* أنماط الأيقونات في الأزرار */
        .btn .fas {
            margin-right: 5px; /* مسافة بين الأيقونة والنص */
        }

        /* أنماط رسائل التنبيه (Alerts) */
        .alert {
            background-color: rgba(255, 255, 255, 0.9) !important; /* خلفية شفافة للرسائل */
            color: #333 !important; /* لون نص داكن */
            border-color: rgba(0, 0, 0, 0.2) !important;
        }
        .alert-success {
            background-color: rgba(40, 167, 69, 0.9) !important; /* خلفية خضراء شفافة للنجاح */
            color: white !important;
        }
        .alert-info {
            background-color: rgba(23, 162, 184, 0.9) !important; /* خلفية زرقاء شفافة للمعلومات */
            color: white !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card"> 
        <div class="card-header">
            <h3 class="card-title">قائمة المواقف اليومية</h3>
            <div class="card-tools">
                <a href="<?php echo e(route('daily-statuses.create')); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> إضافة موقف يومي جديد
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if($dailyStatuses->isEmpty()): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <span class="block sm:inline">لا توجد مواقف يومية لعرضها. ابدأ بإضافة موقف جديد!</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover"> 
                        <thead>
                            <tr>
                                <th class="text-right">التاريخ</th>
                                <th class="text-right">التاريخ الهجري</th>
                                <th class="text-right">اليوم</th>
                                <th class="text-right">الموجود الحالي</th>
                                <th class="text-right">الحضور الفعلي</th>
                                <th class="text-right">المنظم</th>
                                <th class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $dailyStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($status->date->format('Y-m-d')); ?></td>
                                    <td><?php echo e($status->hijri_date); ?></td>
                                    <td><?php echo e($status->day_name); ?></td>
                                    <td><?php echo e($status->total_employees); ?></td>
                                    <td><?php echo e($status->actual_attendance); ?></td>
                                    <td><?php echo e($status->organizer_employee_name); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="الإجراءات">
                                            <a href="<?php echo e(route('daily-statuses.show', $status->id)); ?>" class="btn btn-info btn-sm" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('daily-statuses.edit', $status->id)); ?>" class="btn btn-warning btn-sm" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?php echo e(route('daily-statuses.print', $status->id)); ?>" target="_blank" class="btn btn-secondary btn-sm" title="طباعة">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <form action="<?php echo e(route('daily-statuses.destroy', $status->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذا الموقف اليومي؟');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger btn-sm" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($dailyStatuses->links('pagination::bootstrap-5')); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/daily_statuses/index.blade.php ENDPATH**/ ?>