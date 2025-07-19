<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PhotoReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // يمكن استخدام هذه الدالة للتحقق من صلاحيات المستخدم
        // في هذه الحالة، نفترض أن المستخدم المصادق عليه له صلاحية تنفيذ هذا الطلب
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'report_title' => 'required|string|max:255',
            'date' => 'required|date',
            'unit_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'task_id' => 'nullable|string|max:255',
            'task_type' => 'nullable|string|max:255',
            'status' => ['required', 'string', Rule::in(['مكتمل', 'قيد التنفيذ', 'ملغى'])],
            'notes' => 'nullable|string',
            // تحقق من صور جديدة
            'new_before_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:15360',
            'new_after_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:15360',
            // تحقق من الصور المحذوفة كبيانات JSON
            'deleted_before_images' => 'nullable|json',
            'deleted_after_images' => 'nullable|json',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'report_title.required' => 'يجب إدخال عنوان للتقرير.',
            'date.required' => 'يجب إدخال تاريخ التقرير.',
            'date.date' => 'يجب أن يكون التاريخ صالحاً.',
            'unit_type.required' => 'يجب تحديد نوع الوحدة.',
            'location.required' => 'يجب إدخال موقع التقرير.',
            'status.required' => 'يجب تحديد حالة التقرير.',
            'status.in' => 'الحالة المحددة غير صالحة. يجب أن تكون إحدى القيم المتاحة: مكتمل، قيد التنفيذ، ملغى.',
            'new_before_images.*.image' => 'يجب أن يكون الملف صورة.',
            'new_before_images.*.mimes' => 'يجب أن تكون الصورة من نوع: jpeg، png، jpg، gif، svg.',
            'new_before_images.*.max' => 'حجم الصورة يجب ألا يتجاوز 15 ميجابايت.',
            'new_after_images.*.image' => 'يجب أن يكون الملف صورة.',
            'new_after_images.*.mimes' => 'يجب أن تكون الصورة من نوع: jpeg، png، jpg، gif، svg.',
            'new_after_images.*.max' => 'حجم الصورة يجب ألا يتجاوز 15 ميجابايت.',
            'deleted_before_images.json' => 'يجب أن تكون بيانات الصور المحذوفة بتنسيق JSON صالح.',
            'deleted_after_images.json' => 'يجب أن تكون بيانات الصور المحذوفة بتنسيق JSON صالح.',
        ];
    }
}
