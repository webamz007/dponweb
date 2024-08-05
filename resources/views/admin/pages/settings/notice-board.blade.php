@extends('admin.layout.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            @if ($type === 'other')
                                Home Notice Board
                            @elseif ($type === 'starline')
                                Starline Notice Board
                            @elseif ($type === 'delhi')
                                Delhi Notice Board
                            @else
                                Default Notice Board
                            @endif
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('update-notice-board') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" value="{{ $noticeBoard->title ?? '' }}" class="form-control" id="title" required>
                                        <input type="hidden" name="market_type" value="{{ $type }}" class="form-control" id="market_type">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <textarea id="ckeditor" name="content" required>
                                        {{ $noticeBoard->content ?? '' }}
                                    </textarea>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary">Update Notice</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{ asset('assets/bundles/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/page/ckeditor.js') }}"></script>
@endsection

