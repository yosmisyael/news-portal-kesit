@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- heading --}}
        <h1 class="h3 mb-2 text-gray-800">{{ parseTitleForControlPanelPage($title) }}</h1>

        {{-- body --}}
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-6 col-lg-2">
                        Submission ID
                    </div>
                    <div class="col-6 col-lg-4">
                        {{ $submission->id }}
                    </div>
                    <div class="col-6 col-lg-2">
                        Submitted at
                    </div>
                    <div class="col-6 col-lg-2">
                        {{ $submission->created_at }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-lg-2">
                        Post ID
                    </div>
                    <div class="col-6 col-lg-4">
                        {{ $submission->post_id }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-lg-2">
                        User
                    </div>
                    <div class="col-6 col-lg-4">
                        {{ $post->user->username }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.suspension.store', ['id' => $post->id]) }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="violation">Violation</label>
                        <textarea class="form-control @error('violation')
                            is-invalid
                        @enderror" id="violation" rows="3" name="violation"> {{ old('violation') }}</textarea>
                        @error('violation')
                        <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <input type="hidden" name="submissionId" value="{{ $submission->id }}">
                    <a href="{{ route('admin.post.show', ['id' => $post->id]) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-danger">Suspend</button>
                </form>
            </div>
        </div>
    </div>
@endsection
