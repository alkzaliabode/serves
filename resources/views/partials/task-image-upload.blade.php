<div class="task-image-upload mb-2">
    <button type="button" class="btn btn-info" onclick="document.getElementById('{{ $field }}').click()">
        <i class="fas fa-images" aria-hidden="true"></i> {{ $label }}
    </button>
    <input type="file" id="{{ $field }}" name="{{ $field }}[]" multiple
           accept="image/*,.heic,.heif,.avif,.tif,.tiff,.webp,.bmp,.ico,.svg"
           class="task-image-input" style="position:absolute;width:1px;height:1px;opacity:0;overflow:hidden"
           aria-label="{{ $label }}" aria-describedby="{{ $field }}_help">
    <span class="task-image-count mx-2" aria-live="polite">لم يتم اختيار صور</span>
    <div id="{{ $field }}_help" class="form-text text-muted">يمكن اختيار عدة صور بأي صيغة صور يتعرف عليها الخادم، حتى 15 ميغابايت للصورة. بعض الصيغ تُفتح خارج المتصفح.</div>
    <div class="task-image-selection d-flex flex-wrap mt-2" aria-live="polite"></div>
    @error($field)<div class="text-danger">{{ $message }}</div>@enderror
    @error($field . '.*')<div class="text-danger">{{ $message }}</div>@enderror
</div>
@once
    <script src="{{ asset('js/task-images.js') }}" defer></script>
@endonce
