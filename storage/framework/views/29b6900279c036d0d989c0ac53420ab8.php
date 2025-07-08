<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ملخص الحضور الشهري</title>

    <!-- Bootstrap CSS for basic styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* General styling for the page content */
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            background-color: #f4f6f9; /* Light background for screen view */
            margin: 0;
            padding: 20px; /* Padding for screen view */
            direction: rtl;
            text-align: right;
        }

        /* General card styling for screen view */
        .card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 0.75rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: none;
            color: #333;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #007bff; /* Primary blue for header */
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-title {
            color: white;
            font-weight: bold;
            margin-bottom: 0;
            font-size: 1.5rem;
        }
        .card-body {
            padding: 1.25rem;
        }

        /* Table styling */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #333;
            border-collapse: collapse;
        }
        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: middle; /* Center vertically */
            border: 1px solid #dee2e6;
            text-align: center;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: #e9ecef;
            color: #495057;
            font-weight: bold;
        }
        .table tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.03); /* Lighter zebra stripping */
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.08); /* Light blue on hover */
        }

        /* Hide elements not needed for print */
        .no-print {
            display: block; /* Visible by default */
        }

        /* Report Header (visible on screen and print) */
        .report-header {
            display: flex; /* Always flex for layout */
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .report-header .logo {
            width: 70px; /* Default size for screen view */
            height: 70px; /* Default size for screen view */
            object-fit: contain;
            margin: 0 10px;
        }
        .report-header .text-content h4 {
            font-size: 1.5em; /* Default font size for screen view */
            font-weight: bold;
            margin: 0;
            line-height: 1.2;
        }
        .report-header .text-content p {
            font-size: 0.9em; /* Default font size for screen view */
            color: #555;
            margin: 0;
            line-height: 1.2;
        }

        /* Footer for print only (hidden on screen) */
        .signatures-print {
            display: none; /* Hidden by default, shown only on print */
            margin-top: 40px;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 0.9em;
            color: #333;
        }
        .signature-block-print {
            width: 45%;
            text-align: center;
            border-top: 1px dashed #666;
            padding-top: 10px;
        }


        /* Print specific styles */
        @media print {
            body {
                font-family: 'Arial', sans-serif;
                font-size: 10pt;
                margin: 0;
                padding: 0;
                direction: rtl;
                text-align: right;
                -webkit-print-color-adjust: exact; /* Ensure colors are printed as is */
                color-adjust: exact;
                background-color: white !important; /* Force white background for print */
            }

            /* Hide elements not needed for print */
            .no-print {
                display: none !important;
            }

            /* Card styling for print */
            .card {
                box-shadow: none !important;
                border: none !important; /* Remove card border for cleaner print */
                background-color: white !important;
                border-radius: 0 !important;
                margin: 0; /* Remove margin for print */
            }
            .card-header, .card-title {
                background-color: #f8f9fa !important; /* Lighter header for print */
                color: #333 !important;
                border-bottom: 1px solid #ccc !important;
                border-radius: 0 !important;
                padding: 8px 15px; /* Adjust padding for print */
            }
            .card-body {
                padding: 15px; /* Adjust padding for print */
            }

            /* Table styling for print */
            .table th, .table td {
                border: 1px solid #ccc !important;
                padding: 4px !important; /* Smaller padding for print */
                font-size: 8.5pt !important; /* Smaller font for print */
            }
            .table thead th {
                background-color: #e9ecef !important;
                color: #495057 !important;
                border-bottom: 1px solid #ccc !important; /* Ensure header border is visible */
            }
            .table tbody tr:nth-of-type(odd) {
                background-color: #f2f2f2 !important;
            }

            /* Print-only header and footer */
            .report-header { /* Use report-header for print as well */
                display: flex !important; /* Ensure it's visible and flex */
            }
            .report-header .logo {
                width: 40px; /* Reduced width for print */
                height: 40px; /* Reduced height for print */
                margin: 0 5px; /* Reduced margin */
            }
            .report-header .text-content h4 {
                font-size: 1.1em; /* Adjusted font size for print */
            }
            .report-header .text-content p {
                font-size: 0.75em; /* Adjusted font size for print */
            }

            .signatures-print {
                display: flex !important; /* Show only on print */
                justify-content: space-between;
                margin-top: 30px;
                font-size: 9pt;
            }
            .signature-block-print {
                width: 48%;
                text-align: center;
                border-top: 1px dashed #666;
                padding-top: 8px;
            }

            /* Page border for print */
            @page {
                size: landscape; /* Request landscape orientation */
                margin: 15mm; /* Set overall page margins for print */
            }
            body::before {
                content: '';
                position: fixed;
                top: 10mm; /* Adjust as needed for top margin */
                left: 10mm; /* Adjust as needed for left margin */
                right: 10mm; /* Adjust as needed for right margin */
                bottom: 10mm; /* Adjust as needed for bottom margin */
                border: 2px solid #000; /* Black border, adjust color/thickness as desired */
                z-index: -1; /* Ensure it's behind the content */
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header no-print"> 
                <h3 class="card-title">ملخص الحضور والانصراف للمنتسبين</h3>
                <div class="card-tools">
                    <button class="btn btn-primary btn-sm" onclick="window.print()">
                        <i class="fas fa-print"></i> طباعة الملخص
                    </button>
                </div>
            </div>
            <div class="card-body">
                
                <div class="report-header">
                    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="شعار المؤسسة 1" class="logo"
                         onerror="this.onerror=null; this.src='https://placehold.co/70x70/CCCCCC/666666?text=شعار1';">
                    <div class="text-content">
                        <h4>الموقف اليومي للمنتسبين</h4>
                        <p>قسم مدينة الإمام الحسين (ع) للزائرين</p>
                        <p>الموقف الخاص بالشعبة الخدمية</p>
                    </div>
                    <img src="<?php echo e(asset('images/another_logo.png')); ?>" alt="شعار المؤسسة 2" class="logo"
                         onerror="this.onerror=null; this.src='https://placehold.co/70x70/CCCCCC/666666?text=شعار2';">
                </div>

                <div class="row mb-3 no-print">
                    <div class="col-md-4">
                        <label for="month_select">اختر الشهر:</label>
                        <select id="month_select" class="form-control" onchange="navigateToMonth()">
                            <?php for($i = 1; $i <= 12; $i++): ?>
                                <option value="<?php echo e($i); ?>" <?php echo e($month == $i ? 'selected' : ''); ?>>
                                    <?php echo e(\Carbon\Carbon::create(null, $i, 1)->monthName); ?>

                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="year_select">اختر السنة:</label>
                        <select id="year_select" class="form-control" onchange="navigateToMonth()">
                            <?php for($i = \Carbon\Carbon::now()->year - 5; $i <= \Carbon\Carbon::now()->year + 1; $i++): ?>
                                <option value="<?php echo e($i); ?>" <?php echo e($year == $i ? 'selected' : ''); ?>>
                                    <?php echo e($i); ?>

                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <p class="form-control-static mb-0">
                            <strong>ملخص شهر <?php echo e(\Carbon\Carbon::create(null, $month, 1)->monthName); ?> <?php echo e($year); ?></strong>
                        </p>
                    </div>
                </div>

                <p class="text-center font-weight-bold">
                    ملخص شهر <?php echo e(\Carbon\Carbon::create(null, $month, 1)->monthName); ?> منتسبين شعبة الخدمية
                </p>
                <p class="text-center">
                    التاريخ: <?php echo e(\Carbon\Carbon::now()->format('d/m/Y')); ?>

                </p>

                <?php if(empty($finalSummary)): ?>
                    <div class="alert alert-info text-center mt-4">
                        لا توجد بيانات متاحة لهذا الشهر.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ت</th>
                                    <th>اسم المنتسب</th>
                                    <th>الرقم الوظيفي</th>
                                    <th>عدد أيام الدوام</th>
                                    <th>عدد الإجازات الدورية</th>
                                    <th>عدد الإجازات السنوية</th>
                                    <th>عدد الساعات الزمنية خلال الشهر</th>
                                    <th>عدد أيام الغياب</th>
                                    <th>عدد الأيام بدون راتب</th>
                                    <th>عدد الإجازات المرضية</th>
                                    <th>عدد إجازات الوفاة</th>
                                    <th>عدد إجازات الأعياد</th>
                                    <th>عدد إجازات الخفر</th>
                                    <th>عدد إجازات الزواج الهدية</th>
                                    <th>إجازات أخرى</th>
                                    <th>إجمالي الساعات الشهرية</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1; ?>
                                <?php $__currentLoopData = $finalSummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employeeId => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($counter++); ?></td>
                                    <td><?php echo e($data['employee_name'] ?? 'غير معروف'); ?></td>
                                    <td><?php echo e($data['employee_number'] ?? '&mdash;'); ?></td>
                                    <td><?php echo e($data['total_working_days'] ?? 0); ?></td>
                                    <td><?php echo e($data['periodic_leaves_count'] ?? 0); ?></td>
                                    <td><?php echo e($data['annual_leaves_count'] ?? 0); ?></td>
                                    <td><?php echo e($data['temporary_leaves_hours'] ?? 0); ?></td>
                                    <td><?php echo e($data['absences_days'] ?? 0); ?></td>
                                    <td><?php echo e($data['unpaid_leaves_count'] ?? 0); ?></td>
                                    <td><?php echo e($data['sick_leaves_days'] ?? 0); ?></td>
                                    <td><?php echo e($data['bereavement_leaves_count'] ?? 0); ?></td>
                                    <td><?php echo e($data['eid_leaves_count'] ?? 0); ?></td>
                                    <td><?php echo e($data['guard_rest_count'] ?? 0); ?></td>
                                    <td><?php echo e($data['wedding_leaves_count'] ?? 0); ?></td>
                                    <td><?php echo e($data['other_leaves_count'] ?? 0); ?></td>
                                    <td><?php echo e($data['monthly_hours_total'] ?? 0); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                
                <div class="signatures-print">
                    <div class="signature-block-print">
                        <div>معاون مدير المدينة</div>
                        <div>التوقيع: ........................</div>
                    </div>
                    <div class="signature-block-print">
                        <div>مسؤول الشعبة الخدمية</div>
                        <div>التوقيع: ........................</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery for interactive elements (if needed for non-print view) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script>
        // This function is for navigating between months/years on screen, not directly for print.
        function navigateToMonth() {
            const selectedMonth = document.getElementById('month_select').value;
            const selectedYear = document.getElementById('year_select').value;
            // Construct the URL for the monthly summary page
            window.location.href = `/monthly-summary/${selectedYear}/${selectedMonth}`;
        }
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/monthly_summary.blade.php ENDPATH**/ ?>