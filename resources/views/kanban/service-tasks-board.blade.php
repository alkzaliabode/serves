{{-- resources/views/kanban/service-tasks-board.blade.php --}}

@extends('layouts.adminlte')

@section('title', 'لوحة مهام الشُعبة الخدمية')

@section('page_title', 'لوحة مهام الشُعبة الخدمية')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">لوحة المهام</li>
@endsection

@section('styles')
    <style>
        :root {
            --accent-color: #00eaff; /* Light blue/cyan for interactive elements and emphasis */
            --glass-background-light: rgba(255, 255, 255, 0.08); /* Consistent transparent background for glass effect */
            --glass-background-medium: rgba(255, 255, 255, 0.15);
            --glass-background-dark: rgba(0, 0, 0, 0.2);
            --glass-border: 1px solid rgba(255, 255, 255, 0.2); /* Consistent transparent border */
            --glass-shadow: 0 4px 30px rgba(0, 0, 0, 0.2); /* Consistent shadow */
            --text-primary-color: white;
            --text-shadow-strong: 2px 2px 5px rgba(0, 0, 0, 0.9);
            --text-shadow-medium: 1px 1px 3px rgba(0, 0, 0, 0.7);
            --text-shadow-light: 1px 1px 2px rgba(0, 0, 0, 0.5);

            /* Kanban column specific colors */
            --column-pending-bg: rgba(255, 165, 0, 0.2); /* Orange transparent */
            --column-in_progress-bg: rgba(30, 144, 255, 0.2); /* Blue transparent */
            --column-completed-bg: rgba(60, 179, 113, 0.2); /* Green transparent */
            --column-rejected-bg: rgba(220, 20, 60, 0.2); /* Red transparent */

            /* Card attribute colors - matched to Filament definitions */
            --color-purple: #8B5CF6;
            --color-sky: #0EA5E9;
            --color-indigo: #6366F1;
            --color-red: #EF4444;
            --color-yellow: #FACC15;
            --color-green: #22C55E;
            --color-blue: #3B82F6;
            --color-gray: #9CA3AF; /* Added gray for default case */
        }

        /* General card styling for transparent effect */
        .card, .modal-content {
            background: var(--glass-background-light) !important;
            backdrop-filter: blur(10px) !important;
            border-radius: 1rem !important;
            box-shadow: var(--glass-shadow) !important;
            border: var(--glass-border) !important;
            color: var(--text-primary-color);
            text-shadow: var(--text-shadow-light);
        }
        .card-header, .modal-header {
            background-color: rgba(255, 255, 255, 0.15) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: var(--accent-color) !important;
            text-shadow: var(--text-shadow-medium);
        }
        .modal-header .btn-close { /* For Bootstrap 5 close button */
            filter: invert(1) grayscale(1) brightness(2); /* Make it white */
        }
        .modal-body {
            color: var(--text-primary-color);
            text-shadow: var(--text-shadow-light);
        }
        .modal-footer {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.2) !important;
        }

        /* Form Controls */
        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: var(--text-primary-color) !important;
            text-shadow: var(--text-shadow-light) !important;
            font-size: 1rem !important;
            padding: 0.5rem 0.75rem !important;
            border-radius: 0.5rem;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }
        .form-control:focus, .form-select:focus {
            background-color: rgba(255, 255, 255, 0.2) !important;
            border-color: var(--accent-color) !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 234, 255, 0.25) !important; /* Accent color shadow */
        }
        .form-select option {
            background-color: #2c3e50; /* Dark background for options */
            color: white;
        }
        label {
            color: var(--accent-color) !important;
            text-shadow: var(--text-shadow-medium) !important;
            font-weight: 600;
        }
        .text-danger { /* For validation errors */
            color: #ff4d4d !important;
            text-shadow: none !important;
        }

        /* Kanban Board Styles */
        .kanban-board {
            display: flex;
            flex-wrap: nowrap; /* Prevent wrapping for horizontal scroll */
            gap: 1.5rem; /* Gap between columns */
            padding: 1rem;
            overflow-x: auto; /* Enable horizontal scrolling */
            align-items: flex-start; /* Align columns to the top */
        }

        .kanban-column {
            flex-shrink: 0; /* Prevent columns from shrinking */
            width: 300px; /* Fixed width for columns */
            min-height: 500px; /* Minimum height for columns */
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: var(--glass-shadow);
            border: var(--glass-border);
            backdrop-filter: blur(8px);
            display: flex;
            flex-direction: column;
        }

        /* Column specific backgrounds */
        .kanban-column.pending { background-color: var(--column-pending-bg); }
        .kanban-column.in_progress { background-color: var(--column-in_progress-bg); }
        .kanban-column.completed { background-color: var(--column-completed-bg); }
        .kanban-column.rejected { background-color: var(--column-rejected-bg); }

        .column-header {
            font-size: 1.8rem; /* Larger header */
            font-weight: 800; /* Extra bold */
            margin-bottom: 1rem;
            text-align: center;
            color: var(--accent-color);
            text-shadow: var(--text-shadow-strong);
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        }

        .kanban-cards {
            flex-grow: 1; /* Allow cards area to grow */
            display: flex;
            flex-direction: column;
            gap: 1rem; /* Gap between cards */
            padding: 0.5rem;
            min-height: 100px; /* Smallest height for draggable area */
        }

        .kanban-card {
            background: var(--glass-background-medium) !important; /* Slightly darker glass for cards */
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 0.75rem;
            padding: 1rem;
            cursor: grab;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
        }
        .kanban-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
        }
        .kanban-card.sortable-ghost { /* Style for the ghost element during drag */
            opacity: 0.4;
            background-color: rgba(0, 234, 255, 0.3) !important; /* Accent color transparent */
            border: 2px dashed var(--accent-color);
        }

        .card-title-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--accent-color);
            text-shadow: var(--text-shadow-medium);
            margin-bottom: 0.5rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .card-description-text {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: var(--text-shadow-light);
            margin-bottom: 1rem;
            /* Optional: limit description height */
            max-height: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .card-attribute {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: var(--text-shadow-light);
        }
        .card-attribute i {
            font-size: 1rem;
            filter: drop-shadow(1px 1px 2px rgba(0,0,0,0.5));
        }

        /* Card attribute colors based on Filament's definitions */
        .attribute-purple i { color: var(--color-purple); }
        .attribute-sky i { color: var(--color-sky); }
        .attribute-indigo i { color: var(--color-indigo); }
        .attribute-red i { color: var(--color-red); }
        .attribute-yellow i { color: var(--color-yellow); }
        .attribute-green i { color: var(--color-green); }
        .attribute-blue i { color: var(--color-blue); }
        .attribute-gray i { color: var(--color-gray); } /* Using the new gray variable */

        .card-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
        }
        .card-actions .btn {
            font-size: 0.85rem;
            padding: 0.3rem 0.8rem;
            margin-inline-start: 0.5rem;
            border-radius: 0.4rem;
        }
        .btn-edit {
            background-color: #007bff !important;
            border-color: #007bff !important;
        }
        .btn-delete {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
        }

        /* Add Task Button */
        .add-task-btn {
            background-color: var(--accent-color) !important;
            color: #333 !important;
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 234, 255, 0.4);
            border: none;
        }
        .add-task-btn:hover {
            background-color: #00bfff !important;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 234, 255, 0.6);
        }

        /* Notifications */
        .custom-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1060; /* Higher than modal backdrop */
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            display: none; /* Hidden by default */
            opacity: 0;
            transition: opacity 0.5s ease-out;
        }
        .custom-notification.show {
            display: block;
            opacity: 1;
        }
        .custom-notification.success { background-color: rgba(40, 167, 69, 0.8); }
        .custom-notification.error { background-color: rgba(220, 53, 69, 0.8); }

        /* General Buttons */
        .btn-primary, .btn-danger { /* For modal buttons */
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background-color: #007bff !important;
            border-color: #007bff !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.4) !important;
        }
        .btn-primary:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.6) !important;
        }
        .btn-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4) !important;
        }
        .btn-danger:hover {
            background-color: #c82333 !important;
            border-color: #bd2130 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.6) !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-end mb-4">
            <button type="button" class="btn add-task-btn" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                <i class="fas fa-plus"></i> إضافة مهمة جديدة
            </button>
        </div>

        <div class="kanban-board" id="kanban-board">
            @foreach($columns as $statusKey => $columnTitle)
                <div class="kanban-column {{ $statusKey }}" data-status="{{ $statusKey }}">
                    <h2 class="column-header">{{ $columnTitle }}</h2>
                    <div class="kanban-cards" id="kanban-column-{{ $statusKey }}">
                        @foreach(($groupedTasks[$statusKey] ?? collect())->sortBy('order_column') as $task)
                            {{-- Make sure task-card partial exists and has the necessary data-attributes --}}
                            @include('kanban.partials.task-card', ['task' => $task, 'statuses' => $statuses, 'units' => $units, 'priorities' => $priorities])
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Create Task Modal --}}
    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTaskModalLabel">إنشاء مهمة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createTaskForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="create_title" class="form-label">عنوان المهمة</label>
                            <input type="text" class="form-control" id="create_title" name="title" required placeholder="أدخل عنوان المهمة">
                            <div class="invalid-feedback" id="create_title_error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="create_description" class="form-label">وصف المهمة</label>
                            <textarea class="form-control" id="create_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="create_unit" class="form-label">الوحدة</label>
                                <select class="form-select" id="create_unit" name="unit" required>
                                    @foreach($units as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="create_unit_error"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="create_priority" class="form-label">الأولوية</label>
                                <select class="form-select" id="create_priority" name="priority">
                                    @foreach($priorities as $key => $value)
                                        <option value="{{ $key }}" @if($key == 'medium') selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="create_due_date" class="form-label">تاريخ الاستحقاق</label>
                                <input type="date" class="form-control" id="create_due_date" name="due_date">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="create_assigned_to" class="form-label">تعيين إلى</label>
                                <select class="form-select" id="create_assigned_to" name="assigned_to">
                                    <option value="">لا يوجد</option>
                                    @foreach($employees as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">إنشاء المهمة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Task Modal --}}
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskModalLabel">تعديل المهمة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editTaskForm">
                    <input type="hidden" id="edit_task_id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">عنوان المهمة</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                            <div class="invalid-feedback" id="edit_title_error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">وصف المهمة</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_status" class="form-label">الحالة</label>
                                <select class="form-select" id="edit_status" name="status" required>
                                    @foreach($statuses as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="edit_status_error"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_unit" class="form-label">الوحدة</label>
                                <select class="form-select" id="edit_unit" name="unit" required>
                                    @foreach($units as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="edit_unit_error"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_priority" class="form-label">الأولوية</label>
                                <select class="form-select" id="edit_priority" name="priority">
                                    @foreach($priorities as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_due_date" class="form-label">تاريخ الاستحقاق</label>
                                <input type="date" class="form-control" id="edit_due_date" name="due_date">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_assigned_to" class="form-label">تعيين إلى</label>
                            <select class="form-select" id="edit_assigned_to" name="assigned_to">
                                <option value="">لا يوجد</option>
                                @foreach($employees as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" id="deleteTaskButton">
                            <i class="fas fa-trash"></i> حذف المهمة
                        </button>
                        <div>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Confirmation Modal for Delete (Optional, as the Filament action already has confirmation) --}}
    {{-- This can be used for a custom confirmation if the Filament confirmation is not desired --}}
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد من حذف هذه المهمة؟ لا يمكن التراجع عن هذا الإجراء.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">تأكيد الحذف</button>
                </div>
            </div>
        </div>
    </div>


    {{-- Custom Notification Toast --}}
    <div id="customNotification" class="custom-notification">
        <span id="notificationMessage"></span>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const columns = document.querySelectorAll('.kanban-cards');
            const createTaskForm = document.getElementById('createTaskForm');
            const editTaskForm = document.getElementById('editTaskForm');
            const deleteTaskButton = document.getElementById('deleteTaskButton');
            const editTaskModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
            const createTaskModal = new bootstrap.Modal(document.getElementById('createTaskModal'));
            let currentEditingTask = null; // To store the task being edited

            // Function to show custom notification
            function showNotification(message, type = 'info') {
                const notification = document.getElementById('customNotification');
                const notificationMessage = document.getElementById('notificationMessage');
                notificationMessage.textContent = message;
                notification.className = `custom-notification show ${type}`;
                setTimeout(() => {
                    notification.className = 'custom-notification';
                }, 3000); // Hide after 3 seconds
            }

            // Function to clear validation errors
            function clearValidationErrors(formId) {
                const form = document.getElementById(formId);
                if (!form) return;
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            }

            // Helper function to create a new task card element from provided task data
            // This function takes the raw task object returned from the backend and constructs the HTML.
            // It relies on the backend returning all necessary accessors (e.g., status_label, assigned_to_name, unit_icon, priority_icon, priority_color).
            function createTaskCardElement(task) {
                // Determine status badge class
                let statusBadgeClass = '';
                if (task.status === 'pending') statusBadgeClass = 'bg-warning';
                else if (task.status === 'in_progress') statusBadgeClass = 'bg-info';
                else if (task.status === 'completed') statusBadgeClass = 'bg-success';
                else if (task.status === 'rejected') statusBadgeClass = 'bg-danger';

                // Use accessors for unit and priority info
                const unitIconHtml = `<i class="${task.unit_icon} unit-icon"></i>`;
                const unitLabel = task.unit_label;
                const priorityIconHtml = `<i class="${task.priority_icon} priority-icon"></i>`;
                const priorityLabel = task.priority_label;
                const priorityColorClass = `attribute-${task.priority_color}`; // Example: attribute-red

                const cardHtml = `
                    <div class="kanban-card"
                         data-task-id="${task.id}"
                         data-title="${task.title}"
                         data-description="${task.description || ''}"
                         data-status="${task.status}"
                         data-unit="${task.unit}"
                         data-priority="${task.priority}"
                         data-due-date="${task.due_date || ''}"
                         data-assigned-to="${task.assigned_to || ''}"
                         data-assigned-to-name="${task.assigned_to_name || 'غير معين'}"
                         data-order-column="${task.order_column}">
                        <h4 class="card-title-text">${task.title}</h4>
                        <p class="card-description-text">${task.description || 'لا يوجد وصف.'}</p>
                        <div class="card-attribute">
                            <i class="fas fa-tag"></i>
                            <span>الحالة: <span class="badge ${statusBadgeClass} status-badge">${task.status_label}</span></span>
                        </div>
                        <div class="card-attribute">
                            ${unitIconHtml}
                            <span>الوحدة: <span class="unit-text">${unitLabel}</span></span>
                        </div>
                        <div class="card-attribute ${priorityColorClass}">
                            ${priorityIconHtml}
                            <span>الأولوية: <span class="priority-text">${priorityLabel}</span></span>
                        </div>
                        <div class="card-attribute">
                            <i class="fas fa-calendar-alt"></i>
                            <span>تاريخ الاستحقاق: <span class="due-date">${task.formatted_due_date || 'N/A'}</span></span>
                        </div>
                        <div class="card-attribute">
                            <i class="fas fa-user-tie"></i>
                            <span>المسؤول: <span class="assigned-to-name">${task.assigned_to_name || 'غير معين'}</span></span>
                        </div>
                        <div class="card-actions">
                            <button type="button" class="btn btn-edit btn-sm" data-bs-toggle="modal" data-bs-target="#editTaskModal">
                                <i class="fas fa-edit"></i> تعديل
                            </button>
                        </div>
                    </div>
                `;
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = cardHtml.trim();
                return tempDiv.firstChild;
            }


            // Function to update the UI of an existing card (crucial for local updates)
            // This function assumes the `updatedTask` object contains all necessary data,
            // including accessors like `status_label`, `priority_label`, `priority_color`, `assigned_to_name`, `formatted_due_date`.
            // The Laravel controller's `store` and `update` methods should return these.
            function updateCardUI(cardElement, updatedTask) {
                // Update data- attributes
                cardElement.dataset.title = updatedTask.title;
                cardElement.dataset.description = updatedTask.description;
                cardElement.dataset.status = updatedTask.status;
                cardElement.dataset.unit = updatedTask.unit;
                cardElement.dataset.priority = updatedTask.priority;
                cardElement.dataset.dueDate = updatedTask.due_date || ''; // Ensure it's not null for dataset
                cardElement.dataset.assignedTo = updatedTask.assigned_to || ''; // Use assigned_to directly
                cardElement.dataset.assignedToName = updatedTask.assigned_to_name || 'غير معين'; 
                
                // Update visible elements
                cardElement.querySelector('.card-title-text').textContent = updatedTask.title;
                cardElement.querySelector('.card-description-text').textContent = updatedTask.description || 'لا يوجد وصف.';
                
                // Update status badge (assuming you have a span/element with class 'status-badge')
                const statusBadge = cardElement.querySelector('.status-badge');
                if (statusBadge) {
                    statusBadge.textContent = updatedTask.status_label; // Use the accessor
                    
                    // Remove existing bg- classes and add new one based on status
                    statusBadge.classList.remove('bg-warning', 'bg-info', 'bg-success', 'bg-danger'); 
                    if (updatedTask.status === 'pending') statusBadge.classList.add('bg-warning');
                    else if (updatedTask.status === 'in_progress') statusBadge.classList.add('bg-info');
                    else if (updatedTask.status === 'completed') statusBadge.classList.add('bg-success');
                    else if (updatedTask.status === 'rejected') statusBadge.classList.add('bg-danger');
                }

                // Update unit icon and label
                const unitIconElement = cardElement.querySelector('.unit-icon');
                const unitTextElement = cardElement.querySelector('.unit-text');
                if (unitIconElement) {
                    unitIconElement.className = `${updatedTask.unit_icon} unit-icon`; // Update icon class
                }
                if (unitTextElement) {
                    unitTextElement.textContent = updatedTask.unit_label; // Update text
                }

                // Update priority icon, label, and color class
                const priorityIconElement = cardElement.querySelector('.priority-icon');
                const priorityTextElement = cardElement.querySelector('.priority-text');
                const priorityAttributeContainer = cardElement.querySelector('.card-attribute.attribute-red, .card-attribute.attribute-yellow, .card-attribute.attribute-green, .card-attribute.attribute-purple, .card-attribute.attribute-sky, .card-attribute.attribute-indigo, .card-attribute.attribute-blue, .card-attribute.attribute-gray'); // Select existing color classes

                if (priorityIconElement) {
                    priorityIconElement.className = `${updatedTask.priority_icon} priority-icon`; // Update icon class
                }
                if (priorityTextElement) {
                    priorityTextElement.textContent = updatedTask.priority_label; // Update text
                }
                if (priorityAttributeContainer) {
                    // Remove all previous color classes
                    priorityAttributeContainer.classList.remove('attribute-red', 'attribute-yellow', 'attribute-green', 'attribute-purple', 'attribute-sky', 'attribute-indigo', 'attribute-blue', 'attribute-gray');
                    // Add the new color class based on the accessor
                    priorityAttributeContainer.classList.add(`attribute-${updatedTask.priority_color}`);
                }


                // Update assigned to name
                const assignedToElement = cardElement.querySelector('.assigned-to-name'); 
                if (assignedToElement) {
                    assignedToElement.textContent = updatedTask.assigned_to_name || 'غير معين'; 
                }
            }


            // Initialize SortableJS for each Kanban column
            columns.forEach(column => {
                new Sortable(column, {
                    group: 'kanban', // All columns share the same group
                    animation: 150,
                    ghostClass: 'sortable-ghost', // Class for the ghost element
                    onEnd: function (evt) {
                        const newStatus = evt.to.closest('.kanban-column').dataset.status;
                        const taskId = evt.item.dataset.taskId;
                        
                        // Get the new order of tasks in the target column
                        const newOrder = Array.from(evt.to.children)
                                            .filter(child => child.classList.contains('kanban-card'))
                                            .map(child => child.dataset.taskId);

                        updateTaskStatusAndOrder(taskId, newStatus, newOrder);
                    },
                });
            });

            // Handle Create Task Form Submission
            createTaskForm.addEventListener('submit', function(e) {
                e.preventDefault();
                clearValidationErrors('createTaskForm');

                const formData = {
                    title: document.getElementById('create_title').value,
                    description: document.getElementById('create_description').value,
                    unit: document.getElementById('create_unit').value,
                    priority: document.getElementById('create_priority').value,
                    due_date: document.getElementById('create_due_date').value,
                    assigned_to: document.getElementById('create_assigned_to').value || null,
                };

                fetch('{{ route('service-tasks.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        createTaskModal.hide();
                        createTaskForm.reset();

                        // Add the new task to the 'pending' column (default status)
                        // Make sure the backend returns the 'task' object with all accessors
                        const newTaskElement = createTaskCardElement(data.task);
                        const pendingColumn = document.querySelector('.kanban-column[data-status="pending"] .kanban-cards');
                        if (pendingColumn) {
                            // Find the correct position based on order_column (if task.order_column is available)
                            const existingCards = Array.from(pendingColumn.children);
                            let inserted = false;
                            for (let i = 0; i < existingCards.length; i++) {
                                const existingOrder = parseInt(existingCards[i].dataset.orderColumn);
                                if (data.task.order_column < existingOrder) {
                                    pendingColumn.insertBefore(newTaskElement, existingCards[i]);
                                    inserted = true;
                                    break;
                                }
                            }
                            if (!inserted) {
                                pendingColumn.appendChild(newTaskElement);
                            }
                        } else {
                            console.error('Pending column not found!');
                        }
                    } else {
                        showNotification(data.message || 'فشل إنشاء المهمة.', 'error');
                        if (data.errors) {
                            for (const [key, messages] of Object.entries(data.errors)) {
                                const input = document.getElementById(`create_${key}`);
                                const errorDiv = document.getElementById(`create_${key}_error`);
                                if (input && errorDiv) {
                                    input.classList.add('is-invalid');
                                    errorDiv.textContent = messages[0];
                                }
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('حدث خطأ أثناء إنشاء المهمة.', 'error');
                });
            });

            // Populate Edit Task Modal when a card's "Edit" button is clicked
            document.getElementById('kanban-board').addEventListener('click', function(e) {
                const editButton = e.target.closest('.btn-edit');
                if (editButton) {
                    const card = editButton.closest('.kanban-card');
                    if (card) {
                        currentEditingTask = {
                            id: card.dataset.taskId,
                            title: card.dataset.title,
                            description: card.dataset.description,
                            status: card.dataset.status,
                            unit: card.dataset.unit,
                            priority: card.dataset.priority,
                            due_date: card.dataset.dueDate,
                            assigned_to: card.dataset.assignedTo,
                            order_column: parseInt(card.dataset.orderColumn) // Get current order
                        };

                        document.getElementById('edit_task_id').value = currentEditingTask.id;
                        document.getElementById('edit_title').value = currentEditingTask.title;
                        document.getElementById('edit_description').value = currentEditingTask.description;
                        document.getElementById('edit_status').value = currentEditingTask.status;
                        document.getElementById('edit_unit').value = currentEditingTask.unit;
                        document.getElementById('edit_priority').value = currentEditingTask.priority;
                        document.getElementById('edit_due_date').value = currentEditingTask.due_date;
                        document.getElementById('edit_assigned_to').value = currentEditingTask.assigned_to;
                        
                        clearValidationErrors('editTaskForm');
                    }
                }
            });

            // Handle Edit Task Form Submission
            editTaskForm.addEventListener('submit', function(e) {
                e.preventDefault();
                clearValidationErrors('editTaskForm');

                const taskId = document.getElementById('edit_task_id').value;
                const formData = {
                    title: document.getElementById('edit_title').value,
                    description: document.getElementById('edit_description').value,
                    status: document.getElementById('edit_status').value,
                    unit: document.getElementById('edit_unit').value,
                    priority: document.getElementById('edit_priority').value,
                    due_date: document.getElementById('edit_due_date').value,
                    assigned_to: document.getElementById('edit_assigned_to').value || null,
                };

                fetch(`/service-tasks/${taskId}`, { // Use dynamic URL
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        editTaskModal.hide();

                        // Find the existing card and update its UI
                        const existingCardElement = document.querySelector(`.kanban-card[data-task-id="${taskId}"]`);
                        if (existingCardElement) {
                            updateCardUI(existingCardElement, data.task);
                        } else {
                            console.error('Task card not found in UI for update:', taskId);
                        }
                    } else {
                        showNotification(data.message || 'فشل تحديث المهمة.', 'error');
                        if (data.errors) {
                            for (const [key, messages] of Object.entries(data.errors)) {
                                const input = document.getElementById(`edit_${key}`);
                                const errorDiv = document.getElementById(`edit_${key}_error`);
                                if (input && errorDiv) {
                                    input.classList.add('is-invalid');
                                    errorDiv.textContent = messages[0];
                                }
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('حدث خطأ أثناء تحديث المهمة.', 'error');
                });
            });

            // Handle Delete Task Button Click (from edit modal)
            deleteTaskButton.addEventListener('click', function() {
                if (currentEditingTask && confirm('هل أنت متأكد أنك تريد حذف هذه المهمة؟ لا يمكن التراجع عن هذا الإجراء.')) {
                    const taskId = currentEditingTask.id;
                    fetch(`/service-tasks/${taskId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');
                            editTaskModal.hide();
                            // Remove the card from the UI
                            const cardToRemove = document.querySelector(`.kanban-card[data-task-id="${taskId}"]`);
                            if (cardToRemove) {
                                cardToRemove.remove();
                            }
                        } else {
                            showNotification(data.message || 'فشل حذف المهمة.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('حدث خطأ أثناء حذف المهمة.', 'error');
                    });
                }
            });

            // Function to update task status and order (called by SortableJS onEnd)
            async function updateTaskStatusAndOrder(taskId, newStatus, newOrder) {
                try {
                    const response = await fetch(`/service-tasks/${taskId}/update-status-and-order`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ newStatus: newStatus, newOrder: newOrder })
                    });
                    const data = await response.json();

                    if (data.success) {
                        showNotification(data.message, 'success');
                        // No need to re-render the card, SortableJS already moved it.
                        // However, update its internal data-status for consistency.
                        const movedCard = document.querySelector(`.kanban-card[data-task-id="${taskId}"]`);
                        if(movedCard) {
                            movedCard.dataset.status = newStatus;
                            // Re-sort cards within the new column based on updated order_column
                            // This step is critical to reflect the new order from the backend
                            const targetColumn = movedCard.closest('.kanban-cards');
                            if (targetColumn) {
                                // Re-fetch the order if needed or just re-sort based on newOrder array
                                // For simplicity, we assume newOrder correctly reflects the order in the current column
                                const sortedCards = Array.from(targetColumn.children)
                                                            .sort((a, b) => {
                                                                const orderA = newOrder.indexOf(a.dataset.taskId);
                                                                const orderB = newOrder.indexOf(b.dataset.taskId);
                                                                return orderA - orderB;
                                                            });
                                // Clear and re-append sorted cards
                                targetColumn.innerHTML = '';
                                sortedCards.forEach(card => targetColumn.appendChild(card));
                            }
                        }

                    } else {
                        showNotification(data.message || 'فشل تحديث الحالة والترتيب.', 'error');
                        // Revert the drag operation visually if the backend update fails (optional but good UX)
                        // This would involve re-initializing SortableJS or manually moving the card back
                        // For now, we'll just log the error and notify.
                    }
                } catch (error) {
                    console.error('Error updating task status and order:', error);
                    showNotification('حدث خطأ غير متوقع أثناء تحديث الحالة والترتيب.', 'error');
                }
            }
        });
    </script>
@endsection
