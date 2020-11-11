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

                        {!! $dataTable->table(['class' => 'table table-bordered table-hover userDataTable','style' => 'width:100%']) !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="delete-user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        Are you sure you want to delete <span class="username font-weight-bold"></span> ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger deleteUser">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="edit-user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                            <label for="name">name</label>
                            <input placeholder="Enter name" type="text" class="form-control" id="name">
                            <span class="invalid-feedback" role="alert">
                                <strong id="invalid_name"></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input placeholder="Enter Username" type="text" class="form-control" id="username">
                            <span class="invalid-feedback" role="alert">
                                <strong id="invalid_username"></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input placeholder="Enter email" type="text" class="form-control" id="email">
                            <span class="invalid-feedback" role="alert">
                                <strong id="invalid_email"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success editUser">Edit</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- add-roles -->
        <div class="modal fade" id="add-roles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Roles</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row roles-permissions m-0"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success addRole">Add</button>
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
            let user_id
            let object

            function toast(message) {
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
                user_id = $(this).data('id')
                let name = $(this).data('name')
                $('.username').html(name)
            })
            $('.deleteUser').on('click', function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ asset('/user/delete') }}" + '?id=' + user_id,
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
                            $('#delete-user').modal('hide')
                            window.LaravelDataTables["user-table"].ajax.reload();
                        }
                    }
                });
            })

            $('body').on('click', '.showEditUser', function (e) {
                e.preventDefault()
                user_id = $(this).data('id')
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ asset('/user/show') }}",
                    data: {
                        user_id: user_id
                    },
                    method: "post",
                    statusCode: {
                        200: (response) => {
                            $('#name').val(response.user.name).removeClass('is-invalid')
                            $('#username').val(response.user.username).removeClass('is-invalid')
                            $('#email').val(response.user.email).removeClass('is-invalid')
                            $('#invalid_name').html('')
                            $('#invalid_username').html('')
                            $('#invalid_email').html('')
                            $('#edit-user').modal('show')
                        }
                    }
                });
            })
            $('.editUser').on('click', function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ asset('/user/update') }}",
                    data: {
                        user_id: user_id,
                        name: $('#name').val(),
                        username: $('#username').val(),
                        email: $('#email').val(),
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
                            $('#edit-user').modal('hide')
                            window.LaravelDataTables["user-table"].ajax.reload();
                        },
                        404: (error) => {
                            if (error.responseJSON.error.name) {
                                $('#name').addClass('is-invalid')
                                $('#invalid_name').html(error.responseJSON.error.name[0])
                            }
                            if (error.responseJSON.error.username) {
                                $('#username').addClass('is-invalid')
                                $('#invalid_username').html(error.responseJSON.error.username[0])
                            }
                            if (error.responseJSON.error.email) {
                                $('#email').addClass('is-invalid')
                                $('#invalid_email').html(error.responseJSON.error.email[0])
                            }
                        }
                    }
                });
            })

            $('body').on('click', '.showRolesModel', function (e) {
                e.preventDefault()
                user_id = $(this).data('id')
                $('.roles-permissions').html('')
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ asset('/roles/getRoles') }}",
                    data: {
                        user_id: user_id
                    },
                    method: "get",
                    statusCode: {
                        200: (response) => {
                            console.log(response)
                            object = []
                            response.roles.forEach((item, index) => {
                                object.push({
                                    id: item.id,
                                    userId: user_id,
                                    name: item.name,
                                    guardName: item.guard_name,
                                    active: (() => {
                                        let active = false
                                        response.model.forEach((role, index) => {
                                            if (role.role_id === item.id) {
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
                            el.classList.add('custom-control', 'custom-radio', 'col-md-6')
                            input.classList.add('custom-control-input')
                            input.type = 'radio'
                            label.classList.add('custom-control-label')
                            el.append(input, label)
                            object.forEach((item, index) => {
                                let newel = el.cloneNode(true)
                                newel.children[0].id = `role-${item.id}`
                                newel.children[0].checked = item.active
                                newel.children[0].name = 'roles'
                                newel.children[0].addEventListener('change', function () {
                                    object.forEach((role, index) => {
                                        role.active = false
                                    })
                                    item.active = !item.active
                                    console.log(object)
                                })
                                newel.children[1].setAttribute('for', `role-${item.id}`)
                                newel.children[1].innerText = item.name
                                document.querySelector('.roles-permissions').append(newel)
                            })
                        }
                    },
                    complete: function () {
                        $('#add-roles').modal('show')
                    }
                });
            })
            $('.addRole').on('click', function () {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ asset('/roles/setRoles') }}",
                    data: {
                        data: object,
                    },
                    method: "post",
                    statusCode: {
                        200: (response) => {
                            toast(response.message)
                            $('#add-roles').modal('hide')
                            window.LaravelDataTables["user-table"].ajax.reload();
                        }
                    }
                });
            })
        })

    </script>
@endpush
