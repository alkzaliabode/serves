<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // استيراد Rule لاستخدامه في التحقق من uniqueness

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // يجب أن يكون المستخدم مصادق عليه (مسجل دخول) ليتمكن من تحديث ملفه الشخصي.
        // لذا نرجع true إذا كان المستخدم موجودًا ومصادقًا عليه.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'], // اسم المستخدم: مطلوب، نصي، بحد أقصى 255 حرف
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                // التحقق من أن البريد الإلكتروني فريد (غير مستخدم من قبل مستخدم آخر).
                // تجاهل البريد الإلكتروني الحالي للمستخدم عند التحقق من الفرادة.
                Rule::unique('users')->ignore($this->user()->id),
            ],
        ];
    }
}
