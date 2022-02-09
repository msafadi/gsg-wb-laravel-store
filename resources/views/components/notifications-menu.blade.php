<a class="nav-link" data-toggle="dropdown" href="#">
    <i class="far fa-bell"></i>
    <span class="badge badge-warning navbar-badge">{{ $unread }}</span>
</a>
<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
    <span class="dropdown-header">{{ $notifications->count() }} Notifications</span>
    <div class="dropdown-divider"></div>
    @foreach($notifications as $notification)
    <a href="{{ route('dashboard.notifications.read', $notification->id) }}" class="dropdown-item">
        @if($notification->unread())
        <i class="fas fa-envelope mr-2"></i>
        @else
        <i class="fas fa-envelope-open mr-2"></i>
        @endif
        {{ $notification->data['title'] }}
        <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
    </a>
    <div class="dropdown-divider"></div>
    @endforeach
    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
</div>