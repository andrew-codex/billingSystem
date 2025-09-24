<link rel="stylesheet" href="{{ asset('/CSS_Styles/modalCSS/edit-staff.css') }}">
  <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">

  
<div class="edit-staff" id="editStaffModal">
    <div class="modal-content">
        <button class="close-btn" onclick="closeEditModal()"><i class="fa-solid fa-xmark"></i></button>

        <h2 class="modal-title">Edit Staff</h2>

        <form id="editStaffForm" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="form-columns">
                <div class="form-column">
                    <div class="form-group">
                        <label for="edit_name">Name</label>
                        <input type="text" name="name" id="edit_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" name="email" id="edit_email" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_phone">Phone</label>
                        <input type="text" name="phone" id="edit_phone">
                    </div>
                </div>

                <div class="form-column">
                    <div class="form-group">
                        <label for="edit_address">Address</label>
                        <input type="text" name="address" id="edit_address">
                    </div>
                    <div class="form-group">
                        <label for="edit_password">Password</label>
                        <input type="password" name="password" id="edit_password" placeholder="Leave blank to keep current password">
                    </div>
                    <div class="form-group">
                        <label for="edit_role">Role</label>
                        <select name="role" id="edit_role" required>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                           
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions">
            
                <button type="submit">Update Staff</button>
            </div>
        </form>
    </div>
</div>





