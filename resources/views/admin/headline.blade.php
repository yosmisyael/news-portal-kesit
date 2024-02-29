@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    {{-- heading --}}
    <h1 class="h3 mb-2 text-gray-800"><i class="fas fa-fw fa-fire"></i>{{ parseTitleForControlPanelPage($title) }}</h1>

    {{-- flash message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- content --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            {{-- headline form --}}
            <form action="{{ route('admin.headline.store') }}" method="post" class="mb-5">
                @csrf
                <div class="form-group">
                    <label for="title">Headline Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="enter title" value="{{ old('title') }}">
                    @error('title')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            {{-- headlines list --}}
            @if ($headlines && $headlines->count() > 0)
                <h5>
                    Published Headline
                </h5>
                @foreach ($headlines as $hl)
                    <div class="list-group">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $hl->title }}
                                <span>
                                    <a class="badge badge-warning" href="{{ route('admin.headline.edit', ['id' => $hl->id]) }}">
                                        <button class="btn btn-warning">
                                            <i class="fas fa-fw fa-edit"></i>
                                        </button>
                                    </a>
                                    <span class="badge badge-danger">
                                        <button type="button" id="deleteButton" class="btn btn-danger" data-hl-id="{{ $hl->id }}" data-toggle="modal" data-target="#deleteModal">
                                            <i class="fas fa-fw fa-trash"></i>
                                        </button>
                                    </span>
                                </span>
                            </li>
                        </ul>
                    </div>
                @endforeach
            @else
                <p class="text-center mt-4">No Headline Found</p>
            @endif
        </div>
    </div>
</div>

{{--  delete modal  --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure want to delete this headline?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="" method="post" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            const deleteButton = document.getElementById('deleteButton');
            const deleteForm = document.getElementById('deleteForm');
            deleteButton.addEventListener('click', function () {
                const headlineId = deleteButton.dataset.hlId
                deleteForm.action = `/control-panel/headline/${headlineId}`;
            });
        });
    </script>
@endsection
