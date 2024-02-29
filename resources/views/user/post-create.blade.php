@extends('user.layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- heading --}}
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

        {{-- notification alert --}}
        @if (session('error'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- content --}}
        <div class="card shadow mb-4">
            <div class="card-body">
                <form id="post-form" method="POST" action="{{ route('user.post.store', ['username' => '@' . $user->username]) }}">
                    @csrf
                    {{-- title --}}
                    <div class="form-group">
                        <label for="title" class="h4">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Enter your article title" value="{{ old('title') }}" required>
                        @error('title')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- category --}}
                    <div class="form-group">
                        <label for="category" class="h4">Category</label>
                        <div class="form-check">
                            @foreach ($categories as $category)
                                <input
                                    name="category[]"
                                    class="@error('category') is-invalid @enderror"
                                    type="checkbox"
                                    value="{{ $category->id }}"
                                    id="{{ $category->id }}"
                                    {{ in_array($category->id, old('category', $categories->toArray())) ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="{{ $category->id }}">{{ $category->name }}</label>
                            @endforeach
                            @error('category')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- content --}}
                    <div class="form-group">
                        <label for="editor" class="h4">Content</label>
                        <textarea name="content" id="editor" class="@error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
                        @error('content')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <a href="{{ route('user.dashboard', ['username' => '@' . $user->username]) }}" class="btn btn-danger">Discard</a>
                    <span id="save-button" class="btn btn-primary">Save</span>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('libraries')
    <script src="https://cdn.tiny.cloud/1/hw8fpnd461cs5gyo1uhnhmq0pihqpin0kgglob7kmcl0f3je/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const csrfToken = '{{ csrf_token() }}';
        const uploadURL = '{{ route('user.post.storePict') }}'
    </script>
    <script src="{{ asset('js/imageUploadHandler.js') }}"></script>
@endsection
