<div class="card-body p-4">
    @csrf
    @isset($survey->id)
        @method('PUT')
    @endisset

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-secondary mb-4">
                <div class="card-header">
                    <h5 class="card-title text-secondary"><i class="fas fa-info-circle mr-2"></i> المعلومات العامة (اختياري)</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">الجنس:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="gender_male" value="male" {{ old('gender', $survey->gender) == 'male' ? 'checked' : '' }}>
                                <label class="form-check-label" for="gender_male">ذكر</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="gender_female" value="female" {{ old('gender', $survey->gender) == 'female' ? 'checked' : '' }}>
                                <label class="form-check-label" for="gender_female">أنثى</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">الفئة العمرية:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="age_group" id="age_under_18" value="under_18" {{ old('age_group', $survey->age_group) == 'under_18' ? 'checked' : '' }}>
                                <label class="form-check-label" for="age_under_18">أقل من 18</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="age_group" id="age_18_30" value="18_30" {{ old('age_group', $survey->age_group) == '18_30' ? 'checked' : '' }}>
                                <label class="form-check-label" for="age_18_30">18-30</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="age_group" id="age_30_45" value="30_45" {{ old('age_group', $survey->age_group) == '30_45' ? 'checked' : '' }}>
                                <label class="form-check-label" for="age_30_45">30-45</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="age_group" id="age_45_60" value="45_60" {{ old('age_group', $survey->age_group) == '45_60' ? 'checked' : '' }}>
                                <label class="form-check-label" for="age_45_60">45-60</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="age_group" id="age_over_60" value="over_60" {{ old('age_group', $survey->age_group) == 'over_60' ? 'checked' : '' }}>
                                <label class="form-check-label" for="age_over_60">أكثر من 60</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">عدد الزيارات:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="visit_count" id="visit_first_time" value="first_time" {{ old('visit_count', $survey->visit_count) == 'first_time' ? 'checked' : '' }}>
                                <label class="form-check-label" for="visit_first_time">أول مرة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="visit_count" id="visit_2_5_times" value="2_5_times" {{ old('visit_count', $survey->visit_count) == '2_5_times' ? 'checked' : '' }}>
                                <label class="form-check-label" for="visit_2_5_times">من 2 إلى 5 مرات</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="visit_count" id="visit_over_5_times" value="over_5_times" {{ old('visit_count', $survey->visit_count) == 'over_5_times' ? 'checked' : '' }}>
                                <label class="form-check-label" for="visit_over_5_times">أكثر من 5 مرات</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">مدة الإقامة:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="stay_duration" id="stay_less_1h" value="less_1h" {{ old('stay_duration', $survey->stay_duration) == 'less_1h' ? 'checked' : '' }}>
                                <label class="form-check-label" for="stay_less_1h">أقل من ساعة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="stay_duration" id="stay_2_3h" value="2_3h" {{ old('stay_duration', $survey->stay_duration) == '2_3h' ? 'checked' : '' }}>
                                <label class="form-check-label" for="stay_2_3h">من 2 إلى 3 ساعات</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="stay_duration" id="stay_4_6h" value="4_6h" {{ old('stay_duration', $survey->stay_duration) == '4_6h' ? 'checked' : '' }}>
                                <label class="form-check-label" for="stay_4_6h">من 4 إلى 6 ساعات</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="stay_duration" id="stay_over_6h" value="over_6h" {{ old('stay_duration', $survey->stay_duration) == 'over_6h' ? 'checked' : '' }}>
                                <label class="form-check-label" for="stay_over_6h">أكثر من 6 ساعات</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary mb-4">
                <div class="card-header">
                    <h5 class="card-title text-primary"><i class="fas fa-toilet mr-2"></i> تقييم نظافة المرافق العامة</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">نظافة دورات المياه <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="toilet_cleanliness" id="toilet_excellent" value="excellent" {{ old('toilet_cleanliness', $survey->toilet_cleanliness) == 'excellent' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="toilet_excellent">ممتازة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="toilet_cleanliness" id="toilet_very_good" value="very_good" {{ old('toilet_cleanliness', $survey->toilet_cleanliness) == 'very_good' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="toilet_very_good">جيدة جدًا</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="toilet_cleanliness" id="toilet_good" value="good" {{ old('toilet_cleanliness', $survey->toilet_cleanliness) == 'good' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="toilet_good">جيدة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="toilet_cleanliness" id="toilet_acceptable" value="acceptable" {{ old('toilet_cleanliness', $survey->toilet_cleanliness) == 'acceptable' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="toilet_acceptable">مقبولة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="toilet_cleanliness" id="toilet_poor" value="poor" {{ old('toilet_cleanliness', $survey->toilet_cleanliness) == 'poor' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="toilet_poor">سيئة</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">توفر مستلزمات النظافة <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hygiene_supplies" id="hygiene_always" value="always" {{ old('hygiene_supplies', $survey->hygiene_supplies) == 'always' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hygiene_always">دائمًا متوفرة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hygiene_supplies" id="hygiene_often" value="often" {{ old('hygiene_supplies', $survey->hygiene_supplies) == 'often' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hygiene_often">غالبًا متوفرة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hygiene_supplies" id="hygiene_rarely" value="rarely" {{ old('hygiene_supplies', $survey->hygiene_supplies) == 'rarely' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hygiene_rarely">نادرًا متوفرة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hygiene_supplies" id="hygiene_never" value="never" {{ old('hygiene_supplies', $survey->hygiene_supplies) == 'never' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hygiene_never">غير متوفرة إطلاقًا</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">نظافة الساحات والممرات <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="yard_cleanliness" id="yard_clean" value="clean" {{ old('yard_cleanliness', $survey->yard_cleanliness) == 'clean' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="yard_clean">نظيفة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="yard_cleanliness" id="yard_needs_improvement" value="needs_improvement" {{ old('yard_cleanliness', $survey->yard_cleanliness) == 'needs_improvement' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="yard_needs_improvement">تحتاج إلى تحسين</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="yard_cleanliness" id="yard_dirty" value="dirty" {{ old('yard_cleanliness', $survey->yard_cleanliness) == 'dirty' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="yard_dirty">غير نظيفة</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">فرق التنظيف <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cleaning_teams" id="teams_clearly" value="clearly" {{ old('cleaning_teams', $survey->cleaning_teams) == 'clearly' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="teams_clearly">نعم، بشكل واضح</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cleaning_teams" id="teams_sometimes" value="sometimes" {{ old('cleaning_teams', $survey->cleaning_teams) == 'sometimes' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="teams_sometimes">نعم، ولكن ليس دائمًا</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cleaning_teams" id="teams_rarely" value="rarely" {{ old('cleaning_teams', $survey->cleaning_teams) == 'rarely' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="teams_rarely">نادرًا ما ألاحظ ذلك</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cleaning_teams" id="teams_not_noticed" value="not_noticed" {{ old('cleaning_teams', $survey->cleaning_teams) == 'not_noticed' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="teams_not_noticed">لا، لم ألاحظ</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-warning mb-4">
                <div class="card-header">
                    <h5 class="card-title text-warning"><i class="fas fa-couch mr-2"></i> تقييم أماكن الاستراحة والقاعات</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">نظافة القاعات <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hall_cleanliness" id="hall_very_clean" value="very_clean" {{ old('hall_cleanliness', $survey->hall_cleanliness) == 'very_clean' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hall_very_clean">نظيفة جدًا</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hall_cleanliness" id="hall_clean" value="clean" {{ old('hall_cleanliness', $survey->hall_cleanliness) == 'clean' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hall_clean">نظيفة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hall_cleanliness" id="hall_needs_improvement" value="needs_improvement" {{ old('hall_cleanliness', $survey->hall_cleanliness) == 'needs_improvement' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hall_needs_improvement">تحتاج إلى تحسين</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hall_cleanliness" id="hall_dirty" value="dirty" {{ old('hall_cleanliness', $survey->hall_cleanliness) == 'dirty' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hall_dirty">غير نظيفة</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">حالة البطائن والفرش <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bedding_condition" id="bedding_excellent" value="excellent" {{ old('bedding_condition', $survey->bedding_condition) == 'excellent' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="bedding_excellent">نعم، بحالة ممتازة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bedding_condition" id="bedding_needs_care" value="needs_care" {{ old('bedding_condition', $survey->bedding_condition) == 'needs_care' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="bedding_needs_care">نعم، ولكن تحتاج إلى مزيد من العناية</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bedding_condition" id="bedding_not_clean" value="not_clean" {{ old('bedding_condition', $survey->bedding_condition) == 'not_clean' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="bedding_not_clean">ليست نظيفة بما يكفي</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bedding_condition" id="bedding_not_available" value="not_available" {{ old('bedding_condition', $survey->bedding_condition) == 'not_available' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="bedding_not_available">غير متوفرة بشكل كافي</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">التهوية <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ventilation" id="ventilation_excellent" value="excellent" {{ old('ventilation', $survey->ventilation) == 'excellent' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="ventilation_excellent">نعم، التهوية ممتازة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ventilation" id="ventilation_needs_improvement" value="needs_improvement" {{ old('ventilation', $survey->ventilation) == 'needs_improvement' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="ventilation_needs_improvement">متوفرة ولكن تحتاج إلى تحسين</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ventilation" id="ventilation_poor" value="poor" {{ old('ventilation', $survey->ventilation) == 'poor' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="ventilation_poor">التهوية ضعيفة وغير كافية</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">الإضاءة <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="lighting" id="lighting_excellent" value="excellent" {{ old('lighting', $survey->lighting) == 'excellent' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="lighting_excellent">ممتازة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="lighting" id="lighting_good" value="good" {{ old('lighting', $survey->lighting) == 'good' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="lighting_good">جيدة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="lighting" id="lighting_needs_improvement" value="needs_improvement" {{ old('lighting', $survey->lighting) == 'needs_improvement' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="lighting_needs_improvement">ضعيفة وتحتاج إلى تحسين</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info mb-4">
                <div class="card-header">
                    <h5 class="card-title text-info"><i class="fas fa-tint mr-2"></i> تقييم خدمات سقاية المياه</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">توزيع ترامز الماء <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_trams_distribution" id="water_dist_everywhere" value="everywhere" {{ old('water_trams_distribution', $survey->water_trams_distribution) == 'everywhere' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_dist_everywhere">نعم، في كل مكان</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_trams_distribution" id="water_dist_needs_more" value="needs_more" {{ old('water_trams_distribution', $survey->water_trams_distribution) == 'needs_more' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_dist_needs_more">نعم، ولكن تحتاج إلى زيادة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_trams_distribution" id="water_dist_not_enough" value="not_enough" {{ old('water_trams_distribution', $survey->water_trams_distribution) == 'not_enough' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_dist_not_enough">غير موزعة بشكل كافي</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">نظافة ترامز الماء <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_trams_cleanliness" id="water_clean_very_clean" value="very_clean" {{ old('water_trams_cleanliness', $survey->water_trams_cleanliness) == 'very_clean' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_clean_very_clean">نظيفة جدًا</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_trams_cleanliness" id="water_clean_clean" value="clean" {{ old('water_trams_cleanliness', $survey->water_trams_cleanliness) == 'clean' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_clean_clean">نظيفة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_trams_cleanliness" id="water_clean_needs_improvement" value="needs_improvement" {{ old('water_trams_cleanliness', $survey->water_trams_cleanliness) == 'needs_improvement' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_clean_needs_improvement">تحتاج إلى تحسين</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_trams_cleanliness" id="water_clean_dirty" value="dirty" {{ old('water_trams_cleanliness', $survey->water_trams_cleanliness) == 'dirty' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_clean_dirty">غير نظيفة</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">توفر مياه الشرب <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_availability" id="water_avail_always" value="always" {{ old('water_availability', $survey->water_availability) == 'always' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_avail_always">دائمًا متوفرة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_availability" id="water_avail_often" value="often" {{ old('water_availability', $survey->water_availability) == 'often' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_avail_often">غالبًا متوفرة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_availability" id="water_avail_rarely" value="rarely" {{ old('water_availability', $survey->water_availability) == 'rarely' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_avail_rarely">نادرًا ما تتوفر</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_availability" id="water_avail_not_enough" value="not_enough" {{ old('water_availability', $survey->water_availability) == 'not_enough' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_avail_not_enough">لا تتوفر بشكل كافي</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-success mb-4">
                <div class="card-header">
                    <h5 class="card-title text-success"><i class="fas fa-star mr-2"></i> التقييم العام والملاحظات</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">مستوى الرضا العام <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="overall_satisfaction" id="overall_very_satisfied" value="very_satisfied" {{ old('overall_satisfaction', $survey->overall_satisfaction) == 'very_satisfied' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="overall_very_satisfied">راض جدًا</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="overall_satisfaction" id="overall_satisfied" value="satisfied" {{ old('overall_satisfaction', $survey->overall_satisfaction) == 'satisfied' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="overall_satisfied">راض</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="overall_satisfaction" id="overall_acceptable" value="acceptable" {{ old('overall_satisfaction', $survey->overall_satisfaction) == 'acceptable' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="overall_acceptable">مقبول</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="overall_satisfaction" id="overall_dissatisfied" value="dissatisfied" {{ old('overall_satisfaction', $survey->overall_satisfaction) == 'dissatisfied' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="overall_dissatisfied">غير راض</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="problems_faced">المشاكل التي واجهتها:</label>
                        <textarea name="problems_faced" id="problems_faced" class="form-control" rows="3">{{ old('problems_faced', $survey->problems_faced) }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="suggestions">اقتراحات للتحسين:</label>
                        <textarea name="suggestions" id="suggestions" class="form-control" rows="3">{{ old('suggestions', $survey->suggestions) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">
        <i class="fas fa-save mr-1"></i> حفظ الاستبيان
    </button>
    <a href="{{ route('surveys.index') }}" class="btn btn-secondary mt-3 ml-2">
        <i class="fas fa-ban mr-1"></i> إلغاء
    </a>
</div>
