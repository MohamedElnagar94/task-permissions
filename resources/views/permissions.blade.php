@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {!! $dataTable->table(['class' => 'table table-bordered table-hover','style' => 'width:100%']) !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- delete-permission -->
        <div class="modal fade" id="delete-permission" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <span class="targetName font-weight-bold"></span> ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger deletePermission">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- edit-permission -->
        <div class="modal fade" id="edit-permission" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input placeholder="Enter name" type="text" class="form-control" id="name">
                            <span class="invalid-feedback" role="alert">
                                <strong id="invalid_name"></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="guard_name">Guard Name</label>
                            <input placeholder="Enter Guard Name" type="text" class="form-control" id="guard_name">
                            <span class="invalid-feedback" role="alert">
                                <strong id="invalid_guard_name"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success editPermission">Edit</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- add-permission -->
        <div class="modal fade" id="add-permission" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="add_name">Name</label>
                            <input placeholder="Enter name" type="text" class="form-control" id="add_name">
                            <span class="invalid-feedback" role="alert">
                                <strong id="invalid_add_name"></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="add_guard_name">Guard Name</label>
                            <input placeholder="Enter Guard Name" type="text" class="form-control" id="add_guard_name">
                            <span class="invalid-feedback" role="alert">
                                <strong id="invalid_add_guard_name"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success addPermission">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function () {
            let permission_id
            $('body').on('click','.showDeleteModel', function () {
                permission_id = $(this).data('id')
                let name = $(this).data('name')
                $('.targetName').html(name)
            })
            $('.deletePermission').on('click', function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ asset('/permissions/delete') }}" + '?id=' + permission_id,
                    method: "delete",
                    type: 'DELETE',
                    statusCode: {
                        200: (response) => {
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })
                            $('#delete-permission').modal('hide')
                            window.LaravelDataTables["dataTableBuilder"].ajax.reload();
                        }
                    }
                });
            })

            $('body').on('click','.showEditModel', function (e) {
                e.preventDefault()
                permission_id = $(this).data('id')
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ asset('/permissions/show') }}",
                    data:{
                        permission_id:permission_id
                    },
                    method: "post",
                    statusCode: {
                        200: (response) => {
                            $('#name').val(response.permission.name).removeClass('is-invalid')
                            $('#guard_name').val(response.permission.guard_name).removeClass('is-invalid')
                            $('#invalid_name').html('')
                            $('#invalid_guard_name').html('')
                            $('#edit-permission').modal('show')
                        }
                    }
                });
            })
            $('.editPermission').on('click', function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ asset('/permissions/update') }}",
                    data:{
                        permission_id:permission_id,
                        name:$('#name').val(),
                        guard_name:$('#guard_name').val(),
                    },
                    method: "post",
                    statusCode: {
                        200: (response) => {
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })
                            $('#edit-permission').modal('hide')
                            window.LaravelDataTables["dataTableBuilder"].ajax.reload();
                        },
                        404: (error) => {
                            if (error.responseJSON.error.name){
                                $('#name').addClass('is-invalid')
                                $('#invalid_name').html(error.responseJSON.error.name[0])
                            }
                            if (error.responseJSON.error.guard_name){
                                $('#guard_name').addClass('is-invalid')
                                $('#invalid_guard_name').html(error.responseJSON.error.guard_name[0])
                            }
                        }
                    }
                });
            })

            $('body').on('click','.showAddModel', function (e) {
                e.preventDefault()
                $('#add_name').val('').removeClass('is-invalid')
                $('#add_guard_name').val('').removeClass('is-invalid')
                $('#add_invalid_name').html('')
                $('#invalid_add_guard_name').html('')
                $('#add-permission').modal('show')
            })
            $('.addPermission').on('click', function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ asset('/permissions/add') }}",
                    data:{
                        name:$('#add_name').val(),
                        guard_name:$('#add_guard_name').val(),
                    },
                    method: "post",
                    statusCode: {
                        200: (response) => {
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })
                            $('#add-permission').modal('hide')
                            window.LaravelDataTables["dataTableBuilder"].ajax.reload();
                        },
                        404: (error) => {
                            if (error.responseJSON.error.name){
                                $('#add_name').addClass('is-invalid')
                                $('#invalid_add_name').html(error.responseJSON.error.name[0])
                            }
                            if (error.responseJSON.error.guard_name){
                                $('#add_guard_name').addClass('is-invalid')
                                $('#invalid_add_guard_name').html(error.responseJSON.error.guard_name[0])
                            }
                        }
                    }
                });
            })
        })

    </script>
@endpush
