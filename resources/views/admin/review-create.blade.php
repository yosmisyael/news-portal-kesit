@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- heading --}}
        <h1 class="h3 mb-2 text-gray-800">{{ parseTitleForControlPanelPage($title) }}</h1>

        {{-- notification alert --}}
        @error('error')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @enderror

        {{-- body --}}
        <div class="card shadow mb-4">
            <div class="card-body">
                <h3>{{ $submission->post->title }}</h3>
                {!! $submission->post->content !!}
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.review.store', ['submissionId' => $submission->id]) }}" method="post">
                    @csrf
                    <input type="hidden" name="submissionId" value="{{ $submission->id }}">
                    <div class="form-group">
                        <label for="inputStatus">Status</label>
                        <select id="inputStatus" class="form-control @error('status')
                            is-invalid
                        @enderror" name="status">
                            @foreach ($status as $state)
                                <option value="{{ $state }}" {{ old('status') == $state->value ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="messages">Messages</label>
                        <textarea class="form-control @error('messages')
                            is-invalid
                        @enderror" id="messages" rows="3" name="messages"> {{ old('messages') }}</textarea>
                        @error('messages')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <input type="hidden" name="post_id" value="{{ $submission->post_id }}">
                    <a href="{{ route('admin.submission.show', ['submissionId' => $submission->id]) }}" class="btn btn-secondary"><i class="fas fa-fw fa-angle-left"></i> Back</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
