<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <link rel="shortcut icon" href="{{ asset('logo.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <title>{{ $title }}</title>
</head>
<body>
<div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <div class="row g-lg-5 py-5">
        <a href="{{ route('public.homepage') }}" class="text-decoration-none">Kembali</a>
    </div>
    <div class="row align-items-center g-lg-5 py-5">
        <div class="col-md-10 mx-auto col-lg-5 d-flex gap-2 align-items-center">
            <img src="{{ asset('logo.webp') }}" alt="logo" height="100px" width="100px">
            <h1>User Login</h1>
        </div>
        <div class="col-md-10 mx-auto col-lg-5">
            {{-- Alert for error --}}
            @if (isset($errors) && $errors->has('error'))
                @error('error')
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $errors->first('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @enderror
            @endif
            <form class="p-4 p-md-5 border rounded-3 bg-light" method="post" action="{{ route('user.auth.postLogin') }}">
                @csrf
                <div class="form-floating mb-3">
                    <input name="username" type="text" class="form-control @if (isset($errors))
                        @error('username') is-invalid @enderror"
                    @endif id="user" placeholder="id" value="{{ old('username') }}">
                    <label for="user">Username</label>
                    @if (isset($errors))
                        @error('username')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    @endif
                </div>
                <div class="form-floating mb-3">
                    <input name="password" type="password" class="form-control
                        @if (isset($errors) && $errors->has('password'))
                            @error('password') is-invalid @enderror
                        @endif
                    id="password" placeholder="password">
                    <label for="password">Password</label>
                    @if (isset($errors) && $errors->has('password'))
                        <span class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </span>
                    @endif
                </div>
                <button class="w-100 btn btn-lg btn-dark" type="submit">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
