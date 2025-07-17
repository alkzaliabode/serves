<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class UserProfilePhotoController extends Controller
{
    /**
     * تحديث الصورة الشخصية للمستخدم.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        // التحقق من صحة الطلب: يجب أن يكون ملف صورة وبحد أقصى 2 ميجابايت
        $request->validate([
            'profile_photo' => 'required|image|max:2048', // 2MB Max
        ], [
            'profile_photo.required' => 'يرجى اختيار صورة.',
            'profile_photo.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'profile_photo.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ]);

        $user = Auth::user();

        try {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // تخزين الصورة الجديدة في مجلد 'profile-photos' داخل مجلد 'public'
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            
            // تحديث مسار الصورة في قاعدة البيانات
            $user->profile_photo_path = $path;
            $user->save();
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحميل الصورة: ' . $e->getMessage());
        }

        return back()->with('success', 'تم تحديث الصورة الشخصية بنجاح!');
    }

    /**
     * إزالة الصورة الشخصية للمستخدم.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(): RedirectResponse
    {
        $user = Auth::user();

        // حذف الصورة من نظام الملفات إذا كانت موجودة
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            // إزالة المسار من قاعدة البيانات
            $user->profile_photo_path = null;
            $user->save();
        }

        return back()->with('success', 'تمت إزالة الصورة الشخصية بنجاح!');
    }
}
