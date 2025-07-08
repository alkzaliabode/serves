<div class="card-body p-4">
    @csrf
    @isset($resourceTracking->id)
        @method('PUT')
    @endisset

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-group row">
        <div class="col-md-6 mb-3">
            <label for="date">التاريخ</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $resourceTracking->date ? $resourceTracking->date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="unit_id">الوحدة</label>
            <select name="unit_id" id="unit_id" class="form-control" required>
                <option value="">اختر وحدة...</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ old('unit_id', $resourceTracking->unit_id ?? '') == $unit->id ? 'selected' : '' }}>
                        {{ $unit->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6 mb-3">
            <label for="working_hours">ساعات العمل الإجمالية</label>
            <input type="number" name="working_hours" id="working_hours" class="form-control" value="{{ old('working_hours', $resourceTracking->working_hours ?? '') }}" required min="0" max="24">
        </div>
        <div class="col-md-6 mb-3">
            <label for="cleaning_materials">مواد التنظيف المستهلكة (لتر)</label>
            <input type="number" name="cleaning_materials" id="cleaning_materials" class="form-control" value="{{ old('cleaning_materials', $resourceTracking->cleaning_materials ?? '') }}" required min="0">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6 mb-3">
            <label for="water_consumption">استهلاك المياه (لتر)</label>
            <input type="number" name="water_consumption" id="water_consumption" class="form-control" value="{{ old('water_consumption', $resourceTracking->water_consumption ?? '') }}" required min="0">
        </div>
        <div class="col-md-6 mb-3">
            <label for="equipment_usage">عدد المعدات المستخدمة</label>
            <input type="number" name="equipment_usage" id="equipment_usage" class="form-control" value="{{ old('equipment_usage', $resourceTracking->equipment_usage ?? '') }}" required min="0">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12 mb-3">
            <label for="notes">ملاحظات</label>
            <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $resourceTracking->notes ?? '') }}</textarea>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">
        <i class="fas fa-save mr-1"></i> حفظ السجل
    </button>
    <a href="{{ route('resource-trackings.index') }}" class="btn btn-secondary mt-3 ml-2">
        <i class="fas fa-ban mr-1"></i> إلغاء
    </a>
</div>
