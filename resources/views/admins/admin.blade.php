@extends('admins.master')

@section('title','Admin Management')

@section('admin','active')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Admin Management</h5>
                    <div class="ibox-tools">
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createAdminModal">
                            <i class="fa fa-plus"></i> Add New Admin
                        </button>
                    </div>
                </div>
                <div class="ibox-content">
                    @if(session('msg'))
                        <div class="alert alert-{{ session('msg_type') == 'success' ? 'success' : 'danger' }} alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('msg') }}
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($edit as $admin)
                                <tr>
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ $admin->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $admin->updated_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="editAdmin({{ $admin->id }}, '{{ $admin->email }}')">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                        @if(Session::has('admin') && Session::get('admin')->id != $admin->id)
                                        <a href="{{ route('admins.delete_admin', $admin->id) }}" class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Are you sure you want to delete this admin?')">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                        @else
                                        <button class="btn btn-secondary btn-sm" disabled title="Cannot delete your own account">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Admin Modal -->
<div class="modal fade" id="createAdminModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create New Admin</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admins.create_admin') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="create_email">Email</label>
                        <input type="email" class="form-control" id="create_email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="create_password">Password</label>
                        <input type="password" class="form-control" id="create_password" name="password" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Admin Modal -->
<div class="modal fade" id="editAdminModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Admin</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admins.update_admin') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_password">Password</label>
                        <input type="password" class="form-control" id="edit_password" name="password" required minlength="6">
                    </div>
                    <input type="hidden" id="edit_id" name="id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    // Function to edit admin
    window.editAdmin = function(id, email) {
        $('#edit_id').val(id);
        $('#edit_email').val(email);
        $('#edit_password').val('');
        $('#editAdminModal').modal('show');
    };
    
    // Clear form when create modal is closed
    $('#createAdminModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
    
    // Clear form when edit modal is closed
    $('#editAdminModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
});
</script>
@endpush
