<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ImageHelper;
use App\Models\TaskImageReport;
use Illuminate\Support\Facades\Artisan;

class TestImagesController extends Controller
{
    /**
     * عرض صفحة اختبار الصور
     */
    public function index()
    {
        return view('test-images');
    }
    
    /**
     * اختبار تحميل الصور
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048'
        ]);
        
        $path = ImageHelper::uploadImage($request->file('image'), 'test-uploads');
        
        return back()->with('success', 'تم تحميل الصورة بنجاح: ' . $path);
    }
    
    /**
     * إعادة توليد تقارير الصور
     */
    public function regenerateReports()
    {
        Artisan::call('regenerate-image-reports');
        $output = Artisan::output();
        
        return back()->with('info', 'تم إعادة توليد تقارير الصور: ' . $output);
    }
}
