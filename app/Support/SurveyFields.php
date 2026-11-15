<?php

namespace App\Support;

class SurveyFields
{
    public static function all(): array
    {
        return [
            'gender' => ['label' => 'الجنس', 'options' => ['male' => 'ذكر', 'female' => 'أنثى'], 'required' => false],
            'age_group' => ['label' => 'الفئة العمرية', 'options' => ['under_18' => 'أقل من 18', '18_30' => '18-30', '30_45' => '30-45', '45_60' => '45-60', 'over_60' => 'أكثر من 60'], 'required' => false],
            'visit_count' => ['label' => 'عدد الزيارات', 'options' => ['first_time' => 'أول مرة', '2_5_times' => 'من 2 إلى 5 مرات', 'over_5_times' => 'أكثر من 5 مرات'], 'required' => false],
            'stay_duration' => ['label' => 'مدة الإقامة', 'options' => ['less_1h' => 'أقل من ساعة', '2_3h' => 'من 2 إلى 3 ساعات', '4_6h' => 'من 4 إلى 6 ساعات', 'over_6h' => 'أكثر من 6 ساعات'], 'required' => false],
            'toilet_cleanliness' => ['label' => 'نظافة دورات المياه', 'options' => ['excellent' => 'ممتازة', 'very_good' => 'جيدة جدًا', 'good' => 'جيدة', 'acceptable' => 'مقبولة', 'poor' => 'سيئة'], 'required' => true],
            'hygiene_supplies' => ['label' => 'توفر مستلزمات النظافة', 'options' => ['always' => 'دائمًا متوفرة', 'often' => 'غالبًا متوفرة', 'rarely' => 'نادرًا متوفرة', 'never' => 'غير متوفرة إطلاقًا'], 'required' => true],
            'yard_cleanliness' => ['label' => 'نظافة الساحات والممرات', 'options' => ['clean' => 'نظيفة', 'needs_improvement' => 'تحتاج إلى تحسين', 'dirty' => 'غير نظيفة'], 'required' => true],
            'cleaning_teams' => ['label' => 'فرق التنظيف', 'options' => ['clearly' => 'نعم، بشكل واضح', 'sometimes' => 'نعم، ولكن ليس دائمًا', 'rarely' => 'نادرًا ما ألاحظ ذلك', 'not_noticed' => 'لا، لم ألاحظ'], 'required' => true],
            'hall_cleanliness' => ['label' => 'نظافة القاعات', 'options' => ['very_clean' => 'نظيفة جدًا', 'clean' => 'نظيفة', 'needs_improvement' => 'تحتاج إلى تحسين', 'dirty' => 'غير نظيفة'], 'required' => true],
            'bedding_condition' => ['label' => 'حالة البطائن والفرش', 'options' => ['excellent' => 'نعم، بحالة ممتازة', 'needs_care' => 'نعم، ولكن تحتاج إلى مزيد من العناية', 'not_clean' => 'ليست نظيفة بما يكفي', 'not_available' => 'غير متوفرة بشكل كافي'], 'required' => true],
            'ventilation' => ['label' => 'التهوية', 'options' => ['excellent' => 'نعم، التهوية ممتازة', 'needs_improvement' => 'متوفرة ولكن تحتاج إلى تحسين', 'poor' => 'التهوية ضعيفة وغير كافية'], 'required' => true],
            'lighting' => ['label' => 'الإضاءة', 'options' => ['excellent' => 'ممتازة', 'good' => 'جيدة', 'needs_improvement' => 'ضعيفة وتحتاج إلى تحسين'], 'required' => true],
            'water_trams_distribution' => ['label' => 'توزيع ترامز الماء', 'options' => ['everywhere' => 'نعم، في كل مكان', 'needs_more' => 'نعم، ولكن تحتاج إلى زيادة', 'not_enough' => 'غير موزعة بشكل كافي'], 'required' => true],
            'water_trams_cleanliness' => ['label' => 'نظافة ترامز الماء', 'options' => ['very_clean' => 'نظيفة جدًا', 'clean' => 'نظيفة', 'needs_improvement' => 'تحتاج إلى تحسين', 'dirty' => 'غير نظيفة'], 'required' => true],
            'water_availability' => ['label' => 'توفر مياه الشرب', 'options' => ['always' => 'دائمًا متوفرة', 'often' => 'غالبًا متوفرة', 'rarely' => 'نادرًا ما تتوفر', 'not_enough' => 'لا تتوفر بشكل كافي'], 'required' => true],
            'overall_satisfaction' => ['label' => 'مستوى الرضا العام', 'options' => ['very_satisfied' => 'راض جدًا', 'satisfied' => 'راض', 'acceptable' => 'مقبول', 'dissatisfied' => 'غير راض'], 'required' => true],
            'problems_faced' => ['label' => 'المشاكل التي واجهتها', 'options' => [], 'required' => false],
            'suggestions' => ['label' => 'اقتراحات للتحسين', 'options' => [], 'required' => false],
        ];
    }
}
