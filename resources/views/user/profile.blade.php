@extends('user.layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 col-4">
                        <img src="
                        @if ($user->profile)
                            {{ asset('storage/images/users/' . $user->id . '/profile/' . $user->profile) }}
                        @else
                            {{ asset('user-icon.webp') }}
                        @endif
                        " alt="logo" class="rounded-circle w-100 mb-3">
                    </div>
                    <div class="col-4 d-flex justify-content-center" style="flex-direction: column">
                        <h3 class="font-weight-bold ">{{ $user->username }}</h3>
                        <h5>{{ $user->name }}</h5>
                    </div>
                </div>
                <div class="row ml-3" style="display: flex; flex-direction: column;">
                    <h1 class="lead">About Me</h1>
                    <p>{{ $user->description }}</p>
                </div>
                <div class="row ml-3">
                    <h5 class="lead">Member Since {{ \Carbon\Carbon::parse($user->created_at)->format('d F Y') }}</h5>
                </div>
            </div>
        </div>
    </div>
@endsection