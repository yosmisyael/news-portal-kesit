@php use App\Enums\PostStatusEnum; @endphp
@extends('user.layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- heading --}}
        <h3 class="h3 mb-2 text-gray-800">Submission Detail</h3>

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
                <h4 class="lead font-weight-bold">Review Result</h4>
                @if ($submission->review || $submission->suspension)
                    <div class="list-group">
                        <span class="list-group-item list-group-item-action">
                            <small>Status</small>
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">
                                    @if ($submission->suspension)
                                        <span class="font-weight-bold text-dark text-uppercase">
                                            SUSPENDED
                                        </span>
                                    @elseif($submission->review)
                                        @switch ($submission->review->status)
                                            @case(PostStatusEnum::APPROVED)
                                                <span class="font-weight-bold text-success text-uppercase">
                                                    {{ $submission->review->status }}
                                                </span>
                                                @break
                                            @case(PostStatusEnum::DENIED)
                                                <span class="font-weight-bold text-danger text-uppercase">
                                                    {{ $submission->review->status }}
                                                </span>
                                                @break
                                        @endswitch
                                    @endif
                                </h5>
                                @if($submission->suspension)
                                    <small>{{ parseTimeForHuman($submission->suspension->created_at) }}</small>
                                @elseif($submission->review)
                                    <small>{{ parseTimeForHuman($submission->review->created_at) }}</small>
                                @endif
                            </div>
                            <small>Messages from administrator</small>
                            @if($submission->suspension)
                                <p>{{ $submission->suspension->violation }}</p>
                            @elseif($submission->review)
                                <p>{{ $submission->review->messages }}</p>
                            @endif
                        </span>
                    </div>
                @else
                    <p class="text-center">Your submission has not been reviewed yet.</p>
                    <p class="text-center">Please have patience, you will be notified as soon as your submission is
                        reviewed.</p>
                @endif
            </div>
        </div>
        <a href="{{ route('user.post.show', ['username' => '@'. $submission->post->user->username, 'id' => $submission->post_id]) }}"
           class="btn btn-primary mb-4"><i class="fas fa-fw fa-angle-left"></i> Back to Post</a>
    </div>
@endsection
