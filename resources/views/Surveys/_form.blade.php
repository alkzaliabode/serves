@php($surveyFields = \App\Support\SurveyFields::all())
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
                        <label class="col-md-2 col-form-label">{{ $surveyFields['gender']['label'] }}:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="gender_male" value="male" {{ old('gender', $survey->gender) == 'male' ? 'checked' : '' }}>
                                <label class="form-check-label" for="gender_male">{{ $surveyFields['gender']['options']['male'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="gender_female" value="female" {{ old('gender', $survey->gender) == 'female' ? 'checked' : '' }}>
                                <label class="form-check-label" for="gender_female">{{ $surveyFields['gender']['options']['female'] }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">{{ $surveyFields['age_group']['label'] }}:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="age_group" id="age_under_18" value="under_18" {{ old('age_group', $survey->age_group) == 'under_18' ? 'checked' : '' }}>
                                <label class="form-check-label" for="age_under_18">{{ $surveyFields['age_group']['options']['under_18'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="age_group" id="age_18_30" value="18_30" {{ old('age_group', $survey->age_group) == '18_30' ? 'checked' : '' }}>
                                <label class="form-check-label" for="age_18_30">{{ $surveyFields['age_group']['options']['18_30'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="age_group" id="age_30_45" value="30_45" {{ old('age_group', $survey->age_group) == '30_45' ? 'checked' : '' }}>
                                <label class="form-check-label" for="age_30_45">{{ $surveyFields['age_group']['options']['30_45'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="age_group" id="age_45_60" value="45_60" {{ old('age_group', $survey->age_group) == '45_60' ? 'checked' : '' }}>
                                <label class="form-check-label" for="age_45_60">{{ $surveyFields['age_group']['options']['45_60'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="age_group" id="age_over_60" value="over_60" {{ old('age_group', $survey->age_group) == 'over_60' ? 'checked' : '' }}>
                                <label class="form-check-label" for="age_over_60">{{ $surveyFields['age_group']['options']['over_60'] }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">{{ $surveyFields['visit_count']['label'] }}:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="visit_count" id="visit_first_time" value="first_time" {{ old('visit_count', $survey->visit_count) == 'first_time' ? 'checked' : '' }}>
                                <label class="form-check-label" for="visit_first_time">{{ $surveyFields['visit_count']['options']['first_time'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="visit_count" id="visit_2_5_times" value="2_5_times" {{ old('visit_count', $survey->visit_count) == '2_5_times' ? 'checked' : '' }}>
                                <label class="form-check-label" for="visit_2_5_times">{{ $surveyFields['visit_count']['options']['2_5_times'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="visit_count" id="visit_over_5_times" value="over_5_times" {{ old('visit_count', $survey->visit_count) == 'over_5_times' ? 'checked' : '' }}>
                                <label class="form-check-label" for="visit_over_5_times">{{ $surveyFields['visit_count']['options']['over_5_times'] }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">{{ $surveyFields['stay_duration']['label'] }}:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="stay_duration" id="stay_less_1h" value="less_1h" {{ old('stay_duration', $survey->stay_duration) == 'less_1h' ? 'checked' : '' }}>
                                <label class="form-check-label" for="stay_less_1h">{{ $surveyFields['stay_duration']['options']['less_1h'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="stay_duration" id="stay_2_3h" value="2_3h" {{ old('stay_duration', $survey->stay_duration) == '2_3h' ? 'checked' : '' }}>
                                <label class="form-check-label" for="stay_2_3h">{{ $surveyFields['stay_duration']['options']['2_3h'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="stay_duration" id="stay_4_6h" value="4_6h" {{ old('stay_duration', $survey->stay_duration) == '4_6h' ? 'checked' : '' }}>
                                <label class="form-check-label" for="stay_4_6h">{{ $surveyFields['stay_duration']['options']['4_6h'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="stay_duration" id="stay_over_6h" value="over_6h" {{ old('stay_duration', $survey->stay_duration) == 'over_6h' ? 'checked' : '' }}>
                                <label class="form-check-label" for="stay_over_6h">{{ $surveyFields['stay_duration']['options']['over_6h'] }}</label>
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
                        <label class="col-md-2 col-form-label">{{ $surveyFields['toilet_cleanliness']['label'] }} <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="toilet_cleanliness" id="toilet_excellent" value="excellent" {{ old('toilet_cleanliness', $survey->toilet_cleanliness) == 'excellent' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="toilet_excellent">{{ $surveyFields['toilet_cleanliness']['options']['excellent'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="toilet_cleanliness" id="toilet_very_good" value="very_good" {{ old('toilet_cleanliness', $survey->toilet_cleanliness) == 'very_good' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="toilet_very_good">{{ $surveyFields['toilet_cleanliness']['options']['very_good'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="toilet_cleanliness" id="toilet_good" value="good" {{ old('toilet_cleanliness', $survey->toilet_cleanliness) == 'good' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="toilet_good">{{ $surveyFields['toilet_cleanliness']['options']['good'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="toilet_cleanliness" id="toilet_acceptable" value="acceptable" {{ old('toilet_cleanliness', $survey->toilet_cleanliness) == 'acceptable' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="toilet_acceptable">{{ $surveyFields['toilet_cleanliness']['options']['acceptable'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="toilet_cleanliness" id="toilet_poor" value="poor" {{ old('toilet_cleanliness', $survey->toilet_cleanliness) == 'poor' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="toilet_poor">{{ $surveyFields['toilet_cleanliness']['options']['poor'] }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">{{ $surveyFields['hygiene_supplies']['label'] }} <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hygiene_supplies" id="hygiene_always" value="always" {{ old('hygiene_supplies', $survey->hygiene_supplies) == 'always' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hygiene_always">{{ $surveyFields['hygiene_supplies']['options']['always'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hygiene_supplies" id="hygiene_often" value="often" {{ old('hygiene_supplies', $survey->hygiene_supplies) == 'often' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hygiene_often">{{ $surveyFields['hygiene_supplies']['options']['often'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hygiene_supplies" id="hygiene_rarely" value="rarely" {{ old('hygiene_supplies', $survey->hygiene_supplies) == 'rarely' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hygiene_rarely">{{ $surveyFields['hygiene_supplies']['options']['rarely'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hygiene_supplies" id="hygiene_never" value="never" {{ old('hygiene_supplies', $survey->hygiene_supplies) == 'never' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hygiene_never">{{ $surveyFields['hygiene_supplies']['options']['never'] }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">{{ $surveyFields['yard_cleanliness']['label'] }} <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="yard_cleanliness" id="yard_clean" value="clean" {{ old('yard_cleanliness', $survey->yard_cleanliness) == 'clean' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="yard_clean">{{ $surveyFields['yard_cleanliness']['options']['clean'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="yard_cleanliness" id="yard_needs_improvement" value="needs_improvement" {{ old('yard_cleanliness', $survey->yard_cleanliness) == 'needs_improvement' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="yard_needs_improvement">{{ $surveyFields['yard_cleanliness']['options']['needs_improvement'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="yard_cleanliness" id="yard_dirty" value="dirty" {{ old('yard_cleanliness', $survey->yard_cleanliness) == 'dirty' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="yard_dirty">{{ $surveyFields['yard_cleanliness']['options']['dirty'] }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">{{ $surveyFields['cleaning_teams']['label'] }} <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cleaning_teams" id="teams_clearly" value="clearly" {{ old('cleaning_teams', $survey->cleaning_teams) == 'clearly' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="teams_clearly">{{ $surveyFields['cleaning_teams']['options']['clearly'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cleaning_teams" id="teams_sometimes" value="sometimes" {{ old('cleaning_teams', $survey->cleaning_teams) == 'sometimes' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="teams_sometimes">{{ $surveyFields['cleaning_teams']['options']['sometimes'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cleaning_teams" id="teams_rarely" value="rarely" {{ old('cleaning_teams', $survey->cleaning_teams) == 'rarely' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="teams_rarely">{{ $surveyFields['cleaning_teams']['options']['rarely'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cleaning_teams" id="teams_not_noticed" value="not_noticed" {{ old('cleaning_teams', $survey->cleaning_teams) == 'not_noticed' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="teams_not_noticed">{{ $surveyFields['cleaning_teams']['options']['not_noticed'] }}</label>
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
                        <label class="col-md-2 col-form-label">{{ $surveyFields['hall_cleanliness']['label'] }} <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hall_cleanliness" id="hall_very_clean" value="very_clean" {{ old('hall_cleanliness', $survey->hall_cleanliness) == 'very_clean' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hall_very_clean">{{ $surveyFields['hall_cleanliness']['options']['very_clean'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hall_cleanliness" id="hall_clean" value="clean" {{ old('hall_cleanliness', $survey->hall_cleanliness) == 'clean' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hall_clean">{{ $surveyFields['hall_cleanliness']['options']['clean'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hall_cleanliness" id="hall_needs_improvement" value="needs_improvement" {{ old('hall_cleanliness', $survey->hall_cleanliness) == 'needs_improvement' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hall_needs_improvement">{{ $surveyFields['hall_cleanliness']['options']['needs_improvement'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hall_cleanliness" id="hall_dirty" value="dirty" {{ old('hall_cleanliness', $survey->hall_cleanliness) == 'dirty' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="hall_dirty">{{ $surveyFields['hall_cleanliness']['options']['dirty'] }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">{{ $surveyFields['bedding_condition']['label'] }} <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bedding_condition" id="bedding_excellent" value="excellent" {{ old('bedding_condition', $survey->bedding_condition) == 'excellent' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="bedding_excellent">{{ $surveyFields['bedding_condition']['options']['excellent'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bedding_condition" id="bedding_needs_care" value="needs_care" {{ old('bedding_condition', $survey->bedding_condition) == 'needs_care' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="bedding_needs_care">{{ $surveyFields['bedding_condition']['options']['needs_care'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bedding_condition" id="bedding_not_clean" value="not_clean" {{ old('bedding_condition', $survey->bedding_condition) == 'not_clean' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="bedding_not_clean">{{ $surveyFields['bedding_condition']['options']['not_clean'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bedding_condition" id="bedding_not_available" value="not_available" {{ old('bedding_condition', $survey->bedding_condition) == 'not_available' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="bedding_not_available">{{ $surveyFields['bedding_condition']['options']['not_available'] }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">{{ $surveyFields['ventilation']['label'] }} <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ventilation" id="ventilation_excellent" value="excellent" {{ old('ventilation', $survey->ventilation) == 'excellent' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="ventilation_excellent">{{ $surveyFields['ventilation']['options']['excellent'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ventilation" id="ventilation_needs_improvement" value="needs_improvement" {{ old('ventilation', $survey->ventilation) == 'needs_improvement' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="ventilation_needs_improvement">{{ $surveyFields['ventilation']['options']['needs_improvement'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ventilation" id="ventilation_poor" value="poor" {{ old('ventilation', $survey->ventilation) == 'poor' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="ventilation_poor">{{ $surveyFields['ventilation']['options']['poor'] }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">{{ $surveyFields['lighting']['label'] }} <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="lighting" id="lighting_excellent" value="excellent" {{ old('lighting', $survey->lighting) == 'excellent' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="lighting_excellent">{{ $surveyFields['lighting']['options']['excellent'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="lighting" id="lighting_good" value="good" {{ old('lighting', $survey->lighting) == 'good' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="lighting_good">{{ $surveyFields['lighting']['options']['good'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="lighting" id="lighting_needs_improvement" value="needs_improvement" {{ old('lighting', $survey->lighting) == 'needs_improvement' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="lighting_needs_improvement">{{ $surveyFields['lighting']['options']['needs_improvement'] }}</label>
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
                        <label class="col-md-2 col-form-label">{{ $surveyFields['water_trams_distribution']['label'] }} <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_trams_distribution" id="water_dist_everywhere" value="everywhere" {{ old('water_trams_distribution', $survey->water_trams_distribution) == 'everywhere' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_dist_everywhere">{{ $surveyFields['water_trams_distribution']['options']['everywhere'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_trams_distribution" id="water_dist_needs_more" value="needs_more" {{ old('water_trams_distribution', $survey->water_trams_distribution) == 'needs_more' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_dist_needs_more">{{ $surveyFields['water_trams_distribution']['options']['needs_more'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_trams_distribution" id="water_dist_not_enough" value="not_enough" {{ old('water_trams_distribution', $survey->water_trams_distribution) == 'not_enough' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_dist_not_enough">{{ $surveyFields['water_trams_distribution']['options']['not_enough'] }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">{{ $surveyFields['water_trams_cleanliness']['label'] }} <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_trams_cleanliness" id="water_clean_very_clean" value="very_clean" {{ old('water_trams_cleanliness', $survey->water_trams_cleanliness) == 'very_clean' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_clean_very_clean">{{ $surveyFields['water_trams_cleanliness']['options']['very_clean'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_trams_cleanliness" id="water_clean_clean" value="clean" {{ old('water_trams_cleanliness', $survey->water_trams_cleanliness) == 'clean' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_clean_clean">{{ $surveyFields['water_trams_cleanliness']['options']['clean'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_trams_cleanliness" id="water_clean_needs_improvement" value="needs_improvement" {{ old('water_trams_cleanliness', $survey->water_trams_cleanliness) == 'needs_improvement' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_clean_needs_improvement">{{ $surveyFields['water_trams_cleanliness']['options']['needs_improvement'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_trams_cleanliness" id="water_clean_dirty" value="dirty" {{ old('water_trams_cleanliness', $survey->water_trams_cleanliness) == 'dirty' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_clean_dirty">{{ $surveyFields['water_trams_cleanliness']['options']['dirty'] }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">{{ $surveyFields['water_availability']['label'] }} <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_availability" id="water_avail_always" value="always" {{ old('water_availability', $survey->water_availability) == 'always' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_avail_always">{{ $surveyFields['water_availability']['options']['always'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_availability" id="water_avail_often" value="often" {{ old('water_availability', $survey->water_availability) == 'often' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_avail_often">{{ $surveyFields['water_availability']['options']['often'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_availability" id="water_avail_rarely" value="rarely" {{ old('water_availability', $survey->water_availability) == 'rarely' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_avail_rarely">{{ $surveyFields['water_availability']['options']['rarely'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="water_availability" id="water_avail_not_enough" value="not_enough" {{ old('water_availability', $survey->water_availability) == 'not_enough' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="water_avail_not_enough">{{ $surveyFields['water_availability']['options']['not_enough'] }}</label>
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
                        <label class="col-md-2 col-form-label">{{ $surveyFields['overall_satisfaction']['label'] }} <span class="text-danger">*</span>:</label>
                        <div class="col-md-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="overall_satisfaction" id="overall_very_satisfied" value="very_satisfied" {{ old('overall_satisfaction', $survey->overall_satisfaction) == 'very_satisfied' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="overall_very_satisfied">{{ $surveyFields['overall_satisfaction']['options']['very_satisfied'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="overall_satisfaction" id="overall_satisfied" value="satisfied" {{ old('overall_satisfaction', $survey->overall_satisfaction) == 'satisfied' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="overall_satisfied">{{ $surveyFields['overall_satisfaction']['options']['satisfied'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="overall_satisfaction" id="overall_acceptable" value="acceptable" {{ old('overall_satisfaction', $survey->overall_satisfaction) == 'acceptable' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="overall_acceptable">{{ $surveyFields['overall_satisfaction']['options']['acceptable'] }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="overall_satisfaction" id="overall_dissatisfied" value="dissatisfied" {{ old('overall_satisfaction', $survey->overall_satisfaction) == 'dissatisfied' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="overall_dissatisfied">{{ $surveyFields['overall_satisfaction']['options']['dissatisfied'] }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="problems_faced">{{ $surveyFields['problems_faced']['label'] }}:</label>
                        <textarea name="problems_faced" id="problems_faced" class="form-control" rows="3">{{ old('problems_faced', $survey->problems_faced) }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="suggestions">{{ $surveyFields['suggestions']['label'] }}:</label>
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
