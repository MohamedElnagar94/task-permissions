@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        {!! $dataTable->table(['class' => 'table table-bordered table-hover','style' => 'width:100%']) !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- delete-role -->
        <div class="modal fade" id="delete-role" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        <button type="button" class="btn btn-danger deleteRole">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- edit-role -->
        <div class="modal fade" id="edit-role" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        <button type="button" class="btn btn-success editRole">Edit</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- add-role -->
        <div class="modal fade" id="add-role" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        <button type="button" class="btn btn-success addRole">Add</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- add-permissions -->
        <div class="modal fade" id="add-permissions" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Permissions</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row roles-permissions m-0">

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
            let role_id
            let object
            function toast(message){
                Swal.fire({
                    icon: 'success',
                    title: message,
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
            }
            $('body').on('click', '.showDeleteModel', function () {
                role_id = $(this).data('id')
                let name = $(this).data('name')
                $('.targetName').html(name)
            })
            $('.deleteRole').on('click', function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ asset('/roles/delete') }}" + '?id=' + role_id,
                    method: "delete",
                    type: 'DELETE',
                    statusCode: {
                        200: (response) => {
                            toast(response.message)
                            $('#delete-role').modal('hide')
                            window.LaravelDataTables["dataTableBuilder"].ajax.reload();
                        }
                    }
                });
            })

            $('body').on('click', '.showEditModel', function (e) {
                e.preventDefault()
                role_id = $(this).data('id')
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ asset('/roles/show') }}",
                    data: {
                        role_id: role_id
                    },
                    method: "post",
                    statusCode: {
                        200: (response) => {
                            $('#name').val(response.role.name).removeClass('is-invalid')
                            $('#guard_name').val(response.role.guard_name).removeClass('is-invalid')
                            $('#invalid_name').html('')
                            $('#invalid_guard_name').html('')
                            $('#edit-role').modal('show')
                        }
                    }
                });
            })
            $('.editRole').on('click', function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ asset('/roles/update') }}",
                    data: {
                        role_id: role_id,
                        name: $('#name').val(),
                        guard_name: $('#guard_name').val(),
                    },
                    method: "post",
                    statusCode: {
                        200: (response) => {
                            toast(response.message)
                            $('#edit-role').modal('hide')
                            window.LaravelDataTables["dataTableBuilder"].ajax.reload();
                        },
                        404: (error) => {
                            if (error.responseJSON.error.name) {
                                $('#name').addClass('is-invalid')
                                $('#invalid_name').html(error.responseJSON.error.name[0])
                            }
                            if (error.responseJSON.error.guard_name) {
                                $('#guard_name').addClass('is-invalid')
                                $('#invalid_guard_name').html(error.responseJSON.error.guard_name[0])
                            }
                        }
                    }
                });
            })

            $('body').on('click', '.showAddModel', function (e) {
                e.preventDefault()
                $('#add_name').val('').removeClass('is-invalid')
                $('#add_guard_name').val('').removeClass('is-invalid')
                $('#add_invalid_name').html('')
                $('#invalid_add_guard_name').html('')
                $('#add-role').modal('show')
            })
            $('.addRole').on('click', function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ asset('/roles/add') }}",
                    data: {
                        name: $('#add_name').val(),
                        guard_name: $('#add_guard_name').val(),
                    },
                    method: "post",
                    statusCode: {
                        200: (response) => {
                            toast(response.message)
                            $('#add-role').modal('hide')
                            window.LaravelDataTables["dataTableBuilder"].ajax.reload();
                        },
                        404: (error) => {
                            if (error.responseJSON.error.name) {
                                $('#add_name').addClass('is-invalid')
                                $('#invalid_add_name').html(error.responseJSON.error.name[0])
                            }
                            if (error.responseJSON.error.guard_name) {
                                $('#add_guard_name').addClass('is-invalid')
                                $('#invalid_add_guard_name').html(error.responseJSON.error.guard_name[0])
                            }
                        }
                    }
                });
            })

            $('body').on('click', '.showPermissionsModel', function (e) {
                e.preventDefault()
                role_id = $(this).data('id')
                $('.roles-permissions').html('')
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ asset('/permissions/getPermissions') }}",
                    data: {
                        role_id: role_id
                    },
                    method: "get",
                    statusCode: {
                        200: (response) => {
                            console.log(response)
                            object = []
                            response.permissions.forEach((item, index) => {
                                object.push({
                                    id: item.id,
                                    name: item.name,
                                    guardName: item.guard_name,
                                    roleId: role_id,
                                    active: (() => {
                                        let active = false
                                        response.role.forEach((role, index) => {
                                            if (role.role_permission_permission_id === item.id) {
                                                active = true
                                            }
                                        })
                                        return active
                                    })()
                                })
                            })
                            let el = document.createElement('div')
                            let input = document.createElement('input')
                            let label = document.createElement('label')
                            el.classList.add('custom-control', 'custom-checkbox', 'col-md-6', 'check-permissions')
                            input.classList.add('custom-control-input')
                            input.type = 'checkbox'
                            label.classList.add('custom-control-label')
                            el.append(input, label)
                            object.forEach((item, index) => {
                                let newel = el.cloneNode(true)
                                newel.children[0].id = `permission-${item.id}`
                                newel.children[0].checked = item.active
                                newel.children[0].addEventListener('change', function () {
                                    item.active = !item.active
                                })
                                newel.children[1].setAttribute('for', `permission-${item.id}`)
                                newel.children[1].innerText = item.name
                                document.querySelector('.roles-permissions').append(newel)
                            })
                        }
                    },
                    complete: function () {
                        $('#add-permissions').modal('show')
                    }
                });
            })
            $('.addPermission').on('click', function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ asset('/permissions/setPermissions') }}",
                    data: {
                        data: object,
                    },
                    method: "post",
                    statusCode: {
                        200: (response) => {
                            toast(response.message)
                            $('#add-permissions').modal('hide')
                            window.LaravelDataTables["dataTableBuilder"].ajax.reload();
                        }
                    }
                });
            })
        })

    </script>
@endpush
