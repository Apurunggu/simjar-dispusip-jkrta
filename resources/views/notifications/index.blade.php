@extends('layout')

@section('title', 'Notifikasi')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-bell"></i> Notifikasi
                    </h5>
                    @if(auth()->user()->notifikasi()->unread()->count() > 0)
                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-light">
                            <i class="bi bi-check-all"></i> Tandai Semua Dibaca
                        </button>
                    </form>
                    @endif
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="notification-list">
                            @foreach($notifications as $notification)
                            <div class="notification-item border-bottom pb-3 mb-3 @if($notification->isRead()) opacity-50 @endif">
                                <div class="row">
                                    <div class="col-auto">
                                        <i class="bi {{ $notification->icon }} fs-5 text-{{ $notification->color }}"></i>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">
                                                    {{ $notification->title }}
                                                    @if(!$notification->isRead())
                                                    <span class="badge bg-primary">Baru</span>
                                                    @endif
                                                </h6>
                                                <p class="mb-2 text-muted">{{ $notification->message }}</p>
                                                <small class="text-muted d-block">
                                                    <i class="bi bi-clock"></i> 
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <div class="dropdown ms-2">
                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if(!$notification->isRead())
                                                    <li>
                                                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="bi bi-check"></i> Tandai Dibaca
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                    @if($notification->action_url)
                                                    <li>
                                                        <a class="dropdown-item" href="{{ $notification->action_url }}">
                                                            <i class="bi bi-arrow-up-right"></i> Buka
                                                        </a>
                                                    </li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Hapus notifikasi ini?')">
                                                                <i class="bi bi-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center" role="alert">
                            <i class="bi bi-inbox"></i>
                            <p class="mb-0 mt-2">Tidak ada notifikasi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-item {
    padding: 12px 0;
    transition: background-color 0.2s ease;
}

.notification-item:hover {
    background-color: #f8f9fa;
    border-radius: 4px;
    padding: 12px;
    margin-left: -12px;
    margin-right: -12px;
}

.notification-item.opacity-50 {
    opacity: 0.6;
}
</style>
@endsection
