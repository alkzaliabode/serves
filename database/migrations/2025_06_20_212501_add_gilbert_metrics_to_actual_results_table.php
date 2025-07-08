<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; 

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('actual_results', function (Blueprint $table) {
            // إضافة حقول مثلث الأداء Gilbert
            $table->decimal('working_hours', 8, 2)->nullable()->comment('ساعات العمل');
            $table->decimal('effectiveness', 8, 2)->nullable()->comment('الفعالية %');
            $table->decimal('efficiency', 8, 2)->nullable()->comment('الكفاءة %');
            $table->decimal('relevance', 8, 2)->nullable()->comment('الملاءمة %');
            $table->decimal('overall_performance_score', 8, 2)->nullable()->comment('نقاط الأداء الإجمالية %');
            $table->text('notes')->nullable()->comment('ملاحظات');
            
            // إضافة فهارس للبحث السريع
            $table->index(['effectiveness']);
            $table->index(['efficiency']);
            $table->index(['relevance']);
            $table->index(['overall_performance_score']);
            $table->index(['unit_id', 'date']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // تعطيل التحقق من المفاتيح الأجنبية مؤقتًا للسماح بإسقاط الفهرس
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::table('actual_results', function (Blueprint $table) {
            // حذف الفهارس أولاً بعد التحقق من وجودها
            // يجب تحديد اسم الفهرس كما ينشئه Laravel بشكل افتراضي (اسم_الجدول_اسم_العمود_index)
            // أو (اسم_الجدول_اسم_العمود1_اسم_العمود2_index) للفهارس المركبة
            if (Schema::hasIndex('actual_results', 'actual_results_effectiveness_index')) {
                $table->dropIndex(['effectiveness']);
            }
            if (Schema::hasIndex('actual_results', 'actual_results_efficiency_index')) {
                $table->dropIndex(['efficiency']);
            }
            if (Schema::hasIndex('actual_results', 'actual_results_relevance_index')) {
                $table->dropIndex(['relevance']);
            }
            if (Schema::hasIndex('actual_results', 'actual_results_overall_performance_score_index')) {
                $table->dropIndex(['overall_performance_score']);
            }
            if (Schema::hasIndex('actual_results', 'actual_results_unit_id_date_index')) {
                $table->dropIndex(['unit_id', 'date']);
            }
            
            // حذف الحقول
            $table->dropColumn([
                'working_hours',
                'effectiveness',
                'efficiency',
                'relevance',
                'overall_performance_score',
                'notes',
            ]);
        });

        // إعادة تفعيل التحقق من المفاتيح الأجنبية
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};