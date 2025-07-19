@extends('layouts.admin_layout')

@section('title', 'اختبار عرض الصور')
@section('page_title', 'اختبار عرض الصور')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">اختبار عرض الصور</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">أنواع مختلفة من عرض الصور</h3>
                </div>
                <div class="card-body">
                    <h4>1. عرض الصور باستخدام Helper جديد:</h4>
                    <div class="row mb-4">
                        @php
                            use App\Helpers\ImageHelper;
                            
                            // أمثلة لمسارات صور مختلفة للاختبار
                            $paths = [
                                'uploads/test1.jpg',
                                'storage/uploads/test2.jpg',
                                'public/uploads/test3.jpg',
                                'http://example.com/test4.jpg',
                                'not-exist.jpg'
                            ];
                        @endphp
                        
                        @foreach($paths as $index => $path)
                            <div class="col-md-2 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <img src="{{ ImageHelper::getImageUrl($path) }}" class="img-fluid rounded" 
                                             style="height: 120px; width: 100%; object-fit: cover;"
                                             alt="صورة {{ $index + 1 }}">
                                        <p class="mt-2">{{ $path }}</p>
                                        <small>{{ ImageHelper::imageExists($path) ? 'موجودة' : 'غير موجودة' }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <hr>
                    
                    <h4>2. عرض الصور من تقارير الصور:</h4>
                    @php
                        $taskReports = App\Models\TaskImageReport::take(3)->get();
                    @endphp
                    
                    <div class="row">
                        @forelse($taskReports as $report)
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">{{ $report->report_title }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <h6>صور قبل:</h6>
                                        <div class="d-flex flex-wrap mb-3">
                                            @forelse($report->before_images_urls as $image)
                                                <a href="{{ $image['url'] }}" target="_blank" class="m-1">
                                                    <img src="{{ $image['url'] }}" class="img-thumbnail" 
                                                         style="height: 60px; width: 60px; object-fit: cover;"
                                                         alt="صورة قبل"
                                                         onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                                                </a>
                                            @empty
                                                <p>لا توجد صور</p>
                                            @endforelse
                                        </div>
                                        
                                        <h6>صور بعد:</h6>
                                        <div class="d-flex flex-wrap">
                                            @forelse($report->after_images_urls as $image)
                                                <a href="{{ $image['url'] }}" target="_blank" class="m-1">
                                                    <img src="{{ $image['url'] }}" class="img-thumbnail" 
                                                         style="height: 60px; width: 60px; object-fit: cover;"
                                                         alt="صورة بعد"
                                                         onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                                                </a>
                                            @empty
                                                <p>لا توجد صور</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">
                                    لا توجد تقارير صور للعرض
                                </div>
                            </div>
                        @endforelse
                    </div>
                    
                    <hr>
                    
                    <h4>3. عرض الصور باستخدام direct-image.php:</h4>
                    <div class="row">
                        @php
                            $paths = [
                                'uploads/test1.jpg',
                                'storage/uploads/test2.jpg',
                                'not-exist.jpg'
                            ];
                        @endphp
                        
                        @foreach($paths as $path)
                            <div class="col-md-4 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <img src="{{ url('direct-image.php?path=' . urlencode($path)) }}" class="img-fluid rounded" 
                                             style="height: 120px; width: 100%; object-fit: cover;"
                                             alt="{{ $path }}"
                                             onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                                        <p class="mt-2">{{ $path }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <hr>
                    
                    <h4>4. الأسباب المحتملة لمشاكل عرض الصور:</h4>
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> معلومات مفيدة لتشخيص مشاكل عرض الصور</h5>
                        <ul>
                            <li>تأكد من وجود الرابط الرمزي للتخزين: <code>php artisan storage:link</code></li>
                            <li>تأكد من صلاحيات المجلدات (القراءة والكتابة): <code>chmod -R 775 storage</code></li>
                            <li>تحقق من أن <code>APP_URL</code> في ملف <code>.env</code> مضبوط بشكل صحيح</li>
                            <li>جرّب أمر إعادة إنشاء تقارير الصور: <code>php artisan regenerate-image-reports</code></li>
                            <li>تحقق من وجود ملف الصورة الاحتياطية: <code>public/images/no-image.png</code></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
