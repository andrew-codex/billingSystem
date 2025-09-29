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
                            <label for="city">City/Municipality</label>
                            <select class="select" id="city" name="city_code"></select>
                            <input type="hidden" id="city-text" name="city_name">
                        </div>



                         <div class="form-group">
                            <label for="barangay">Barangay</label>
                            <select class="select" id="barangay" name="barangay_code"></select>
                            <input type="hidden" id="barangay-text" name="barangay_name">
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

document.addEventListener("DOMContentLoaded", function () {
    let cityDropdown = document.querySelector("#city");
    let barangayDropdown = document.querySelector("#barangay");

    // Clear + set default option
    function resetDropdown(dropdown, placeholder) {
        dropdown.innerHTML = `<option selected disabled>${placeholder}</option>`;
    }

    // Load South Cotabato Cities
    resetDropdown(cityDropdown, "Choose City/Municipality");
    resetDropdown(barangayDropdown, "Choose Barangay");

    fetch("/json/city.json")
        .then(res => res.json())
        .then(data => {
            // South Cotabato province_code = 1263 (check province.json)
            let cities = data.filter(c => c.province_code === "1263");

            cities.sort((a, b) => a.city_name.localeCompare(b.city_name));

            cities.forEach(city => {
                let option = document.createElement("option");
                option.value = city.city_code;
                option.textContent = city.city_name;
                cityDropdown.appendChild(option);
            });
        });

    // Load Barangays when City changes
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
