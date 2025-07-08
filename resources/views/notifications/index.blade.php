{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'إشعاراتي')

@section('page_title', 'إشعاراتي')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">إشعاراتي</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-info shadow-lg">
            <div class="card-header">
                <h3 class="card-title">جميع الإشعارات</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" id="mark-all-as-read-page-btn">
                        <i class="fas fa-check-double"></i> وضع علامة على الكل كمقروء
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($notifications->isEmpty())
                    <div class="alert alert-info text-center m-3" role="alert">
                        لا توجد إشعارات لعرضها.
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach ($notifications as $notification)
                            <a href="{{ $notification->data['link'] ?? '#' }}" 
                               class="list-group-item list-group-item-action @if (is_null($notification->read_at)) list-group-item-light @endif notification-item-full"
                               data-notification-id="{{ $notification->id }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">
                                        @if (is_null($notification->read_at))
                                            <span class="badge bg-info mr-2">جديد</span>
                                        @endif
                                        {{ $notification->data['message'] ?? 'إشعار جديد' }}
                                    </h5>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-muted">{{ $notification->data['task_title'] ?? '' }}</p>
                                <small class="text-muted">{{ $notification->created_at->format('Y-m-d H:i:s') }}</small>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="card-footer">
                {{ $notifications->links('pagination::bootstrap-5') }} {{-- استخدام تقسيم الصفحات من Bootstrap 5 --}}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const markAllAsReadBtn = document.getElementById('mark-all-as-read-page-btn');
            if (markAllAsReadBtn) {
                markAllAsReadBtn.addEventListener('click', async function(event) {
                    event.preventDefault();
                    try {
                        const response = await fetch('/notifications/mark-all-as-read', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            // Update UI: remove 'list-group-item-light' class from all items
                            document.querySelectorAll('.notification-item-full.list-group-item-light').forEach(item => {
                                item.classList.remove('list-group-item-light');
                                const badge = item.querySelector('.badge.bg-info');
                                if (badge) badge.remove(); // Remove 'جديد' badge
                            });
                            // Optionally, update the main layout notification count to 0
                            if (window.updateUnreadCount) {
                                window.updateUnreadCount(0);
                            }
                            alert('تم وضع علامة على جميع الإشعارات كمقروءة.'); // Use a custom modal in production
                        } else {
                            alert('فشل وضع علامة على الإشعارات كمقروءة.');
                        }
                    } catch (error) {
                        console.error('Error marking all notifications as read:', error);
                        alert('حدث خطأ أثناء وضع علامة على الإشعارات كمقروءة.');
                    }
                });
            }

            // Mark single notification as read when clicked
            document.querySelectorAll('.notification-item-full').forEach(item => {
                item.addEventListener('click', async function(event) {
                    const notificationId = this.dataset.notificationId;
                    if (notificationId && this.classList.contains('list-group-item-light')) { // Only if unread
                        try {
                            const response = await fetch(`/notifications/${notificationId}/mark-as-read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            });
                            const data = await response.json();
                            if (data.success) {
                                this.classList.remove('list-group-item-light');
                                const badge = this.querySelector('.badge.bg-info');
                                if (badge) badge.remove();
                                if (window.updateUnreadCount) {
                                    window.updateUnreadCount(); // Update the main layout count
                                }
                            }
                        } catch (error) {
                            console.error('Error marking single notification as read:', error);
                        }
                    }
                });
            });
        });
    </script>
@endsection
