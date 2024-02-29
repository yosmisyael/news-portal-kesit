@php use Carbon\Carbon; use App\Enums\PostStatusEnum; @endphp
@extends('user.layouts.main')

@section('styles')
    <link href="{{ asset('css/timeline.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid">
        {{-- heading --}}
        <h3 class="h3 mb-2 text-gray-800">Submission History</h3>

        {{-- submission --}}
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="container mt-5 mb-5">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            @if($submissions && $submissions->count() > 0)
                                <h4>Timeline</h4>
                                <ul class="timeline">
                                    @foreach($submissions as $submission)
                                        <li>
                                            <a href="{{ route('user.submission.show', ['username' => '@' . $user->username, 'postId' => $submission->post_id, 'submissionId' => $submission->id]) }}"
                                               class="text-decoration-none text-dark">Submission {{ $loop->iteration }}</a>
                                            <p class="float-right">{{ Carbon::parse($submission->created_at)->locale('id')->timezone('Asia/Jakarta')->isoFormat('D MMM YYYY HH:mm')}}</p>
                                        </li>
                                        @if ($submission->review)
                                            <li>
                                                <a href="{{ route('user.submission.show', ['username' => '@' . $user->username, 'postId' => $submission->post_id, 'submissionId' => $submission->id]) }}"
                                                   class="text-decoration-none text-dark">Review of Submission {{ $loop->iteration }}</a>
                                                <p class="float-right">{{ Carbon::parse($submission->review->created_at)->locale('id')->timezone('Asia/Jakarta')->isoFormat('D MMM YYYY HH:mm')}}</p>
                                                <p>
                                                    @switch($submission->review->status)
                                                        @case(PostStatusEnum::APPROVED)
                                                            <span class="badge badge-success text-uppercase">
                                                                {{ $submission->review->status }}
                                                            </span>
                                                            @break
                                                        @case(PostStatusEnum::DENIED)
                                                            <span class="badge badge-danger text-uppercase">
                                                                {{ $submission->review->status }}
                                                            </span>
                                                            @break
                                                    @endswitch
                                                    {{ $submission->review->messages }}
                                                </p>
                                            </li>
                                        @endif
                                        @if ($submission->suspension)
                                            <li>
                                                <a href="{{ route('user.submission.show', ['username' => '@' . $user->username, 'postId' => $submission->post_id, 'submissionId' => $submission->id]) }}"
                                                   class="text-decoration-none text-dark">Suspension of Submission {{ $loop->iteration }}</a>
                                                <p class="float-right">{{ Carbon::parse($submission->suspension->created_at)->locale('id')->timezone('Asia/Jakarta')->isoFormat('D MMM YYYY HH:mm')}}</p>
                                                <p>
                                                    <span class="badge badge-dark">
                                                                SUSPENDED
                                                    </span>
                                                    {{ $submission->suspension->violation }}
                                                </p>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-center">This post does not have any submissions yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('user.post.show', ['username' => '@' . $user->username, 'id' => $post_id]) }}"
           class="btn btn-secondary m-md-4 m-2"><i class="fas fa-fw fa-angle-left"></i> Back to post</a>
    </div>
@endsection
