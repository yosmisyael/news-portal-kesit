@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- heading --}}
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

        <div class="card shadow mb-4">
            <div class="card-body">
                {{-- headline form --}}
                <form action="{{ route('admin.category.update', ['id' => $category->id]) }}" method="post" class="mb-5">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="hidden" name="id" value="{{ $category->id }}">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="enter name" value="{{ $category->name ?? old('name') }}">
                        @error('name')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <a href="{{ route('admin.category.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
