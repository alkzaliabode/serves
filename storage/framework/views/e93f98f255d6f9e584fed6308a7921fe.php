 

<?php $__env->startSection('title', 'الموقف اليومي - ' . \Carbon\Carbon::parse($dailyStatus->date)->format('Y-m-d')); ?>

<?php $__env->startSection('page_title', 'الموقف اليومي'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('daily-statuses.index')); ?>">الموقف اليومي</a></li>
    <li class="breadcrumb-item active">عرض الموقف</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <style>
        /* أنماط الطباعة */
        @page {
            size: A4;
            margin: 5mm; /* تقليل الهامش الكلي للصفحة بشكل أكبر */
        }
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.2; /* تقليل ارتفاع السطر لضغط المحتوى */
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 11px; /* تقليل حجم الخط الأساسي بشكل أكبر */
            direction: rtl; /* لضمان الاتجاه من اليمين لليسار */
            text-align: right; /* لمحاذاة النص لليمين */
        }
        .print-container { /* Renamed from .container-print */
            width: 100%;
            max-width: 200mm; /* عرض أقصى للحاوية لترك هوامش كافية عند الطباعة */
            margin: 0 auto;
            padding: 3mm; /* تقليل الحشوة الداخلية للحاوية */
            border: 1.5px solid #333; /* تقليل سمك الحدود */
            box-sizing: border-box;
            background-color: #fff;
        }
        .header-print {
            text-align: center;
            margin-bottom: 10px; /* تقليل المسافة بعد الهيدر */
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 5px; /* تقليل الحشوة السفلية للهيدر */
            border-bottom: 1px solid #bbb; /* تقليل سمك الحد */
        }
        .header-print .logo {
            width: 60px; /* حجم أصغر للشعار */
            height: 60px;
            object-fit: contain;
            margin-left: 5px; /* تقليل المسافة */
        }
        .header-print .text-content {
            flex-grow: 1;
            text-align: center;
        }
        .title-print {
            font-size: 18px; /* تقليل حجم العنوان الرئيسي */
            font-weight: bold;
            margin: 0;
            color: #000;
        }
        .subtitle-print {
            font-size: 13px; /* تقليل حجم العناوين الفرعية */
            margin: 3px 0; /* تقليل المسافة العمودية */
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0; /* تقليل المسافة حول الجداول بشكل كبير */
            font-size: 10px; /* حجم خط الجدول أصغر جداً */
        }
        th, td {
            border: 1px solid #333; /* تقليل سمك الحدود */
            padding: 3px; /* تقليل الحشوة داخل الخلايا بشكل كبير */
            text-align: center;
            vertical-align: middle;
            color: #333;
        }
        th {
            background-color: #666;
            font-weight: bold;
            color: #fff;
            font-size: 11px; /* حجم أصغر لأسماء الحقول */
        }
        td {
            background-color: #f8f8f8;
            color: #444;
            font-size: 10px; /* حجم نص أصغر داخل الخلايا */
        }
        .table-title {
            font-size: 14px; /* تقليل حجم عنوان الجدول */
            font-weight: bold;
            text-align: right;
            margin-top: 8px; /* تقليل المسافة قبل العنوان */
            margin-bottom: 3px; /* تقليل المسافة بعد العنوان */
            border-bottom: 1px solid #666; /* تقليل سمك الحد */
            padding-bottom: 3px; /* تقليل الحشوة السفلية */
            color: #000;
        }
        .two-column-tables {
            display: flex;
            justify-content: space-between;
            flex-wrap: nowrap; /* مهم: منع الجداول من الانتقال لسطر جديد */
            margin-bottom: 5px; /* تقليل المسافة بين المجموعات */
            align-items: flex-start; /* لرفع المحتوى لأعلى */
        }
        .two-column-tables > div {
            width: 49.5%; /* ترك مسافة صغيرة بين العمودين */
            box-sizing: border-box;
        }
        .two-column-tables table {
            margin: 0;
            width: 100%;
        }

        /* ** تنسيقات التوقيعات الجديدة ** */
        .signatures-container {
            margin-top: 15px; /* تقليل المسافة قبل التوقيعات */
            overflow: hidden;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 12px; /* حجم خط أصغر للتوقيعات */
            color: #333;
        }
        .signature-block {
            width: 48%;
            margin-top: 5px; /* تقليل المسافة العلوية */
            padding: 3px; /* تقليل الحشوة */
            box-sizing: border-box;
            border-top: 1px dashed #666; /* تقليل سمك الخط المتقطع */
            padding-top: 5px; /* تقليل الحشوة العلوية */
        }
        .responsible-signature {
            text-align: right;
        }
        .organizer-signature {
            text-align: left;
        }
        .signature-line {
            margin-top: 5px; /* تقليل المسافة بين النص وسطر التوقيع */
            font-weight: bold;
        }
        .signature-line div {
            margin-bottom: 3px; /* تقليل المسافة بين سطور التوقيع */
        }

        .department { text-align: center; margin-top: 8px; font-weight: bold; font-size: 12px; color: #333; }

        /* طباعة CSS */
        @media print {
            .app-header, .app-sidebar, .app-footer, .app-content-header, .no-print-adminlte,
            .main-header, .main-sidebar, .main-footer, .content-header, .control-sidebar,
            .preloader, .wrapper > .content-wrapper, body > .wrapper > .content-wrapper .card-tools,
            .btn, .card-tools, .card-header .card-tools {
                display: none !important;
            }

            @page {
                size: A4 portrait; /* Updated as per request */
                margin: 0; /* Updated as per request */
            }

            body {
                font-size: 10px; /* حجم خط أصغر للطباعة */
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                margin: 0; /* Updated as per request */
                padding: 0; /* Updated as per request */
                width: 100%;
                min-width: 0 !important;
            }

            .wrapper {
                width: 100% !important;
                margin-left: 0 !important;
                padding-left: 0 !important;
            }

            .content-wrapper, .main-panel, .content-wrapper .container-fluid {
                padding: 0 !important;
                margin-left: 0 !important;
                min-height: 0 !important;
                width: 100% !important;
                float: none !important;
            }

            .container-fluid {
                padding: 0 !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
                margin-bottom: 0 !important;
            }

            .card-header, .card-body {
                padding: 0 !important;
            }

            .print-container { /* Renamed from .container-print */
                border: 1.5px solid #333 !important;
                width: 100% !important;
                max-width: 100% !important;
                height: 100% !important; /* Added as per request */
                padding: 0 !important; /* Updated as per request */
                margin: 0 !important; /* Updated as per request */
                box-sizing: border-box !important;
                box-shadow: none !important;
            }
            table {
                font-size: 9px; /* حجم خط أصغر جداً في الجداول للطباعة */
                page-break-inside: avoid; /* منع تقسيم الجداول عبر الصفحات */
            }
            th, td {
                padding: 2px; /* تقليل الحشوة للخلايا في الطباعة بشكل أكبر */
            }
            th { background-color: #666 !important; color: #fff !important; text-shadow: none !important; }
            td { background-color: #f8f8f8 !important; color: #444 !important; }

            .header-print { margin-bottom: 8px; border-bottom: 1px solid #bbb !important;}
            .title-print { font-size: 16px; text-shadow: none !important; }
            .subtitle-print { font-size: 12px; text-shadow: none !important; }
            .table-title { font-size: 13px; margin-top: 5px; border-bottom: 1px solid #666 !important; text-shadow: none !important; padding-bottom: 2px;}
            .two-column-tables {
                flex-wrap: nowrap; /* تأكيد عدم كسر السطر */
                align-items: flex-start; /* رفع المحتوى */
                margin-bottom: 5px; /* تقليل المسافة بين المجموعات في الطباعة */
            }
            .two-column-tables > div {
                width: 49.5%; /* التأكيد على أن تأخذ مساحة متساوية */
            }
            .signatures-container { font-size: 11px; margin-top: 10px; } /* تقليل حجم الخط والمسافة في التوقيعات للطباعة */
            .signature-block { width: 48%; border-top: 1px dashed #666 !important; padding-top: 5px; }
            .signature-line { margin-top: 3px; }
            .signature-line div { margin-bottom: 2px; }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card card-primary card-outline">
        <div class="card-header no-print-adminlte">
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                    <i data-lte-icon="plus" class="bi bi-plus-lg"></i>
                    <i data-lte-icon="minus" class="bi bi-dash-lg" style="display: none;"></i>
                </button>
                <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="print-container" lang="ar" dir="rtl"> 

                <div class="header-print">
                    <img src="<?php echo e(asset('images/logo.png')); ?>"
                               alt="شعار المدينة"
                               class="logo"
                               onerror="this.onerror=null; this.src='https://placehold.co/60x60/CCCCCC/666666?text=شعار';"
                               title="إذا لم يظهر الشعار، تأكد من مسار الصورة في مجلد public/images">
                    <div class="text-content">
                        <div class="title-print">الموقف اليومي للمنتسبين</div>
                        <div class="subtitle-print">قسم مدينة الإمام الحسين (ع) للزائرين</div>
                        <div class="subtitle-print">الموقف الخاص بالشعبة الخدمية</div>
                    </div>
                </div>

                <table>
                    <tr>
                        <td colspan="2">اليوم: <strong><?php echo e($dailyStatus->day_name); ?></strong></td>
                        <td colspan="2">التاريخ: <strong><?php echo e($dailyStatus->hijri_date); ?> (<?php echo e(\Carbon\Carbon::parse($dailyStatus->date)->format('d/m/Y')); ?>)</strong></td>
                    </tr>
                </table>

                
                <div class="two-column-tables">
                    <?php if(!empty($dailyStatus->periodic_leaves) && count($dailyStatus->periodic_leaves) > 0): ?>
                    <div>
                        <div class="table-title">الإجازات الدورية</div>
                        <table>
                            <thead>
                                <tr>
                                    <th>م</th>
                                    <th>الاسم</th>
                                    <th>الرقم الوظيفي</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dailyStatus->periodic_leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($leave['employee_name'] ?? ''); ?></td>
                                    <td><?php echo e($leave['employee_number'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>

                    <?php if(!empty($dailyStatus->annual_leaves) && count($dailyStatus->annual_leaves) > 0): ?>
                    <div>
                        <div class="table-title">الإجازات السنوية</div>
                        <table>
                            <thead>
                                <tr>
                                    <th>م</th>
                                    <th>الاسم</th>
                                    <th>الرقم الوظيفي</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dailyStatus->annual_leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($leave['employee_name'] ?? ''); ?></td>
                                    <td><?php echo e($leave['employee_number'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="two-column-tables"> 
                    <?php if(!empty($dailyStatus->eid_leaves) && count($dailyStatus->eid_leaves) > 0): ?>
                    <div>
                        <div class="table-title">إجازات الأعياد</div>
                        <table>
                            <thead>
                                <tr>
                                    <th>م</th>
                                    <th>نوع العيد</th>
                                    <th>الاسم</th>
                                    <th>الرقم الوظيفي</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dailyStatus->eid_leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td>
                                        <?php
                                            $eidType = $leave['eid_type'] ?? '';
                                            echo match ($eidType) {
                                                'eid_alfitr' => 'عيد الفطر',
                                                'eid_aladha' => 'عيد الأضحى',
                                                'eid_algahdir' => 'عيد الغدير',
                                                default => $eidType
                                            };
                                        ?>
                                    </td>
                                    <td><?php echo e($leave['employee_name'] ?? ''); ?></td>
                                    <td><?php echo e($leave['employee_number'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>

                    <?php if(!empty($dailyStatus->guard_rest) && count($dailyStatus->guard_rest) > 0): ?>
                    <div>
                        <div class="table-title">استراحة خفر</div>
                        <table>
                            <thead>
                                <tr>
                                    <th>م</th>
                                    <th>الاسم</th>
                                    <th>الرقم الوظيفي</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dailyStatus->guard_rest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $rest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($rest['employee_name'] ?? ''); ?></td>
                                    <td><?php echo e($rest['employee_number'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>

                
                <div class="two-column-tables">
                    <?php if(!empty($dailyStatus->bereavement_leaves) && count($dailyStatus->bereavement_leaves) > 0): ?>
                    <div>
                        <div class="table-title">إجازة الوفاة</div>
                        <table>
                            <thead>
                                <tr>
                                    <th>م</th>
                                    <th>الاسم</th>
                                    <th>الرقم الوظيفي</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dailyStatus->bereavement_leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($leave['employee_name'] ?? ''); ?></td>
                                    <td><?php echo e($leave['employee_number'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>

                    <?php if(!empty($dailyStatus->unpaid_leaves) && count($dailyStatus->unpaid_leaves) > 0): ?>
                    <div>
                        <div class="table-title">إجازة بدون راتب</div>
                        <table>
                            <thead>
                                <tr>
                                    <th>م</th>
                                    <th>الاسم</th>
                                    <th>الرقم الوظيفي</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dailyStatus->unpaid_leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($leave['employee_name'] ?? ''); ?></td>
                                    <td><?php echo e($leave['employee_number'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
                

                
                <?php if(!empty($dailyStatus->sick_leaves) && count($dailyStatus->sick_leaves) > 0): ?>
                <div class="table-title">الإجازات المرضية</div>
                <table>
                    <thead>
                        <tr>
                            <th>م</th>
                            <th>الاسم</th>
                            <th>الرقم الوظيفي</th>
                            <th>مدة الإجازة</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $dailyStatus->sick_leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($leave['employee_name'] ?? ''); ?></td>
                            <td><?php echo e($leave['employee_number'] ?? ''); ?></td>
                            <td>
                                <?php
                                    $displayDuration = 'بيانات غير متوفرة';
                                    if (isset($leave['start_date']) && isset($leave['total_days'])) {
                                        $startDate = \Carbon\Carbon::parse($leave['start_date']);
                                        $currentDate = \Carbon\Carbon::parse($dailyStatus->date);
                                        $totalDays = $leave['total_days'];
                                        $dayOfLeave = $startDate->diffInDays($currentDate) + 1; // +1 لأن اليوم الأول هو 1

                                        if ($dayOfLeave > $totalDays) {
                                            $displayDuration = 'انتهت الإجازة';
                                        } else {
                                            $displayDuration = "اليوم $dayOfLeave من $totalDays يوم";
                                        }
                                    } elseif (isset($leave['from_date']) && isset($leave['to_date'])) {
                                        // Fallback for old data if it still exists
                                        $displayDuration = \Carbon\Carbon::parse($leave['from_date'])->format('Y-m-d') . ' - ' . \Carbon\Carbon::parse($leave['to_date'])->format('Y-m-d');
                                    }
                                    echo $displayDuration;
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php endif; ?>
                


                
                <?php if(!empty($dailyStatus->custom_usages) && count($dailyStatus->custom_usages) > 0): ?>
                <div class="table-title">الاستخدام</div>
                <table>
                    <thead>
                        <tr>
                            <th>م</th>
                            <th>الاسم</th>
                            <th>الرقم الوظيفي</th>
                            <th>تفاصيل الاستخدام</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $dailyStatus->custom_usages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $usage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($usage['employee_name'] ?? ''); ?></td>
                            <td><?php echo e($usage['employee_number'] ?? ''); ?></td>
                            <td><?php echo e($usage['usage_details'] ?? '&mdash;'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php endif; ?>

                

                <?php if(!empty($dailyStatus->temporary_leaves) && count($dailyStatus->temporary_leaves) > 0): ?>
                <div class="table-title">الإجازات الزمنية</div>
                <table>
                    <thead>
                        <tr>
                            <th>م</th>
                            <th>الاسم</th>
                            <th>الرقم الوظيفي</th>
                            <th>الوقت</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $dailyStatus->temporary_leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($leave['employee_name'] ?? ''); ?></td>
                            <td><?php echo e($leave['employee_number'] ?? ''); ?></td>
                            <td>
                                <?php echo e(\Carbon\Carbon::parse($leave['from_time'])->format('H:i') ?? ''); ?> -
                                <?php echo e(\Carbon\Carbon::parse($leave['to_time'])->format('H:i') ?? ''); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php endif; ?>

                <?php if(!empty($dailyStatus->absences) && count($dailyStatus->absences) > 0): ?>
                <div class="table-title">الغياب</div>
                <table>
                    <thead>
                        <tr>
                            <th>م</th>
                            <th>الاسم</th>
                            <th>الرقم الوظيفي</th>
                            <th>مدة الغياب</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $dailyStatus->absences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $absence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($absence['employee_name'] ?? ''); ?></td>
                            <td><?php echo e($absence['employee_number'] ?? ''); ?></td>
                            <td>
                                <?php
                                    $displayDuration = 'بيانات غير متوفرة';
                                    if (isset($absence['start_date']) && isset($absence['total_days'])) {
                                        $startDate = \Carbon\Carbon::parse($absence['start_date']);
                                        $currentDate = \Carbon\Carbon::parse($dailyStatus->date);
                                        $totalDays = $absence['total_days'];
                                        $dayOfAbsence = $startDate->diffInDays($currentDate) + 1;

                                        if ($dayOfAbsence > $totalDays) {
                                            $displayDuration = 'انتهى الغياب';
                                        } else {
                                            $displayDuration = "اليوم $dayOfAbsence من $totalDays يوم";
                                        }
                                    } elseif (isset($absence['from_date']) && isset($absence['to_date'])) {
                                        // Fallback for old data if it still exists
                                        $displayDuration = \Carbon\Carbon::parse($absence['from_date'])->format('Y-m-d') . ' - ' . \Carbon\Carbon::parse($absence['to_date'])->format('Y-m-d');
                                    }
                                    echo $displayDuration;
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php endif; ?>

                <?php if(!empty($dailyStatus->long_leaves) && count($dailyStatus->long_leaves) > 0): ?>
                <div class="table-title">الإجازات الطويلة</div>
                <table>
                    <thead>
                        <tr>
                            <th>م</th>
                            <th>الاسم</th>
                            <th>الرقم الوظيفي</th>
                            <th>مدة الإجازة</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $dailyStatus->long_leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($leave['employee_name'] ?? ''); ?></td>
                            <td><?php echo e($leave['employee_number'] ?? ''); ?></td>
                            <td>
                                <?php
                                    $displayDuration = 'بيانات غير متوفرة';
                                    if (isset($leave['start_date']) && isset($leave['total_days'])) {
                                        $startDate = \Carbon\Carbon::parse($leave['start_date']);
                                        $currentDate = \Carbon\Carbon::parse($dailyStatus->date);
                                        $totalDays = $leave['total_days'];
                                        $dayOfLeave = $startDate->diffInDays($currentDate) + 1;

                                        if ($dayOfLeave > $totalDays) {
                                            $displayDuration = 'انتهت الإجازة';
                                        } else {
                                            $displayDuration = "اليوم $dayOfLeave من $totalDays يوم";
                                        }
                                    } elseif (isset($leave['from_date']) && isset($leave['to_date'])) {
                                        // Fallback for old data if it still exists
                                        $displayDuration = \Carbon\Carbon::parse($leave['from_date'])->format('Y-m-d') . ' - ' . \Carbon\Carbon::parse($leave['to_date'])->format('Y-m-d');
                                    }
                                    echo $displayDuration;
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php endif; ?>

                <table>
                    <?php
                        $totalRequired = $dailyStatus->total_required ?? 86;
                        // Use a try-catch block to safely access Employee model, as it might not be available
                        try {
                            $totalEmployees = \App\Models\Employee::where('is_active', 1)->count();
                        } catch (Throwable $e) {
                            $totalEmployees = 0; // Fallback if model is not accessible
                        }
                        $shortage = $totalRequired - $totalEmployees;

                        $paidLeavesCount = count($dailyStatus->annual_leaves ?? [])
                                            + count($dailyStatus->periodic_leaves ?? [])
                                            + count($dailyStatus->sick_leaves ?? [])
                                            + count($dailyStatus->bereavement_leaves ?? []);

                        $eidLeavesCount = 0;
                        foreach ($dailyStatus->eid_leaves ?? [] as $eidLeave) {
                            if (isset($eidLeave['employee_id'])) {
                                $eidLeavesCount++;
                            }
                        }
                        $paidLeavesCount += $eidLeavesCount;

                        $unpaidLeavesCount = count($dailyStatus->unpaid_leaves ?? []);
                        $absencesCount = count($dailyStatus->absences ?? []);
                        $temporaryLeavesCount = count($dailyStatus->temporary_leaves ?? []);
                        $guardRestCount = count($dailyStatus->guard_rest ?? []);
                        $customUsagesCount = count($dailyStatus->custom_usages ?? []);

                        // حساب الحضور الفعلي
                        // العدد الإجمالي للموظفين ناقص كل من هو في إجازة (بأنواعها) أو غياب أو استراحة خفر أو استخدام مخصص
                        $actualAttendance = $totalEmployees - (
                            count($dailyStatus->periodic_leaves ?? []) +
                            count($dailyStatus->annual_leaves ?? []) +
                            count($dailyStatus->eid_leaves ?? []) +
                            count($dailyStatus->sick_leaves ?? []) +
                            count($dailyStatus->bereavement_leaves ?? []) +
                            count($dailyStatus->unpaid_leaves ?? []) +
                            count($dailyStatus->absences ?? []) +
                            count($dailyStatus->temporary_leaves ?? []) +
                            count($dailyStatus->guard_rest ?? []) +
                            count($dailyStatus->custom_usages ?? [])
                        );
                    ?>
                    <tr>
                        <th>الملاك</th>
                        <th>الموجود الحالي</th>
                        <th>النقص</th>
                        <th>الحضور الفعلي</th>
                        <th>إجازات براتب</th>
                        <th>إجازات بدون راتب</th>
                        <th>الغياب</th>
                        <th>استراحة خفر</th>
                        <th>إجازات زمنية</th>
                        <th>إجازة وفاة</th>
                        <th>الاستخدام</th>
                        <th>تعيين</th>
                        <th>نقل</th>
                        <th>فصل</th>
                    </tr>
                    <tr>
                        <td><strong><?php echo e($totalRequired); ?></strong></td>
                        <td><strong><?php echo e($totalEmployees); ?></strong></td>
                        <td><strong><?php echo e($shortage); ?></strong></td>
                        <td><strong><?php echo e($actualAttendance); ?></strong></td>
                        <td><?php echo e($paidLeavesCount); ?></td>
                        <td><?php echo e($unpaidLeavesCount); ?></td>
                        <td><?php echo e($absencesCount); ?></td>
                        <td><?php echo e($guardRestCount); ?></td>
                        <td><?php echo e($temporaryLeavesCount); ?></td>
                        <td><?php echo e(count($dailyStatus->bereavement_leaves ?? [])); ?></td>
                        <td><?php echo e($customUsagesCount); ?></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                </table>
            </div>

            <div class="signatures-container">
                <div class="signature-block responsible-signature">
                    <div>مسؤول شعبة الخدمية</div>
                    <div class="signature-line">
                        <div>التوقيع: ........................</div>
                        <div>التاريخ: <?php echo e(\Carbon\Carbon::parse($dailyStatus->date)->addDay()->format('d/m/Y')); ?></div>
                    </div>
                </div>

                <?php if(!empty($dailyStatus->organizer_employee_name)): ?>
                <div class="signature-block organizer-signature">
                    <div>منظم الموقف: <strong><?php echo e($dailyStatus->organizer_employee_name); ?></strong></div>
                    <div class="signature-line">
                        <div>التوقيع: ........................</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="text-center mt-4 no-print-adminlte">
                
                <button onclick="window.print()" class="btn btn-success me-2">
                    <i class="fas fa-print"></i> طباعة التقرير (مباشر)
                </button>

                
                <a href="<?php echo e(route('daily-statuses.print.standalone', $dailyStatus->id)); ?>" target="_blank" class="btn btn-info me-2">
                    <i class="fas fa-file-pdf"></i> عرض للطباعة (صفحة مستقلة)
                </a>

                <button onclick="window.history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> العودة
                </button>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/daily_statuses/print.blade.php ENDPATH**/ ?>