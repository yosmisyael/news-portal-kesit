@php use App\Enums\PostStatusEnum;use Carbon\Carbon; @endphp
@extends('user.layouts.main')

@section('content')
    <div class="containter-fluid">
        <div class="card shadow mb-4 mx-md-4">
            <div class="card-body">
                <h1 class="">{{ $post->title }}</h1>
                <div class="d-flex gap-3">
                    @foreach($post->categories as $category)
                        <span class="bg-dark text-white rounded">
                            <span class="py-1 px-2">{{ $category->name }}</span>
                        </span>
                    @endforeach
                </div>
                <div class="mt-4">
                    {!! $post->content !!}
                </div>
            </div>
        </div>
        <div class="card shadow mb-4 mx-md-4">
            <div class="card-body">
                <h5>Submission</h5>
                @if ($post->submissions->count() === 0)
                    <p class="text-center">This post has not been submitted yet.</p>
                @else
                    Latest Submission
                    <a href="{{ route('user.submission.show', ['username' => '@' . $user->username, 'postId' => $post->id, 'submissionId' => $post->submissions->last()->id]) }}"
                       class="list-group-item list-group-item-action flex-column align-items-start rounded my-1">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Submission {{ sizeof($post->submissions) }}</h5>
                            <small
                                class="text-muted">{{ parseTimeForHuman($post->submissions->last()->created_at) }}</small>
                        </div>
                        <small class="text-muted">
                            @if ($post->submissions->last()->suspension)
                                <span class="border border-dark rounded p-1 font-weight bold text-dark text-uppercase font-weight-bold">
                                    SUSPENDED
                                </span>
                            @elseif($post->submissions->last()->review)
                                @switch($post->submissions->last()->review->status)
                                    @case(PostStatusEnum::APPROVED)
                                        <span class="border border-success rounded p-1 font-weight bold text-success text-uppercase font-weight-bold">
                                            {{ $post->submissions->last()->review->status }}
                                        </span>
                                        @break
                                    @case(PostStatusEnum::DENIED)
                                        <span class="border border-danger rounded p-1 font-weight bold text-danger text-uppercase font-weight-bold">
                                            {{ $post->submissions->last()->review->status }}
                                        </span>
                                        @break
                                @endswitch
                            @else
                                <span
                                    class="border border-dark rounded p-1 font-weight-bold">Waiting on the list
                                </span>
                            @endif
                        </small>
                        <p class="mb-1">
                            click here to see the review detail
                        </p>
                    </a>
                    <p class="mt-3">You can't edit or delete your post if it has been submitted or published. <a href="{{ route('user.submission.index', ['username' => '@' . $user->username, 'postId' => $post->id]) }}" class="bg-dark text-white rounded py-1 px-2 text-decoration-none">View Submission History</a></p>
                @endif

                @unless(Auth::user()->cannot('submitPost', $post) && Auth::user()->cannot('update', $post) && Auth::user()->cannot('delete', $post))
                    <h5>Actions</h5>
                @endif

                @can('submitPost', $post)
                    <button id="submitBtn" class="btn btn-success" data-toggle="modal" data-target="#submissionModal"
                            data-postId="{{ $post->id }}"><i class="fas fa-fw fa-paper-plane"></i> Submit
                    </button>
                @endcan
                @can('update', $post)
                    <a class="btn btn-warning m-1"
                       href="{{ route('user.post.edit', ['id' => $post->id, 'username' => '@' . $user->username]) }}"><i
                            class="fas fa-fw fa-edit"></i> Edit</a>
                @endcan
                @can('delete', $post)
                    <button class="btn btn-danger m-1" type="button" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-fw fa-trash"></i> Delete</button>
                @endcan
            </div>
        </div>
        <a href="{{ route('user.post.index', ['username' => '@' . $user->username]) }}"
           class="btn btn-secondary m-md-4 m-2"><i class="fas fa-fw fa-angle-left"></i> Back to post list</a>
    </div>

    {{-- submit modal --}}
    <div class="modal fade" id="submissionModal" tabindex="-1" aria-labelledby="submissionModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Submission Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you ready to submit this post? After submitting, <span class="font-weight-bold">you will not be able to edit or delete your post</span>
                    until the admin reviews it.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <form id="submissionForm" action="{{ route('user.submission.store', [
                        'postId' => $post->id,
                        'username' => '@' . $user->username,
                    ]) }}" method="post">
                        @csrf
                        <input type="hidden" name="postId" value="{{ $post->id }}">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- delete modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Post Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure to delete this post? <span class="font-weight-bold">This action is not reversible.</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="submissionForm" action="{{ route('user.post.destroy', [
                        'id' => $post->id,
                        'username' => '@' . $user->username
                    ]) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
