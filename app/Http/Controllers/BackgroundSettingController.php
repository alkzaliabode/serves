<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting; // تأكد من استيراد نموذج Setting الذي أنشأته
use Illuminate\Support\Facades\Storage; // لاستخدام تخزين الملفات

class BackgroundSettingController extends Controller
{
    /**
     * عرض نموذج تغيير صورة الخلفية.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // جلب مسار الصورة الحالي من الإعدادات
        $currentBackgroundUrl = Setting::get('background_image_url', asset('images/dashboard-background.jpg'));

        // تمرير مسار الصورة الحالي إلى واجهة العرض
        return view('admin.background-settings', compact('currentBackgroundUrl'));
    }

    /**
     * تحديث صورة الخلفية.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // التحقق من صحة الطلب: يجب أن يكون هناك ملف صورة وأن يكون من نوع صالح
        $request->validate([
            'background_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB Max
        ], [
            'background_image.required' => 'الرجاء اختيار صورة خلفية.',
            'background_image.image' => 'الملف الذي تم تحميله ليس صورة.',
            'background_image.mimes' => 'يجب أن تكون الصورة من نوع JPEG, PNG, JPG, GIF, أو SVG.',
            'background_image.max' => 'يجب ألا يتجاوز حجم الصورة 2 ميغابايت.',
        ]);

        $currentBackgroundUrl = Setting::get('background_image_url');

        // حذف الصورة القديمة إذا كانت موجودة وليست الصورة الافتراضية
        // تأكد من أن 'images/dashboard-background.jpg' هو المسار الافتراضي الصحيح
        if ($currentBackgroundUrl && !str_contains($currentBackgroundUrl, 'dashboard-background.jpg') && Storage::disk('public')->exists(str_replace(asset(''), '', $currentBackgroundUrl))) {
            Storage::disk('public')->delete(str_replace(asset(''), '', $currentBackgroundUrl));
        }
        
        // حفظ الصورة الجديدة في مجلد 'uploads/backgrounds' داخل 'public'
        $path = $request->file('background_image')->store('uploads/backgrounds', 'public');

        // تحديث مسار الصورة في جدول الإعدادات
        Setting::set('background_image_url', Storage::url($path));

        return redirect()->route('background-settings.index')->with('success', 'تم تحديث صورة الخلفية بنجاح!');
    }
}

