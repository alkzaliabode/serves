@php
    $validImages = collect($images ?? [])->filter(function ($path) {
        if (!is_string($path)) {
            return false;
        }

        $path = trim($path);
        $relativePath = preg_replace('~^(?:public/|storage/)+~', '', ltrim($path, '/'));

        return $relativePath !== '' && $relativePath !== 'storage' && $relativePath !== 'public';
    })->map(fn ($path) => trim($path));
@endphp
@forelse ($validImages as $imagePath)
    @php
        $imageUrl = preg_match('~^https?://~i', $imagePath)
            ? $imagePath
            : asset('storage/' . preg_replace('~^(?:public/|storage/)+~', '', ltrim($imagePath, '/')));
    @endphp
    <a href="{{ $imageUrl }}" target="_blank" rel="noopener noreferrer" class="d-inline-block m-1" title="فتح الصورة">
        <img src="{{ $imageUrl }}" alt="صورة {{ $loop->iteration }}" loading="lazy"
             style="max-width:80px;max-height:65px;object-fit:contain" onerror="this.style.display='none'">
        <span class="d-block small">فتح الصورة {{ $loop->iteration }}</span>
    </a>
@empty
    <span class="text-muted">لا توجد صور</span>
@endforelse
