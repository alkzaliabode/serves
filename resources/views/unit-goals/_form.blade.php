<div class="card-body p-4">
    @csrf
    @isset($unitGoal->id)
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

    <div class="form-group">
        <label for="department_goal_id">هدف القسم (مع الهدف الرئيسي)</label>
        <select name="department_goal_id" id="department_goal_id" class="form-control" required>
            <option value="">اختر هدف القسم...</option>
            @foreach($departments as $id => $text)
                <option value="{{ $id }}" {{ old('department_goal_id', $unitGoal->department_goal_id ?? '') == $id ? 'selected' : '' }}>
                    {{ $text }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mt-3">
        <label for="unit_id">الوحدة</label>
        <select name="unit_id" id="unit_id" class="form-control">
            <option value="">اختر الوحدة...</option>
            @foreach($units as $unit)
                <option value="{{ $unit->id }}" {{ old('unit_id', $unitGoal->unit_id ?? '') == $unit->id ? 'selected' : '' }}>
                    {{ $unit->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mt-3">
        <label for="unit_name">اسم الوحدة</label>
        <input type="text" name="unit_name" id="unit_name" class="form-control" value="{{ old('unit_name', $unitGoal->unit_name ?? '') }}" required>
    </div>

    <div class="form-group mt-3">
        <label for="goal_text">نص الهدف</label>
        <textarea name="goal_text" id="goal_text" class="form-control" rows="3" required>{{ old('goal_text', $unitGoal->goal_text ?? '') }}</textarea>
    </div>

    <div class="form-group mt-3">
        <label for="target_tasks">عدد المهام المستهدفة</label>
        <input type="number" name="target_tasks" id="target_tasks" class="form-control" value="{{ old('target_tasks', $unitGoal->target_tasks ?? 1) }}" required min="0">
        <small class="form-text text-muted">العدد المستهدف من المهام لإنجاز هذا الهدف.</small>
    </div>

    <div class="form-group mt-3">
        <label for="date">التاريخ</label>
        <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $unitGoal->date ? $unitGoal->date->format('Y-m-d') : '') }}">
    </div>

    <button type="submit" class="btn btn-primary mt-3">
        <i class="fas fa-save mr-1"></i> حفظ الهدف
    </button>
    <a href="{{ route('unit-goals.index') }}" class="btn btn-secondary mt-3 ml-2">
        <i class="fas fa-ban mr-1"></i> إلغاء
    </a>
</div>
