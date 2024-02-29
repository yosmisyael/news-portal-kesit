@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
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
        <span class="d-flex justify-content-between">
            <a href="{{ route('admin.post.index') }}"
               class="btn btn-secondary m-md-4 mx-2"><i class="fas fa-fw fa-angle-left"></i> Back to post list</a>
            <a href="{{ route('admin.suspension.create', ['id' => $post->id]) }}" class="btn btn-danger m-md-4 mx-2">Suspend Post</a>
        </span>
    </div>
@endsection
