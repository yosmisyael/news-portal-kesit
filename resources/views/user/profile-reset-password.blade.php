@extends('user.layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- heading --}}
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

        {{-- notification alert --}}
        @error('error')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @enderror

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('user.profile.patchReset', ['username' => '@' . $user->username]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="password">Enter New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="enter new password" value="{{ old('password') }}">
                        @error('password')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="confirmation">Confirm New Password</label>
                        <input type="password" class="form-control @error('confirmation') is-invalid @enderror" id="confirmation" name="confirmation" placeholder="confirm new password" value="{{ old('confirmation') }}">
                        @error('confirmation')
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