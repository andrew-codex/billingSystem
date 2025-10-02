<link rel="stylesheet" href="{{ asset('/CSS_Styles/modalCSS/add-staff.css') }}">
  <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
<div class="add-staff" id="addStaffModal">
    <div class="modal-content">
        <button class="close-btn" onclick="closedAddStaff()"><i class="fa-solid fa-xmark"></i></button>
        <h2 class="modal-title">Create a Staff</h2>

        <div class="modal-body">
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

            
                <div class="form-section">
                    <h3 class="section-title"> Staff Information</h3>
                    <div class="form-columns">
                        <div class="form-column">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" placeholder="Enter first name" 
                                    value="{{ old('first_name') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" placeholder="Enter last name" 
                                    value="{{ old('last_name') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" name="middle_name" placeholder="Enter middle name" 
                                    value="{{ old('middle_name') }}">
                            </div>

                            <div class="form-group">
                                <label for="suffix">Suffix</label>
                                <select name="suffix" class="select">
                                    <option value="">-- None --</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-column">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" placeholder="Enter email"
                                    class="@error('email') invalid @enderror" value="{{ old('email') }}" required>
                                <small class="error-message">@error('email') {{ $message }} @enderror</small>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" placeholder="09XXXXXXXXX" 
                                    value="{{ old('phone') }}" class="@error('phone') invalid @enderror" required>
                                @error('phone')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" class="@error('role') invalid @enderror">
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                </select>
                                @error('role')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

             
                <div class="form-section">
                    <h3 class="section-title"> Address Information</h3>
                    <div class="form-columns">
                        <div class="form-column">
                            <div class="form-group">
                                <label for="city">City/Municipality</label>
                                <select class="select" id="city" name="city_code"></select>
                                <input type="hidden" id="city-text" name="city_name">
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-group">
                                <label for="barangay">Barangay</label>
                                <select class="select" id="barangay" name="barangay_code"></select>
                                <input type="hidden" id="barangay-text" name="barangay_name">
                            </div>
                        </div>
                    </div>
                </div>

              
                <div class="form-actions">
                    <button type="submit">Add Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>

document.addEventListener("DOMContentLoaded", function () {
    let cityDropdown = document.querySelector("#city");
    let barangayDropdown = document.querySelector("#barangay");


    function resetDropdown(dropdown, placeholder) {
        dropdown.innerHTML = `<option selected disabled>${placeholder}</option>`;
    }


    resetDropdown(cityDropdown, "Choose City/Municipality");
    resetDropdown(barangayDropdown, "Choose Barangay");

    fetch("/json/city.json")
        .then(res => res.json())
        .then(data => {
           
            let cities = data.filter(c => c.province_code === "1263");

            cities.sort((a, b) => a.city_name.localeCompare(b.city_name));

            cities.forEach(city => {
                let option = document.createElement("option");
                option.value = city.city_code;
                option.textContent = city.city_name;
                cityDropdown.appendChild(option);
            });
        });


    cityDropdown.addEventListener("change", function () {
        let cityCode = this.value;
        let cityName = this.options[this.selectedIndex].text;
        document.querySelector("#city-text").value = cityName;

        resetDropdown(barangayDropdown, "Choose Barangay");

        fetch("/json/barangay.json")
            .then(res => res.json())
            .then(data => {
                let barangays = data.filter(b => b.city_code === cityCode);

                barangays.sort((a, b) => a.brgy_name.localeCompare(b.brgy_name));

                barangays.forEach(brgy => {
                    let option = document.createElement("option");
                    option.value = brgy.brgy_code;
                    option.textContent = brgy.brgy_name;
                    barangayDropdown.appendChild(option);
                });
            });
    });

    // Save Barangay name
    barangayDropdown.addEventListener("change", function () {
        document.querySelector("#barangay-text").value = 
            this.options[this.selectedIndex].text;
    });
});



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
