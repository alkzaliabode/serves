 

<?php $__env->startSection('title', 'إدارة الأدوار'); ?>

<?php $__env->startSection('page_title', 'إدارة الأدوار'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">إدارة الأدوار</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">قائمة الأدوار</h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('roles.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> إضافة دور جديد
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                            <?php echo e(session('error')); ?>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('الاسم')); ?></th>
                                    <th><?php echo e(__('الصلاحيات')); ?></th>
                                    <th style="width: 150px;"><?php echo e(__('الإجراءات')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($role->name); ?></td>
                                        <td>
                                            <?php $__empty_2 = true; $__currentLoopData = $role->permissions->pluck('name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                <span class="badge badge-secondary mr-1"><?php echo e($permission); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                <span class="badge badge-light"><?php echo e(__('لا توجد صلاحيات')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('roles.edit', $role)); ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i> تعديل
                                            </a>
                                            <form action="<?php echo e(route('roles.destroy', $role)); ?>" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الدور؟');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> حذف
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="3" class="text-center">لا توجد أدوار لعرضها.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <?php echo e($roles->links('vendor.pagination.bootstrap-4')); ?>

                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(document).ready(function() {
            // Alerts dismissal for AdminLTE
            $('.alert .close').on('click', function() {
                $(this).closest('.alert').alert('close');
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/roles/index.blade.php ENDPATH**/ ?>