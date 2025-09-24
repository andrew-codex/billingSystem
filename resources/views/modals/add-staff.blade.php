<link rel="stylesheet" href="{{ asset('/CSS_Styles/modalCSS/add-staff.css') }}">
  <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
<div class="add-staff" id="addStaffModal">
    <div class="modal-content">
        <button class="close-btn" onclick="closedAddStaff()"><i class="fa-solid fa-xmark"></i></button>
        <h2 class="modal-title">Create a Staff</h2>

        <form id="staffForm" action="{{ route('staff.store') }}" method="POST">
            @csrf
            @method('POST')

            @if ($errors->any())
                <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        openAddStaff(false);
                    });
                </script>
            @endif

            <div class="form-columns">
                <div class="form-column">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" placeholder="Enter name" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="staff-email">Email</label>
                        <input type="email" id="staff-email" name="email" placeholder="Enter email" required
                            class="@error('email') invalid @enderror" value="{{ old('email') }}">
                        <small id="email-error" class="error-message">
                            @error('email') {{ $message }} @enderror
                        </small>
                    </div>


                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone"  placeholder="09XXXXXXXXX" value="{{ old('phone') }}" 
                            class="@error('phone') invalid @enderror" required>
                        @error('phone')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-column">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" name="address" placeholder="Enter address" id="address" value="{{ old('address') }}" 
                            class="@error('address') invalid @enderror" required>
                        @error('address')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

<div class="form-group password-wrapper">
    <label for="password">Password</label>
    <div class="input-container">
        <input 
            type="password" 
            name="password" 
            id="password" 
            placeholder="Enter password" 
            required
            class="@error('password') invalid @enderror password-input"
            oninput="toggleEyeVisibility()"
        >
        <i 
            id="eyeIcon" 
            class="fa-solid fa-eye-slash eye-icon" 
            onclick="togglePassword()"
        ></i>
    </div>
    @error('password')
        <p class="error-message">{{ $message }}</p>
    @enderror 
</div>


                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="@error('role') invalid @enderror">
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                            
                        </select>
                        @error('role')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-actions">
               
                <button type="submit">Add Staff</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleEyeVisibility() {
    const passwordInput = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");

    if (passwordInput.value.trim() !== "") {
        eyeIcon.style.display = "block";
    } else {
        eyeIcon.style.display = "none";
        passwordInput.type = "password"; 
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    }
}

function togglePassword() {
    const passwordInput = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    } else {
        passwordInput.type = "password";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    }
}
</script>

<script src="{{asset('/JsFiles/modalJS/add-staff.js')}}"></script>
