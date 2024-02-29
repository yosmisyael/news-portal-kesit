@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- heading --}}
        <h1 class="h3 mb-2 text-gray-800">{{ parseTitleForControlPanelPage($title) }}</h1>

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
                                    <a href="{{ route('admin.post.show', ['id' => $post->id]) }}" class="text-decoration-none text-dark">
                                        {{ $post->title }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <h4 class="text-center my-5">There is no published post yet.</h4>
                @endif

            </div>
        </div>
    </div>
@endsection
