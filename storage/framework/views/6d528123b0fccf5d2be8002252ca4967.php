 

<?php $__env->startSection('title', 'إدارة المستخدمين'); ?> 

<?php $__env->startSection('page_title', 'إدارة المستخدمين'); ?> 

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">إدارة المستخدمين</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?> 
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">قائمة المستخدمين</h3>
                    <div class="card-tools">
                        
                       
    <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> إضافة مستخدم جديد
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
                                    <th><?php echo e(__('البريد الإلكتروني')); ?></th>
                                    <th><?php echo e(__('رقم الموظف')); ?></th>
                                    <th><?php echo e(__('المسمى الوظيفي')); ?></th>
                                    <th><?php echo e(__('القسم/الوحدة')); ?></th>
                                    <th><?php echo e(__('نشط')); ?></th>
                                    <th><?php echo e(__('الأدوار')); ?></th>
                                    <th style="width: 150px;"><?php echo e(__('الإجراءات')); ?></th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($user->name); ?></td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td><?php echo e($user->employee_id ?? '-'); ?></td>
                                        <td><?php echo e($user->job_title ?? '-'); ?></td>
                                        <td><?php echo e($user->unit ?? '-'); ?></td>
                                        <td>
                                            <?php if($user->is_active): ?>
                                                <span class="badge badge-success"><?php echo e(__('نعم')); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-danger"><?php echo e(__('لا')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php $__empty_2 = true; $__currentLoopData = $user->getRoleNames(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                <span class="badge badge-info mr-1"><?php echo e($role); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                <span class="badge badge-secondary"><?php echo e(__('لا يوجد دور')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit users')): ?>
                                                <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> تعديل
                                                </a>
                                            <?php endif; ?>

                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete users')): ?>
                                                <form action="<?php echo e(route('users.destroy', $user)); ?>" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا المستخدم؟');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> حذف
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">لا توجد بيانات مستخدمين لعرضها.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <?php echo e($users->links('vendor.pagination.bootstrap-4')); ?> 
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/users/index.blade.php ENDPATH**/ ?>