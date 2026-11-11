(function () {
    'use strict';
    document.addEventListener('change', function (event) {
        const input = event.target;
        if (!input.classList.contains('task-image-input')) return;
        const group = input.closest('.task-image-upload');
        const preview = group.querySelector('.task-image-selection');
        preview.replaceChildren();
        group.querySelector('.task-image-count').textContent = input.files.length
            ? 'الصور المختارة: ' + input.files.length : 'لم يتم اختيار صور';
        for (const file of input.files) {
            const item = document.createElement('div');
            item.className = 'm-1 text-center';
            const name = document.createElement('div');
            name.textContent = file.name;
            const image = document.createElement('img');
            image.alt = file.name;
            image.style.cssText = 'max-width:120px;max-height:100px;object-fit:contain';
            const url = URL.createObjectURL(file);
            image.onload = () => URL.revokeObjectURL(url);
            image.onerror = () => { URL.revokeObjectURL(url); image.remove(); };
            image.src = url;
            item.append(image, name);
            preview.appendChild(item);
        }
    });
}());
