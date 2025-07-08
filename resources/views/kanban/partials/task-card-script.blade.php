{{-- resources/views/kanban/partials/task-card-script.blade.php --}}
{{--
    هذا الملف مخصص لسكريبتات JavaScript التي قد تحتاج إلى تهيئة عناصر معينة داخل كل بطاقة مهمة بشكل فردي
    (مثل Tooltips, Popovers, أو مكونات JavaScript أخرى خاصة بالبطاقة).

    ملاحظة هامة: المنطق الخاص بفتح مودال التعديل عند النقر على زر "تعديل"
    يجب أن يتم التعامل معه في الملف الرئيسي (service-tasks-board.blade.php) باستخدام
    "Event Delegation" على العنصر الأب (kanban-board) لضمان عمله مع البطاقات التي يتم تحميلها ديناميكيًا
    وتجنب تكرار الكود.
--}}
<script>
    /**
     * دالة لتهيئة أي سكربتات خاصة ببطاقة مهمة فردية.
     * تُستدعى هذه الدالة إذا كانت هناك حاجة لتثبيت سلوكيات JavaScript معقدة لكل بطاقة،
     * والتي لا يمكن التعامل معها بكفاءة عبر Event Delegation على العنصر الأب.
     *
     * @param {HTMLElement} cardElement - عنصر DOM الخاص ببطاقة المهمة.
     */
    function initializeTaskCard(cardElement) {
        // مثال: إذا كان لديك Tooltips أو Popovers على كل بطاقة، يمكنك تهيئتها هنا.
        // يجب أن تكون هذه التهيئات خاصة بالعناصر داخل كل بطاقة على حدة.
        // if (cardElement.querySelector('[data-bs-toggle="tooltip"]')) {
        //     new bootstrap.Tooltip(cardElement.querySelector('[data-bs-toggle="tooltip"]'));
        // }

        // يمكنك إضافة أي منطق تهيئة آخر هنا إذا كانت البطاقات تحتاج سكربتات فردية.
        // مثلاً، لربط أحداث مخصصة أو تهيئة مكتبات JavaScript أخرى لعناصر داخل البطاقة فقط.
    }

    // عند تحميل الصفحة، يمكن استدعاء initializeTaskCard لكل البطاقات الموجودة بالفعل.
    // (هذه الخطوة ليست ضرورية إذا كان كل شيء يتم التعامل معه عبر event delegation)
    document.querySelectorAll('.kanban-card').forEach(cardElement => {
        initializeTaskCard(cardElement);
    });

    // **تذكير:**
    // المنطق الخاص بفتح مودال التعديل عند النقر على زر "تعديل" وجميع أحداث البطاقة الأخرى
    // التي تؤثر على مستوى اللوحة (مثل السحب والإفلات)، يتم التعامل معها في ملف
    // service-tasks-board.blade.php باستخدام Event Delegation على العنصر الأب #kanban-board.
    // هذا يضمن أن الأزرار تعمل للبطاقات الموجودة والجديدة ديناميكيًا،
    // ويمنع تكرار الكود وتضارب معالجات الأحداث.
</script>
