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
                <form action="{{ route('user.profile.update', ['username' => '@' . $user->username]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
        
                    {{-- profile --}}
                    <div class="form-group">
                        <label for="profile">Profile Picture</label>
                        @if ($user->profile)
                            <label class="d-block col-6 col-md-3 position-relative mx-auto text-center" for="profile">
                                <img id="preview" class="rounded-circle w-100 h-100 mb-3" src="{{ asset('storage/images/users/' . $user->id . '/profile/' . $user->profile) }}">
                                <span class="text-center font-weight-bold">Change Profile</span>
                            </label>
                        @else
                            <label class="d-block col-6 col-md-3 position-relative mx-auto text-center" for="profile">
                                <img id="preview" class="rounded-circle w-100 h-100 mb-3" src="{{ asset('user-icon.webp') }}">
                                <span class="text-center font-weight-bold">Change Profile</span>
                            </label>
                        @endif
                        <input type="file" name="profile" id="profile" style="display: none" class="form-control-file @error('profile')
                            is-invalid
                        @enderror" data-username="{{ $user->username }}" accept="image/*">
                    </div>
        
                    {{-- name --}}
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="enter name" value="{{ old('name') ?? $user->name }}">
                        @error('name')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    
                    {{-- username --}}
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="enter username" value="{{ old('username') ?? $user->username }}">
                        @error('username')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    
                    {{-- description --}}
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control" id="description" rows="3">{{ $user->description ?? old('description') }}</textarea>
                    </div>
        
                    {{-- hidden --}}
                    <input type="hidden" name="oldProfile" value="{{ $user->profile }}">
                    <input type="hidden" name="id" value="{{ $user->id }}">
        
                    <button type="submit" class="btn btn-dark btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-check"></i>
                        </span>
                        <span class="text">Save Changes</span>
                    </button>
        
                </form>
            </div>
        </div>

        {{-- crop image modal --}}
        <div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Crop image before upload</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body row">
                    <div>
                        <img id="imageContainer" class="w-100 h-100" alt="uploaded photo">
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" id="crop" class="btn btn-primary">Save changes</button>
                </div>
              </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" integrity="sha512-hvNR0F/e2J7zPPfLC9auFe3/SE0yG4aJCOd/qxew74NN7eyiSKjr7xJJMu1Jy2wf7FXITpWS1E/RY8yzuXN7VA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('libraries')
    <script src="https://unpkg.com/jquery@3/dist/jquery.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js" integrity="sha512-9KkIqdfN7ipEW6B6k+Aq20PV31bjODg4AA52W+tYtAE0jE0kMx49bjJ3FgvS56wzmyfMUHbQ4Km2b7l9+Y/+Eg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/cropImage.js') }}"></script>
@endsection
