@extends('admin.layout.app')
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>All Notifications</h4>
                        <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">Create New Notification</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Message</th>
                                    <th>Sent To</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($notifications as $notification)
                                    <tr>
                                        <td>{{ $notification->id }}</td>
                                        <td>{{ $notification->title }}</td>
                                        <td>{{ $notification->message }}</td>
                                        <td>
                                            @if($notification->user_id)
                                                {{ $notification->user->name ?? 'User Deleted' }}
                                            @else
                                                All Users
                                            @endif
                                        </td>
                                        <td>{{ $notification->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this notification?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No notifications found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
