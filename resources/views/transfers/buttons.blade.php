<a href="{{ route($transferRoute.'.export', array_merge(request()->query(), ['format' => 'excel'])) }}" class="btn btn-primary btn-sm ml-2"><i class="fas fa-file-excel mr-1"></i> تصدير Excel</a>
<a href="{{ route($transferRoute.'.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" target="_blank" rel="noopener" class="btn btn-danger btn-sm ml-2"><i class="fas fa-file-pdf mr-1"></i> تصدير PDF</a>
<a href="{{ route($transferRoute.'.template') }}" class="btn btn-outline-success btn-sm ml-2">نموذج Excel</a>
<a href="#performance-import" class="btn btn-success btn-sm ml-2" onclick="document.getElementById('performance-import').open = true">استيراد Excel</a>
