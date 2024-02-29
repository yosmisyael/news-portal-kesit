@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    {{-- heading --}}
    <h1 class="h3 mb-2 text-gray-800">User List</h1>
    <p class="mb-4">List of journalist active contributor including article writer, editor, and publisher.</p>

    <div class="card shadow mb-4">
        <div class="card-body">
            {{-- notification alert --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- table --}}
            @if ($users && $users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Username</th>
                                <th>Member Since</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users)
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ \Carbon\Carbon::parse($user->created_at)->timezone('Asia/Bangkok')->format('d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.user.edit', ['id' => $user->id]) }}" class="btn btn-secondary btn-icon-split">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-question"></i>
                                                </span>
                                                <span class="text">Reset Password</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            @else
                <p>There is no user has been registered.</p>
            @endif
        </div>
    </div>

</div>
@endsection
