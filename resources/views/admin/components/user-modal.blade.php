<!-- User View Modal -->
<div class="modal fade" id="userViewModal" tabindex="-1" aria-labelledby="userViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="userViewModalLabel">User Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar-circle mx-auto mb-3">
                        <i class="bi bi-person-circle" style="font-size: 4rem; color: #4e73df;"></i>
                    </div>
                    <h5 class="user-name mb-0">User Name</h5>
                    <p class="user-email text-muted">user@example.com</p>
                    <div class="user-role mb-2">
                        <span class="badge bg-primary">Role</span>
                    </div>
                </div>
                
                <div class="user-details">
                    <div class="row mb-3">
                        <div class="col-5 text-muted">ID:</div>
                        <div class="col-7 user-id fw-bold">1</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5 text-muted">Joined:</div>
                        <div class="col-7 user-joined">Jan 1, 2023</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5 text-muted">Last Login:</div>
                        <div class="col-7 user-last-login">Jan 1, 2023</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-5 text-muted">Status:</div>
                        <div class="col-7 user-status">
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary edit-user-btn" data-user-id="">Edit User</button>
            </div>
        </div>
    </div>
</div>

<!-- User Edit Modal -->
<div class="modal fade" id="userEditModal" tabindex="-1" aria-labelledby="userEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="userEditModalLabel">Edit User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit-email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-role" class="form-label">Role</label>
                        <select class="form-select" id="edit-role" name="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-password" class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" id="edit-password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="edit-password-confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="edit-password-confirmation" name="password_confirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Delete Confirmation Modal -->
<div class="modal fade" id="userDeleteModal" tabindex="-1" aria-labelledby="userDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="userDeleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                <p class="fw-bold user-delete-name"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteUserForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>
