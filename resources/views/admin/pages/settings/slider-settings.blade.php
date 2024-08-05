@extends('admin.layout.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            @if ($type === 'other')
                                Home Slider Setting
                            @elseif ($type === 'starline')
                                Starline Slider Setting
                            @elseif ($type === 'delhi')
                                Delhi Slider Setting
                            @else
                                Default Slider Settings
                            @endif
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store.slides') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="ander">Upload Slides</label>
                                        <input type="file" name="slides[]" class="form-control" id="slides" required multiple>
                                        <input type="hidden" name="type" value="{{ $type }}" class="form-control" id="type">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Upload Slides</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>All Slides</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($slides as $slide)
                                    <tr>
                                        <td>{{ $slide->title }}</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $slide->image_path) }}" alt="{{ $slide->title }}" width="100" class="img-thumbnail">
                                        </td>
                                        <td>
                                            <form action="{{ route('slides.destroy', ['id' => $slide->id, 'type' => $slide->type]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this slide?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center font-bold">Slides Not Found</td>
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

