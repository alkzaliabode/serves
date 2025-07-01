<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md mt-10">
    <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">عداد وتتبع المهام</h2>

    {{-- قسم العداد --}}
    <div class="flex items-center justify-between mb-6">
        <span class="text-lg text-gray-700">العدد الحالي:</span>
        <span class="text-3xl font-extrabold text-blue-600">{{ $count }}</span>
        <button wire:click="increment" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">زيادة العدد</button>
    </div>

    <hr class="my-6 border-gray-300">

    {{-- قسم إضافة المهام --}}
    <h3 class="text-xl font-semibold mb-3 text-gray-800">إضافة مهمة جديدة</h3>
    <div class="flex mb-4">
        <input type="text" wire:model.live="newItem" placeholder="اكتب اسم المهمة..." class="flex-grow p-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent">
        <button wire:click="addItem" class="px-4 py-2 bg-purple-600 text-white rounded-r-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-75">إضافة</button>
    </div>

    {{-- قسم عرض المهام --}}
    <h3 class="text-xl font-semibold mb-3 text-gray-800">المهام الموجودة:</h3>
    @if ($items)
        <ul class="list-disc list-inside bg-gray-50 p-4 rounded-lg border border-gray-200">
            @foreach ($items as $item)
                <li class="py-1 text-gray-700">{{ $item }}</li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-600 text-center">لا توجد مهام حالياً.</p>
    @endif
</div>