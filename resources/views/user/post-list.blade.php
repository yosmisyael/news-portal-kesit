@extends('user.layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- heading --}}
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

        {{-- notification alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('errors'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('errors') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-body">
                @if ($posts && $posts->count() > 0)
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Title</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>
                                    <a href="{{ route('user.post.show', ['id' => $post->id, 'username' => '@' . $user->username]) }}" class="text-decoration-none text-dark">
                                        {{ $post->title }}
                                        @if ($post->latestReview)
                                            @switch($post->latestReview->status)
                                                @case(\App\Enums\PostStatusEnum::APPROVED)
                                                    <span class="badge badge-success">Approved</span>
                                                    @break
                                                @case(\App\Enums\PostStatusEnum::DENIED)
                                                    <span class="badge badge-danger">Denied</span>
                                                    @break
                                                @case(\App\Enums\PostStatusEnum::SUSPENDED)
                                                    <span class="badge badge-info">Suspended</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">Unknown</span>
                                            @endswitch
                                        @elseif ($post->submission)
                                            <span class="badge badge-secondary">pending</span>
                                        @else
                                            <span class="badge badge-secondary">private</span>
                                        @endif
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <h4 class="text-center my-5">You have not written any articles yet...</h4>
                @endif
            </div>
        </div>
    </div>
@endsection
