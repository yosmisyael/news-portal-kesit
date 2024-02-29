@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- heading --}}
        <h1 class="h3 mb-2 text-gray-800">{{ parseTitleForControlPanelPage($title) }}</h1>

        <div class="card shadow mb-4">
            <div class="card-body">
                {{-- headline form --}}
                <form action="{{ route('admin.category.store') }}" method="post" class="mb-5">
                    @csrf
                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="enter name" value="{{ old('name') }}">
                        @error('name')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
