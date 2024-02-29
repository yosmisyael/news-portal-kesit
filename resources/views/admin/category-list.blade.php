@php use Carbon\Carbon; @endphp
@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800"><i class="fas fa-tags"></i> Category List</h1>
        <p class="mb-4">List of category.</p>

        {{-- notification alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Content -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Post</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if ($categories)
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->posts_count }}</td>
                                    <td>
                                        <a class="badge badge-warning" href="{{ route('admin.category.edit', ['id' => $category->id]) }}">
                                            <button class="btn btn-warning">
                                                <i class="fas fa-fw fa-edit"></i>
                                            </button>
                                        </a>
                                        <span class="badge badge-danger">
                                            <button type="button" id="deleteButton" class="btn btn-danger" data-tag-id="{{ $category->id }}" data-toggle="modal" data-target="#deleteModal">
                                                <i class="fas fa-fw fa-trash"></i>
                                            </button>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <p>There is no category has been made.</p>
                        @endif
                        </tbody>
                    </table>
                </div>
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
                    Are you sure want to delete this category?
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
                const tagId = deleteButton.dataset.tagId
                deleteForm.action = `/control-panel/category/${tagId}`;
            });
        });
    </script>
@endsection
