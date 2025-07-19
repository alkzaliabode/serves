<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SanitationFacilityTask;
use App\Models\TaskImageReport;
use Illuminate\Support\Facades\Log;

class RegenerateImageReports extends Command
{
    /**
     * اسم وتوصيف الأمر.
     *
     * @var string
     */
    protected $signature = 'regenerate-image-reports {--task_id= : معرف المهمة المحددة لإعادة إنشاء تقرير الصور الخاص بها}';

    /**
     * وصف الأمر.
     *
     * @var string
     */
    protected $description = 'إعادة إنشاء تقارير الصور لمهام المنشآت الصحية';

    /**
     * إنشاء حالة جديدة من الأمر.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * تنفيذ الأمر.
     */
    public function handle()
    {
        $taskId = $this->option('task_id');
        
        if ($taskId) {
            // إعادة إنشاء تقرير الصور لمهمة محددة فقط
            $task = SanitationFacilityTask::find($taskId);
            
            if (!$task) {
                $this->error("لم يتم العثور على المهمة بالمعرف {$taskId}");
                return 1;
            }
            
            $this->regenerateTaskImageReport($task);
            $this->info("تم إعادة إنشاء تقرير الصور للمهمة رقم {$taskId} بنجاح!");
        } else {
            // إعادة إنشاء تقارير الصور لجميع المهام
            $tasks = SanitationFacilityTask::all();
            
            $this->info("بدء إعادة إنشاء تقارير الصور لـ {$tasks->count()} مهمة...");
            
            $bar = $this->output->createProgressBar($tasks->count());
            $bar->start();
            
            foreach ($tasks as $task) {
                $this->regenerateTaskImageReport($task);
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine();
            $this->info("تم الانتهاء من إعادة إنشاء تقارير الصور بنجاح!");
        }
        
        return 0;
    }
    
    /**
     * إعادة إنشاء تقرير الصور لمهمة محددة
     *
     * @param SanitationFacilityTask $task المهمة
     */
    private function regenerateTaskImageReport(SanitationFacilityTask $task)
    {
        try {
            // حذف تقرير الصور القديم إن وجد
            if ($task->imageReport) {
                $task->imageReport->delete();
            }
            
            // جمع الصور قبل وبعد من المهمة
            $beforeImages = [];
            $afterImages = [];
            
            // جمع الصور من الخصائص المتاحة
            // (هذه الخصائص قد تختلف حسب هيكل قاعدة البيانات الخاصة بك)
            if (!empty($task->before_image)) {
                $beforeImages[] = $task->before_image;
            }
            if (!empty($task->before_image_2)) {
                $beforeImages[] = $task->before_image_2;
            }
            if (!empty($task->before_image_3)) {
                $beforeImages[] = $task->before_image_3;
            }
            
            if (!empty($task->after_image)) {
                $afterImages[] = $task->after_image;
            }
            if (!empty($task->after_image_2)) {
                $afterImages[] = $task->after_image_2;
            }
            if (!empty($task->after_image_3)) {
                $afterImages[] = $task->after_image_3;
            }
            
            // إنشاء تقرير الصور الجديد
            $imageReport = new TaskImageReport([
                'report_title' => "تقرير مهمة {$task->facility_name} - {$task->date}",
                'date' => $task->date,
                'unit_type' => 'sanitation_facility',
                'location' => $task->facility_name,
                'task_type' => $task->task_type,
                'task_id' => $task->id,
                'before_images' => $beforeImages,
                'after_images' => $afterImages,
                'status' => $task->status,
                'notes' => $task->notes ?? '',
            ]);
            
            $task->imageReport()->save($imageReport);
            
            Log::info("تم إعادة إنشاء تقرير الصور للمهمة", [
                'task_id' => $task->id, 
                'before_images' => count($beforeImages), 
                'after_images' => count($afterImages)
            ]);
        } catch (\Exception $e) {
            Log::error("فشل في إعادة إنشاء تقرير الصور للمهمة", [
                'task_id' => $task->id, 
                'error' => $e->getMessage()
            ]);
        }
    }
}
