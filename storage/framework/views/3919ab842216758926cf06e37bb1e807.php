 

<?php $__env->startSection('title', 'استبيانات رضا الزائرين'); ?>

<?php $__env->startSection('page_title', 'استبيانات رضا الزائرين'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">الرئيسية</a></li>
    <li class="breadcrumb-item active">استبيانات رضا الزائرين</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <div class="card card-outline card-info shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-info">
                    <i class="fas fa-clipboard-list mr-2"></i> سجلات استبيانات رضا الزائرين
                </h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('surveys.create')); ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-1"></i> إضافة استبيان جديد
                    </a>
                    <a href="<?php echo e(route('surveys.export', request()->query())); ?>" class="btn btn-primary btn-sm ml-2">
                        <i class="fas fa-file-export mr-1"></i> تصدير البيانات
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e(session('error')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form method="GET" action="<?php echo e(route('surveys.index')); ?>" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="overall_satisfaction">الرضا العام:</label>
                                <select name="overall_satisfaction" id="overall_satisfaction" class="form-control">
                                    <option value="">جميع المستويات</option>
                                    <?php $__currentLoopData = $satisfactionOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(request('overall_satisfaction') == $key ? 'selected' : ''); ?>>
                                            <?php echo e($value); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="visit_count">عدد الزيارات:</label>
                                <select name="visit_count" id="visit_count" class="form-control">
                                    <option value="">جميع الأعداد</option>
                                    <?php $__currentLoopData = $visitCountOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(request('visit_count') == $key ? 'selected' : ''); ?>>
                                            <?php echo e($value); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender">الجنس:</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="">كلا الجنسين</option>
                                    <?php $__currentLoopData = $genderOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(request('gender') == $key ? 'selected' : ''); ?>>
                                            <?php echo e($value); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="age_group">الفئة العمرية:</label>
                                <select name="age_group" id="age_group" class="form-control">
                                    <option value="">جميع الفئات</option>
                                    <?php $__currentLoopData = $ageGroupOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(request('age_group') == $key ? 'selected' : ''); ?>>
                                            <?php echo e($value); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="from_date">من تاريخ:</label>
                                <input type="date" name="from_date" id="from_date" class="form-control" value="<?php echo e(request('from_date')); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="to_date">إلى تاريخ:</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" value="<?php echo e(request('to_date')); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="search">بحث:</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="ابحث برقم الاستبيان أو الملاحظات" value="<?php echo e(request('search')); ?>">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter mr-1"></i> تطبيق الفلاتر</button>
                    <a href="<?php echo e(route('surveys.index')); ?>" class="btn btn-secondary btn-sm ml-2"><i class="fas fa-undo mr-1"></i> إعادة تعيين</a>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-info text-white">
                            <tr>
                                <th>تاريخ الإدخال</th>
                                <th>رقم الاستبيان</th>
                                <th>الرضا العام</th>
                                <th>عدد الزيارات</th>
                                <th>مدة الإقامة</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $surveys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $survey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($survey->created_at->format('Y-m-d H:i:s')); ?></td>
                                    <td>📄 <?php echo e($survey->survey_number); ?></td>
                                    <td>
                                        <?php
                                            $satisfactionText = match($survey->overall_satisfaction) {
                                                'very_satisfied' => 'راض جدًا',
                                                'satisfied' => 'راض',
                                                'acceptable' => 'مقبول',
                                                'dissatisfied' => 'غير راض',
                                                default => 'غير محدد',
                                            };
                                            $satisfactionColor = match($survey->overall_satisfaction) {
                                                'very_satisfied' => 'badge-success',
                                                'satisfied' => 'badge-primary',
                                                'acceptable' => 'badge-warning',
                                                'dissatisfied' => 'badge-danger',
                                                default => 'badge-secondary',
                                            };
                                        ?>
                                        <span class="badge <?php echo e($satisfactionColor); ?>"><?php echo e($satisfactionText); ?></span>
                                    </td>
                                    <td>
                                        <?php
                                            $visitCountText = match($survey->visit_count) {
                                                'first_time' => 'أول مرة',
                                                '2_5_times' => '2-5 مرات',
                                                'over_5_times' => 'أكثر من 5',
                                                default => 'غير محدد',
                                            };
                                        ?>
                                        <?php echo e($visitCountText); ?>

                                    </td>
                                    <td>
                                        <?php
                                            $stayDurationText = match($survey->stay_duration) {
                                                'less_1h' => '< ساعة',
                                                '2_3h' => '2-3 ساعات',
                                                '4_6h' => '4-6 ساعات',
                                                'over_6h' => '> 6 ساعات',
                                                default => 'غير محدد',
                                            };
                                        ?>
                                        <?php echo e($stayDurationText); ?>

                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('surveys.show', $survey->id)); ?>" class="btn btn-sm btn-info" title="عرض"><i class="fas fa-eye"></i></a>
                                            <a href="<?php echo e(route('surveys.edit', $survey->id)); ?>" class="btn btn-sm btn-primary ml-1" title="تعديل"><i class="fas fa-edit"></i></a>
                                            <form action="<?php echo e(route('surveys.destroy', $survey->id)); ?>" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الاستبيان؟')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger ml-1" title="حذف"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center">لا توجد استبيانات لعرضها.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <?php echo e($surveys->links('vendor.pagination.bootstrap-4')); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/surveys/index.blade.php ENDPATH**/ ?>