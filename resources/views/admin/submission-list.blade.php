@extends('admin.layouts.main')
{{-- @dd($submissions) --}}
@section('content')
    <div class="container-fluid">
        {{-- heading --}}
        <h1 class="h3 mb-2 text-gray-800">Post Submission List</h1>
        <p class="mb-4">List of post submitted by users.</p>

        {{-- notification alert --}}
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        {{-- body --}}
        <div class="card shadow mb-4">
            <div class="card-body">
                {{-- table --}}
                @if ($submissions && $submissions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Post Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($submissions as $submission)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('admin.submission.show', ['submissionId' => $submission->id]) }}" class="text-decoration-none text-dark">{{ $submission->post->title }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <h4 class="my-4 text-center">There is no new post submission.</h4>
            @endif
            </div>
        </div>
    </div>
@endsection
