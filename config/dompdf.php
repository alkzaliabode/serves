<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Set some default values. It is possible to add all defines that can be set
    | in dompdf_config.inc.php. You can also override the entire config file.
    |
    */
    'show_warnings' => false, // لا تظهر تحذيرات Dompdf كـ Exceptions في بيئة الإنتاج

    'public_path' => null, // يمكنك تعيين هذا إذا كنت بحاجة لتجاوز المسار العام

    /*
     * Dejavu Sans font is missing glyphs for converted entities, turn it off if you need to show € and £.
     */
    'convert_entities' => true,

    'options' => [
        /**
         * The location of the DOMPDF font directory
         *
         * The location of the directory where DOMPDF will store fonts and font metrics
         * Note: This directory must exist and be writable by the webserver process.
         * *Please note the trailing slash.*
         */
        'font_dir' => public_path('fonts/'), // تم تحديث هذا المسار
        'font_cache' => public_path('fonts/'), // وتم تحديث هذا المسار أيضًا

        /**
         * The location of a temporary directory.
         *
         * The directory specified must be writeable by the webserver process.
         * The temporary directory is required to download remote images and when
         * using the PDFLib back end.
         */
        'temp_dir' => sys_get_temp_dir(),

        /**
         * ==== IMPORTANT ====
         *
         * dompdf's "chroot": Prevents dompdf from accessing system files or other
         * files on the webserver. All local files opened by dompdf must be in a
         * subdirectory of this directory. DO NOT set it to '/' since this could
         * allow an attacker to use dompdf to read any files on the server. This
         * should be an absolute path.
         * This is only checked on command line call by dompdf.php, but not by
         * direct class use like:
         * $dompdf = new DOMPDF(); $dompdf->load_html($htmldata); $dompdf->render(); $pdfdata = $dompdf->output();
         */
        'chroot' => realpath(base_path()), // ✅ مهم للسماح بالوصول إلى الملفات المحلية (الخطوط، الصور)

        /**
         * Protocol whitelist
         */
        'allowed_protocols' => [
            'data://' => ['rules' => []],
            'file://' => ['rules' => []],
            'http://' => ['rules' => []],
            'https://' => ['rules' => []],
        ],

        /**
         * Operational artifact (log files, temporary files) path validation
         */
        'artifactPathValidation' => null,

        /**
         * @var string
         */
        'log_output_file' => null,

        /**
         * Whether to enable font subsetting or not.
         */
        'enable_font_subsetting' => false,

        /**
         * The PDF rendering backend to use
         */
        'pdf_backend' => 'CPDF',

        /**
         * html target media view which should be rendered into pdf.
         */
        'default_media_type' => 'print', // ✅ يفضل استخدام 'print' لتقارير PDF

        /**
         * The default paper size.
         */
        'default_paper_size' => 'A4', // ✅ حجم الورق الافتراضي

        /**
         * The default paper orientation.
         */
        'default_paper_orientation' => 'portrait', // ✅ اتجاه الورق الافتراضي

        /**
         * The default font family
         *
         * Used if no suitable fonts can be found. This must exist in the font folder.
         */
        'default_font' => 'Amiri', // ✅ تعيين خط Amiri كخط افتراضي

        /**
         * Image DPI setting
         */
        'dpi' => 96,

        /**
         * Enable embedded PHP
         */
        'enable_php' => false, // ✅ يفضل أن تكون 'false' لأسباب أمنية

        /**
         * Enable inline JavaScript
         */
        'enable_javascript' => true,

        /**
         * Enable remote file access
         */
        'enable_remote' => true, // ✅ يجب أن تكون 'true' للسماح بتحميل الصور عبر URL

        /**
         * List of allowed remote hosts
         */
        'allowed_remote_hosts' => null, // يمكنك تحديد النطاقات هنا إذا أردت تقييد الوصول

        /**
         * A ratio applied to the fonts height to be more like browsers' line height
         */
        'font_height_ratio' => 1.1,

        /**
         * Use the HTML5 Lib parser
         *
         * @deprecated This feature is now always on in dompdf 2.x
         */
        // 'enable_html5_parser' => true, // هذا الخيار لم يعد ضروريا في Dompdf 2.x فما فوق
    ],

];