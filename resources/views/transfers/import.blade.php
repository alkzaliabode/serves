@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <strong>لم يتم استيراد أي بيانات. صحّح الأخطاء التالية:</strong>
        <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif
<details id="performance-import" class="border rounded p-3 mb-4" {{ $errors->any() ? 'open' : '' }}>
    <summary class="font-weight-bold">استيراد Excel — {{ \App\Support\PerformanceTransfer::title($transferKind) }}</summary>
    <p class="mt-3">استخدم نموذج هذه الصفحة، مع إبقاء أسماء الأعمدة وترتيبها. اكتب كل سجل في صف مستقل. التاريخ بصيغة يوم/شهر/سنة أو سنة-شهر-يوم، والوحدة باسمها المسجل أو معرفها. الحد الأقصى 5000 صف و10 ميغابايت.</p>
    @if ($transferKind === 'resources')
        <p>املأ التاريخ والوحدة وساعات العمل (رقم صحيح) وإجمالي المستلزمات والمعدات، والملاحظات اختيارية. الكفاءة و«كفء؟» حقول محسوبة للعرض؛ اتركها فارغة في النموذج.</p>
    @else
        <p>املأ التاريخ والوحدة والمهام المكتملة وساعات العمل. الجودة رقم من 0 إلى 100 كما تُخزّن في النظام، ودرجة الكفاءة من 1 إلى 100 اختيارية. يُربط السجل بالهدف المسجل للوحدة والتاريخ؛ عند تعدد الأهداف أدخل معرف هدف الوحدة في العمود الأخير. المستهدف والمؤشرات محسوبة تلقائياً؛ اترك أعمدتها فارغة في النموذج. بيانات الإدخال الإضافية تأتي بعد الأعمدة الظاهرة في الصفحة.</p>
        @if ($transferKind === 'gilbert')
            <p>الاستيراد يحفظ النتائج الفعلية التي تغذي المخطط. التصدير يطابق مقارنة اليوم والأمس للوحدة رقم 1 المعروضة حالياً، وترتيب محاوره: النتائج، الفعالية، الأهداف، الملاءمة، الموارد، الكفاءة.</p>
        @endif
    @endif
    <form action="{{ route($transferRoute.'.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="performance-import-file">ملف Excel</label>
            <input type="file" name="file" id="performance-import-file" class="form-control-file" accept=".xlsx" required>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="update_existing" id="update_existing" value="1" class="form-check-input" {{ old('update_existing') ? 'checked' : '' }}>
            <label for="update_existing" class="form-check-label">تحديث السجلات الموجودة لنفس التاريخ والوحدة/الهدف بقيم الملف</label>
        </div>
        <button type="submit" class="btn btn-success btn-sm">استيراد Excel</button>
        <a href="{{ route($transferRoute.'.template') }}" class="btn btn-outline-success btn-sm">تنزيل النموذج</a>
        <small class="d-block mt-2">يتم التحقق من جميع الصفوف قبل الحفظ. بدون تفعيل خيار التحديث، يُرفض أي سجل موجود مسبقاً.</small>
    </form>
</details>
