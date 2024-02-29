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
                <form action="{{ route('admin.user.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="enter name" value="{{ old('name') }}">
                        @error('name')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="enter username" value="{{ old('username') }}">
                        @error('username')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="enter email" value="{{ old('email') }}">
                        @error('email')
                        <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="password">
                        @error('password')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-dark rounded">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
