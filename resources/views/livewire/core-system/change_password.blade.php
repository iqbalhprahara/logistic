<div>
    <div wire:ignore.self class="modal fade change-password" tabindex="-1" role="dialog" aria-labelledby="change-password-modal-label" aria-hidden="true" id="change-password-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="change-password-modal-label">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="changePassword">
                        <div class="mb-3 position-relative">
                            <label for="current_password">Current Password</label>
                            <input type="password"
                                class="form-control @error('currentPassword') is-invalid @enderror"
                                wire:model.blur="currentPassword" autocomplete="currentPassword"
                                placeholder="Enter Current Password">
                            @error('currentPassword')
                            <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 position-relative">
                            <label for="newpassword">New Password</label>
                            <input type="password"
                                class="form-control @error('newPassword') is-invalid @enderror"
                                wire:model.blur="newPassword" autocomplete="newPassword" placeholder="Enter New Password">
                            @error('newPassword')
                            <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 position-relative">
                            <label for="userpassword">Confirm Password</label>
                            <input type="password" class="form-control"
                                wire:model.blur="newPasswordConfirmation" autocomplete="newPassword" placeholder="Enter New Confirm password">
                            @error('newPasswordConfirmation')
                            <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-3 d-grid">
                            <button class="btn btn-primary waves-effect waves-light" type="submit">Update Password</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
