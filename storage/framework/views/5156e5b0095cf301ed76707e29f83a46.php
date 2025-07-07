 

<?php $__env->startSection('title', 'إعدادات الخلفية'); ?> 

<?php $__env->startSection('page_title', 'تغيير صورة الخلفية'); ?> 

<?php $__env->startSection('breadcrumb'); ?> 
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">إعدادات الخلفية</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <style>
        /* تعريف متغيرات الألوان لضمان التناسق */
        :root {
            --accent-color: #00eaff; /* Light blue/cyan for interactive elements and emphasis */
            --glass-background: rgba(255, 255, 255, 0.08); /* Consistent transparent background for glass effect */
            --glass-border: 1px solid rgba(255, 255, 255, 0.2); /* Consistent transparent border */
            --glass-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); /* Consistent shadow */
            --text-primary-color: white;
            --text-shadow-strong: 2px 2px 5px rgba(0, 0, 0, 0.9);
            --text-shadow-medium: 1px 1px 3px rgba(0, 0, 0, 0.7);
            --text-shadow-light: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        /* أنماط البطاقات لتكون شفافة بالكامل مع تأثير زجاجي وخطوط بارزة */
        .card {
            background: var(--glass-background) !important;
            backdrop-filter: blur(8px) !important; /* تأثير الزجاج المتجمد */
            border-radius: 1rem !important; /* حواف مستديرة */
            box-shadow: var(--glass-shadow) !important;
            border: var(--glass-border) !important;
        }
        .card-header {
            background-color: rgba(255, 255, 255, 0.15) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
        }
        
        /* General text styling */
        body,
        .card-body {
            font-size: 1.05rem;
            line-height: 1.6;
            color: var(--text-primary-color) !important;
            text-shadow: var(--text-shadow-light) !important;
        }

        /* Titles and Headers */
        .card-title,
        .card-header h3.card-title {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: var(--accent-color) !important;
            text-shadow: var(--text-shadow-strong) !important;
            transition: color 0.3s ease, text-shadow 0.3s ease;
        }
        .card-title:hover,
        .card-header h3.card-title:hover {
            color: #ffffff !important;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 1.0) !important;
        }

        /* Form Controls (Inputs, Selects, Textareas) */
        .form-control,
        input[type="file"] {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: var(--text-primary-color) !important;
            text-shadow: var(--text-shadow-medium) !important;
            font-size: 1.1rem !important;
            padding: 0.75rem 1rem !important;
            border-radius: 0.5rem; /* Rounded corners for inputs */
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }
        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.2) !important;
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.5) !important;
        }

        /* Labels */
        .form-label,
        label {
            font-size: 1.1rem !important;
            font-weight: 600 !important;
            color: var(--accent-color) !important;
            text-shadow: var(--text-shadow-medium) !important;
            margin-bottom: 0.5rem; /* Spacing below label */
            display: block; /* Ensure label takes full width */
        }

        /* Buttons */
        .btn {
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 0.5rem; /* Rounded corners for buttons */
        }
        .btn-primary {
            background-color: #007bff !important;
            border-color: #007bff !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.4) !important;
        }
        .btn-primary:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.6) !important;
            filter: brightness(1.2);
        }

        /* Alerts */
        .alert {
            background-color: rgba(255, 255, 255, 0.9) !important;
            color: #333 !important;
            border-color: rgba(0, 0, 0, 0.2) !important;
            border-radius: 0.5rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .alert-success {
            background-color: rgba(40, 167, 69, 0.9) !important;
            color: white !important;
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.9) !important;
            color: white !important;
        }

        /* Image Preview Styling */
        .img-fluid.rounded {
            border: 3px solid var(--accent-color); /* Highlight current image */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4); /* Stronger shadow for image */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .img-fluid.rounded:hover {
            transform: scale(1.02); /* Slight zoom on hover */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
        }
        
        /* Form Text for hints */
        .form-text {
            font-size: 0.95rem !important;
            color: rgba(255, 255, 255, 0.7) !important;
            text-shadow: var(--text-shadow-light) !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?> 
    <div class="container-fluid">
        <div class="card"> 
            <div class="card-header">
                <h3 class="card-title">إدارة صورة الخلفية</h3>
            </div>
            <div class="card-body">
                
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('background-settings.update')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?> 

                    <div class="mb-4"> 
                        <label for="current_background" class="form-label">الخلفية الحالية المستخدمة في التطبيق:</label>
                        
                        <img src="<?php echo e($currentBackgroundUrl); ?>" 
                             alt="الخلفية الحالية" 
                             class="img-fluid rounded" 
                             style="max-width: 400px; height: auto; display: block; margin-top: 10px;"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x200/cccccc/ffffff?text=صورة+خلفية+غير+موجودة';"
                             title="صورة الخلفية الحالية">
                        <div class="form-text mt-2">هذه هي الصورة التي تظهر حاليًا كخلفية في لوحة التحكم والشريط الجانبي.</div>
                    </div>

                    <div class="mb-4">
                        <label for="background_image" class="form-label">اختر صورة خلفية جديدة للتحميل:</label>
                        <input class="form-control" type="file" id="background_image" name="background_image" accept="image/*">
                        <div class="form-text">
                            يرجى اختيار صورة جديدة (JPG, PNG, GIF, SVG). يفضل أن تكون عالية الدقة للحصول على أفضل النتائج.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fas fa-upload"></i> تحديث صورة الخلفية
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?> 

<?php $__env->startSection('scripts'); ?> 
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // يمكنك إضافة أي سكربتات خاصة بهذه الصفحة هنا.
            // مثلاً: معاينة الصورة قبل الرفع، أو رسائل تأكيد.
            // لتبسيط هذا المثال، لا توجد سكربتات JavaScript معقدة حالياً.

            // مثال بسيط لمعاينة الصورة قبل الرفع (اختياري)
            const input = document.getElementById('background_image');
            const preview = document.querySelector('.img-fluid.rounded');

            if (input && preview) {
                input.addEventListener('change', function(event) {
                    if (event.target.files && event.target.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                        };
                        reader.readAsDataURL(event.target.files[0]);
                    }
                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/admin/background-settings.blade.php ENDPATH**/ ?>