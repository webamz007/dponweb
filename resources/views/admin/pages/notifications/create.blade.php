@extends('admin.layout.app')

@section('css')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Create Notifications</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.notifications.store') }}" method="POST">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="title">Title</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title') }}" required>
                                @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="message">Message</label>
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror"
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Send Notification To</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="notification_for"
                                           value="all" id="allUsers" {{ old('notification_for', 'all') === 'all' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allUsers">
                                        All Users
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="notification_for"
                                           value="specific" id="specificUser" {{ old('notification_for') === 'specific' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="specificUser">
                                        Specific User
                                    </label>
                                </div>
                                @error('notification_for')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3" id="userSelectDiv" style="{{ old('notification_for') === 'specific' ? 'display:block' : 'display:none' }}">
                                <label for="user_id">Select User</label>
                                <select name="user_id" class="form-control select2 @error('user_id') is-invalid @enderror">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name  }} - ( {{ $user->phone  }} )
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Create Notification</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="notification_for"]');
            const userSelectDiv = document.getElementById('userSelectDiv');

            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'specific') {
                        userSelectDiv.style.display = 'block';
                    } else {
                        userSelectDiv.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
