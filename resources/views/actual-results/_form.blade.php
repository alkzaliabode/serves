<div class="row" x-data="{
    unitId: @js(old('unit_id', $actualResult->unit_id ?? '')),
    date: @js(old('date', $actualResult->date ? $actualResult->date->format('Y-m-d') : now()->format('Y-m-d'))),
    completedTasks: @js(old('completed_tasks', $actualResult->completed_tasks ?? 0)),
    workingHours: @js(old('working_hours', $actualResult->working_hours ?? 8)),
    qualityRating: @js(old('quality_rating', $actualResult->quality_rating ?? 3)),
    targetTasksDisplay: @js(old('target_tasks_display', $actualResult->target_tasks_display ?? 0)),
    effectiveness: @js(old('effectiveness', $actualResult->effectiveness ?? 0)),
    efficiency: @js(old('efficiency', $actualResult->efficiency ?? 0)),
    relevance: @js(old('relevance', $actualResult->relevance ?? 0)),
    overallPerformanceScore: @js(old('overall_performance_score', $actualResult->overall_performance_score ?? 0)),
    unitGoalId: @js(old('unit_goal_id', $actualResult->unit_goal_id ?? null)),
    departmentGoalId: @js(old('department_goal_id', $actualResult->department_goal_id ?? null)),

    // Function to fetch and update metrics
    fetchMetrics: async function() {
        if (!this.unitId || !this.date) {
            this.targetTasksDisplay = 0;
            this.effectiveness = 0;
            this.efficiency = 0;
            this.relevance = 0;
            this.overallPerformanceScore = 0;
            this.unitGoalId = null;
            this.departmentGoalId = null;
            return;
        }

        try {
            const response = await fetch('{{ route('actual-results.get-form-metrics') }}?' + new URLSearchParams({
                unit_id: this.unitId,
                date: this.date,
                completed_tasks: this.completedTasks,
                working_hours: this.workingHours,
                quality_rating: this.qualityRating,
            }));
            const data = await response.json();
            this.targetTasksDisplay = data.target_tasks_display;
            this.effectiveness = data.effectiveness;
            this.efficiency = data.efficiency;
            this.relevance = data.relevance;
            this.overallPerformanceScore = data.overall_performance_score;
            this.unitGoalId = data.unit_goal_id;
            this.departmentGoalId = data.department_goal_id;
        } catch (error) {
            console.error('Error fetching metrics:', error);
            // Optionally, show a user-friendly error message
        }
    },

    init() {
        // Initial fetch when component loads
        this.fetchMetrics();
        // Set up watchers for relevant fields
        this.$watch('unitId', () => this.fetchMetrics());
        this.$watch('date', () => this.fetchMetrics());
        this.$watch('completedTasks', () => this.fetchMetrics());
        this.$watch('workingHours', () => this.fetchMetrics());
        this.$watch('qualityRating', () => this.fetchMetrics());
    }
}">
    @csrf
    @isset($actualResult->id)
        @method('PUT')
    @endisset

    <div class="card-body p-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="form-group row">
            <div class="col-md-6 mb-3">
                <label for="date">التاريخ</label>
                <input type="date" name="date" id="date" class="form-control" x-model="date" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="unit_id">الوحدة</label>
                <select name="unit_id" id="unit_id" class="form-control" x-model.number="unitId" required>
                    <option value="">اختر وحدة...</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6 mb-3">
                <label for="target_tasks_display">المهام المستهدفة (من الهدف)</label>
                <input type="text" id="target_tasks_display" class="form-control" x-model="targetTasksDisplay" disabled>
                <small class="form-text text-muted">يتم جلبها تلقائياً من جدول أهداف الوحدة</small>
            </div>
            <div class="col-md-6 mb-3">
                <label for="completed_tasks">المهام المكتملة</label>
                <input type="number" name="completed_tasks" id="completed_tasks" class="form-control" x-model.number="completedTasks" required min="0">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6 mb-3">
                <label for="working_hours">ساعات العمل</label>
                <input type="number" name="working_hours" id="working_hours" class="form-control" x-model.number="workingHours" required min="0" max="24">
            </div>
            <div class="col-md-6 mb-3">
                <label for="quality_rating">تقييم الجودة</label>
                <select name="quality_rating" id="quality_rating" class="form-control" x-model.number="qualityRating">
                    <option value="1">⭐ ضعيف</option>
                    <option value="2">⭐⭐ مقبول</option>
                    <option value="3">⭐⭐⭐ جيد</option>
                    <option value="4">⭐⭐⭐⭐ ممتاز</option>
                    <option value="5">⭐⭐⭐⭐⭐ استثنائي</option>
                </select>
            </div>
        </div>

        <!-- Gilbert Performance Metrics Display -->
        <h4 class="mt-4 mb-3 text-primary">مثلث الأداء Gilbert</h4>
        <div class="form-group row">
            <div class="col-md-4 mb-3">
                <label for="effectiveness">الفعالية (%)</label>
                <input type="text" id="effectiveness" class="form-control text-green-600 font-bold" x-model="effectiveness" disabled>
                <small class="form-text text-muted">النتائج ÷ الأهداف</small>
            </div>
            <div class="col-md-4 mb-3">
                <label for="efficiency">الكفاءة (%)</label>
                <input type="text" id="efficiency" class="form-control text-blue-600 font-bold" x-model="efficiency" disabled>
                <small class="form-text text-muted">النتائج ÷ الموارد</small>
            </div>
            <div class="col-md-4 mb-3">
                <label for="relevance">الملاءمة (%)</label>
                <input type="text" id="relevance" class="form-control text-purple-600 font-bold" x-model="relevance" disabled>
                <small class="form-text text-muted">الموارد ÷ الأهداف</small>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-12 mb-3">
                <label for="overall_performance_score">نقاط الأداء الإجمالية (%)</label>
                <input type="text" id="overall_performance_score" class="form-control text-red-600 font-bold text-lg" x-model="overallPerformanceScore" disabled>
                <small class="form-text text-muted">متوسط الأبعاد الثلاثة</small>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6 mb-3">
                <label for="efficiency_score">درجة الكفاءة (1-100) <small>(اختياري)</small></label>
                <input type="number" name="efficiency_score" id="efficiency_score" class="form-control" value="{{ old('efficiency_score', $actualResult->efficiency_score ?? '') }}" min="1" max="100">
                <small class="form-text text-muted">درجة إضافية لتقييم الكفاءة، يدوية الإدخال</small>
            </div>
            <div class="col-md-6 mb-3">
                <label for="notes">ملاحظات</label>
                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $actualResult->notes ?? '') }}</textarea>
            </div>
        </div>

        {{-- Hidden fields for unit_goal_id and department_goal_id to be stored in DB --}}
        <input type="hidden" name="unit_goal_id" x-model="unitGoalId">
        <input type="hidden" name="department_goal_id" x-model="departmentGoalId">

        <button type="submit" class="btn btn-primary mt-3">
            <i class="fas fa-save mr-1"></i> حفظ السجل
        </button>
        <a href="{{ route('actual-results.index') }}" class="btn btn-secondary mt-3 ml-2">
            <i class="fas fa-ban mr-1"></i> إلغاء
        </a>
    </div>
</div>
