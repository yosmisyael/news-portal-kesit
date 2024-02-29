@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- heading --}}
        <h3 class="h3 mb-2 text-gray-800">{{ parseTitleForControlPanelPage($title) }}</h3>

        {{-- submission --}}
        <div class="card shadow mb-4">
            <div class="card-body">

                {{-- body --}}
                <h4 class="lead font-weight-bold">Submission Info</h4>
                <div class="row">
                    <div class="col-6 col-lg-2">
                        Submission ID
                    </div>
                    <div class="col-6 col-lg-4">
                        {{ $submission->id }}
                    </div>
                    <div class="col-6 col-lg-2">
                        Submitted at
                    </div>
                    <div class="col-6 col-lg-2">
                        {{ $submission->created_at }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-lg-2">
                        Post ID
                    </div>
                    <div class="col-6 col-lg-4">
                        {{ $submission->post_id }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-lg-2">
                        User
                    </div>
                    <div class="col-6 col-lg-4">
                        {{ $submission->post->user->username }}
                    </div>
                </div>
            </div>
        </div>

        {{-- review --}}
        <div class="card shadow mb-4">
            <div class="card-body">
                <h4 class="lead font-weight-bold">Review</h4>
                @if ($submission->reviews && $submission->reviews->count() > 0)
                    @foreach ($submission->reviews as $review)
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action my-1">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">Review {{ $loop->iteration }}</h5>
                                <small>{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            @switch($review->status)
                                @case(\App\Enums\PostStatusEnum::APPROVED)
                                    <span class="badge badge-success text-uppercase font-weight-bold">{{ $review->status }}</span>
                                    @break
                                @case(\App\Enums\PostStatusEnum::DENIED)
                                    <span class="badge badge-danger text-uppercase font-weight-bold">{{ $review->status }}</span>
                                    @break
                                @case(\App\Enums\PostStatusEnum::SUSPENDED)
                                    <span class="badge badge-dark text-uppercase font-weight-bold">{{ $review->status }}</span>
                                    @break
                            @endswitch
                            <p class="mb-1">{{ $review->messages }}</p>
                            </a>
                        </div>
                    @endforeach
                @else
                    <p class="text-center">This submission has not been reviewed yet.</p>
                @endif
            </div>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <a href="{{ route('admin.submission.index') }}" class="btn btn-secondary"><i class="fas fa-fw fa-angle-left"></i> Back</a>
            <a href="{{ route('admin.review.create', ['submissionId' => $submission->id]) }}" class="btn btn-primary"><i class="fas fa-fw fa-pen-square"></i> Review</a>
        </div>
    </div>
@endsection
