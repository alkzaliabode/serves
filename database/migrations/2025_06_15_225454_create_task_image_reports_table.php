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

            $table->string('report_title')->nullable(); // تم السماح بالقيم الفارغة

            // ✅ تم تغيير نوع العمود 'task_id' من unsignedBigInteger إلى string بطول 255
            // هذا يسمح بتخزين قيم نصية مثل 'جلي مرمر وجه الجامع'
            $table->string('task_id', 255)->nullable()->index(); // تم التعديل هنا، وأيضاً جعله nullable

            // ✅ تم تغيير نوع العمود 'unit_type' من enum إلى string بطول 255
            // هذا يسمح بتخزين قيم نصية مثل 'النظافة العامة' و 'المنشآت الصحية'
            $table->string('unit_type', 255)->index(); 

            // الحقول التي تخزن الصور بصيغة JSON
            $table->json('before_images')->nullable();
            $table->json('after_images')->nullable();
            
            // تاريخ المهمة
            $table->date('date')->index();

            // الموقع (مكان المهمة)
            $table->string('location')->index();

            // نوع المهمة: إدامة أو صيانة
            $table->string('task_type')->index();

            // حالة المهمة (مثلاً: مكتملة، معلقة...)
            $table->string('status')->nullable();

            // ملاحظات إضافية
            $table->text('notes')->nullable();

            $table->timestamps();

            // يمكنك إضافة مفتاح أجنبي إذا كانت هناك جداول GeneralCleaningTask و SanitationFacilityTask
            // ولكن هنا لأنه قد يكون نوعين مختلفين لنفس المفتاح، نحتفظ به كـ unsignedBigInteger فقط.
            // إذا تريد إضافة قيود مفتاح أجنبي يمكنك تعديلها حسب جداولك.
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
