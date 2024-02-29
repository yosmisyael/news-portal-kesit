@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    {{-- heading --}}
    <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

    {{-- body --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            {{-- headline form --}}
            <form action="{{ route('admin.headline.update', ['id' => $headline->id]) }}" method="post" class="mb-5">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="title">Headline Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="enter title" value="{{ old('title') ?? $headline->title }}">
                    @error('title')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <a href="{{ route('admin.headline.index') }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>

</div>
@endsection
