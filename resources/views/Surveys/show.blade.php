@extends('layouts.adminlte')

@section('title', 'تفاصيل الاستبيان')

@section('page_title', 'تفاصيل الاستبيان')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('surveys.index') }}">استبيانات رضا الزائرين</a></li>
    <li class="breadcrumb-item active">تفاصيل</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-primary shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-primary">
                    <i class="fas fa-info-circle mr-2"></i> تفاصيل الاستبيان رقم: {{ $survey->survey_number }}
                </h3>
                <div class="card-tools">
                    <a href="{{ route('surveys.edit', $survey->id) }}" class="btn btn-primary btn-sm mr-2">
                        <i class="fas fa-edit mr-1"></i> تعديل
                    </a>
                    <a href="{{ route('surveys.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-alt-circle-right mr-1"></i> العودة للقائمة
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>تاريخ الإدخال:</strong> {{ $survey->created_at->format('Y-m-d H:i:s') }}</p>
                        <p><strong>رقم الاستبيان:</strong> {{ $survey->survey_number }}</p>
                        <p><strong>الجنس:</strong> {{ match($survey->gender) { 'male' => 'ذكر', 'female' => 'أنثى', default => 'غير محدد' } }}</p>
                        <p><strong>الفئة العمرية:</strong> {{ match($survey->age_group) { 'under_18' => 'أقل من 18', '18_30' => '18-30', '30_45' => '30-45', '45_60' => '45-60', 'over_60' => 'أكثر من 60', default => 'غير محدد' } }}</p>
                        <p><strong>عدد الزيارات:</strong> {{ match($survey->visit_count) { 'first_time' => 'أول مرة', '2_5_times' => 'من 2 إلى 5 مرات', 'over_5_times' => 'أكثر من 5 مرات', default => 'غير محدد' } }}</p>
                        <p><strong>مدة الإقامة:</strong> {{ match($survey->stay_duration) { 'less_1h' => 'أقل من ساعة', '2_3h' => 'من 2 إلى 3 ساعات', '4_6h' => 'من 4 إلى 6 ساعات', 'over_6h' => 'أكثر من 6 ساعات', default => 'غير محدد' } }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>نظافة دورات المياه:</strong> {{ match($survey->toilet_cleanliness) { 'excellent' => 'ممتازة', 'very_good' => 'جيدة جدًا', 'good' => 'جيدة', 'acceptable' => 'مقبولة', 'poor' => 'سيئة', default => 'غير محدد' } }}</p>
                        <p><strong>توفر مستلزمات النظافة:</strong> {{ match($survey->hygiene_supplies) { 'always' => 'دائمًا متوفرة', 'often' => 'غالبًا متوفرة', 'rarely' => 'نادرًا متوفرة', 'never' => 'غير متوفرة إطلاقًا', default => 'غير محدد' } }}</p>
                        <p><strong>نظافة الساحات والممرات:</strong> {{ match($survey->yard_cleanliness) { 'clean' => 'نظيفة', 'needs_improvement' => 'تحتاج إلى تحسين', 'dirty' => 'غير نظيفة', default => 'غير محدد' } }}</p>
                        <p><strong>فرق التنظيف:</strong> {{ match($survey->cleaning_teams) { 'clearly' => 'نعم، بشكل واضح', 'sometimes' => 'نعم، ولكن ليس دائمًا', 'rarely' => 'نادرًا ما ألاحظ ذلك', 'not_noticed' => 'لا، لم ألاحظ', default => 'غير محدد' } }}</p>
                    </div>
                </div>

                <hr>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <p><strong>نظافة القاعات:</strong> {{ match($survey->hall_cleanliness) { 'very_clean' => 'نظيفة جدًا', 'clean' => 'نظيفة', 'needs_improvement' => 'تحتاج إلى تحسين', 'dirty' => 'غير نظيفة', default => 'غير محدد' } }}</p>
                        <p><strong>حالة البطائن والفرش:</strong> {{ match($survey->bedding_condition) { 'excellent' => 'نعم، بحالة ممتازة', 'needs_care' => 'نعم، ولكن تحتاج إلى مزيد من العناية', 'not_clean' => 'ليست نظيفة بما يكفي', 'not_available' => 'غير متوفرة بشكل كافي', default => 'غير محدد' } }}</p>
                        <p><strong>التهوية:</strong> {{ match($survey->ventilation) { 'excellent' => 'نعم، التهوية ممتازة', 'needs_improvement' => 'متوفرة ولكن تحتاج إلى تحسين', 'poor' => 'التهوية ضعيفة وغير كافية', default => 'غير محدد' } }}</p>
                        <p><strong>الإضاءة:</strong> {{ match($survey->lighting) { 'excellent' => 'ممتازة', 'good' => 'جيدة', 'needs_improvement' => 'ضعيفة وتحتاج إلى تحسين', default => 'غير محدد' } }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>توزيع ترامز الماء:</strong> {{ match($survey->water_trams_distribution) { 'everywhere' => 'نعم، في كل مكان', 'needs_more' => 'نعم، ولكن تحتاج إلى زيادة', 'not_enough' => 'غير موزعة بشكل كافي', default => 'غير محدد' } }}</p>
                        <p><strong>نظافة ترامز الماء:</strong> {{ match($survey->water_trams_cleanliness) { 'very_clean' => 'نظيفة جدًا', 'clean' => 'نظيفة', 'needs_improvement' => 'تحتاج إلى تحسين', 'dirty' => 'غير نظيفة', default => 'غير محدد' } }}</p>
                        <p><strong>توفر مياه الشرب:</strong> {{ match($survey->water_availability) { 'always' => 'دائمًا متوفرة', 'often' => 'غالبًا متوفرة', 'rarely' => 'نادرًا ما تتوفر', 'not_enough' => 'لا تتوفر بشكل كافي', default => 'غير محدد' } }}</p>
                        <p><strong>مستوى الرضا العام:</strong> {{ match($survey->overall_satisfaction) { 'very_satisfied' => 'راض جدًا', 'satisfied' => 'راض', 'acceptable' => 'مقبول', 'dissatisfied' => 'غير راض', default => 'غير محدد' } }}</p>
                    </div>
                </div>

                <hr>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <p><strong>المشاكل التي واجهتها:</strong> {{ $survey->problems_faced ?? 'لا توجد مشاكل مذكورة.' }}</p>
                        <p><strong>اقتراحات للتحسين:</strong> {{ $survey->suggestions ?? 'لا توجد اقتراحات مذكورة.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
