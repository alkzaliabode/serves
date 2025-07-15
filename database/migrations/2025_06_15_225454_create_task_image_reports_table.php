<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskImageReportsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_image_reports', function (Blueprint $table) {
            $table->id();

            // عنوان التقرير (اختياري)
            $table->string('report_title')->nullable();

            // رقم أو اسم المهمة (نصي لأن المهمة قد تكون وصفية)
            $table->string('task_id', 255)->nullable()->index();

            // نوع الوحدة (مثل: النظافة العامة، المنشآت الصحية)
            $table->string('unit_type', 255)->index();

            // الصور قبل وبعد (JSON)
            $table->json('before_images')->nullable();
            $table->json('after_images')->nullable();

            // عدد الصور (جديد)
            $table->integer('before_images_count')->default(0);
            $table->integer('after_images_count')->default(0);

            // تاريخ التنفيذ
            $table->date('date')->index();

            // موقع تنفيذ المهمة
            $table->string('location')->index();

            // نوع المهمة (مثلاً: جلي، صيانة...)
            $table->string('task_type')->index();

            // حالة المهمة (مكتملة، قيد التنفيذ...)
            $table->string('status')->nullable();

            // ملاحظات إضافية
            $table->text('notes')->nullable();

            // التواريخ التلقائية (created_at, updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_image_reports');
    }
}
