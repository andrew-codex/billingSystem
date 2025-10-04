
<link rel="stylesheet" href="{{ asset('/CSS_Styles/modalCSS/edit-staff.css') }}">
<link rel="stylesheet" href="{{ asset('/CSS_Styles/mainCss/base.css') }}">
@foreach($users as $user)
<div class="edit-staff" id="edit-staff-{{ $user->id }}">
    <div class="modal-content">
        <button class="close-btn" onclick="closeEditStaff({{ $user->id }})">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h2 class="modal-title">Edit Staff</h2>

        <div class="modal-body">
            <form id="editStaffForm-{{ $user->id }}" method="POST" action="{{ route('staff.update', $user->id) }}">
                @csrf
                @method('PUT')

    
                <div class="form-section">
                    <h3 class="section-title">Staff Information</h3>
                    <div class="form-columns">
                        <div class="form-column">
                            <div class="form-group">
                                <label for="first_name_{{ $user->id }}">First Name</label>
                                <input type="text" name="first_name" id="first_name_{{ $user->id }}"
                                    value="{{ old('first_name', $user->first_name) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="last_name_{{ $user->id }}">Last Name</label>
                                <input type="text" name="last_name" id="last_name_{{ $user->id }}"
                                    value="{{ old('last_name', $user->last_name) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="middle_name_{{ $user->id }}">Middle Name</label>
                                <input type="text" name="middle_name" id="middle_name_{{ $user->id }}"
                                    value="{{ old('middle_name', $user->middle_name) }}">
                            </div>

                            <div class="form-group">
                                <label for="suffix_{{ $user->id }}">Suffix</label>
                                <select name="suffix" id="suffix_{{ $user->id }}" class="select">
                                    <option value="">-- None --</option>
                                    <option value="Jr." {{ old('suffix', $user->suffix) == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                    <option value="Sr." {{ old('suffix', $user->suffix) == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                    <option value="II" {{ old('suffix', $user->suffix) == 'II' ? 'selected' : '' }}>II</option>
                                    <option value="III" {{ old('suffix', $user->suffix) == 'III' ? 'selected' : '' }}>III</option>
                                    <option value="IV" {{ old('suffix', $user->suffix) == 'IV' ? 'selected' : '' }}>IV</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-column">
                            <div class="form-group">
                                <label for="email_{{ $user->id }}">Email</label>
                                <input type="email" name="email" id="email_{{ $user->id }}"
                                    value="{{ old('email', $user->email) }}" required>
                     <small id="email-error-{{ $user->id }}" class="error-message">
                        @error('email') {{ $message }} @enderror
                    </small>

                            </div>

                            <div class="form-group">
                                <label for="phone_{{ $user->id }}">Phone</label>
                                <input type="text" name="phone" id="phone_{{ $user->id }}"
                                    value="{{ old('phone', $user->phone) }}">
<small id="phone-error-{{ $user->id }}" class="error-message">
    @error('phone') {{ $message }} @enderror
</small>

                            </div>

                            <div class="form-group">
                                <label for="role_{{ $user->id }}">Role</label>
                                <select name="role" id="role_{{ $user->id }}" required>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

           
                <div class="form-section">
                    <h3 class="section-title">Address Information</h3>
                    <div class="form-columns">
                        <div class="form-column">
                            <div class="form-group">
                                <label for="city-{{ $user->id }}">City/Municipality</label>
                                <select name="city_code" id="city-{{ $user->id }}" class="select">
                                    <option value="">Choose City</option>
                                </select>
                                <input type="hidden" name="city_name" id="city_name_{{ $user->id }}">
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-group">
                                <label for="barangay-{{ $user->id }}">Barangay</label>
                                <select name="barangay_code" id="barangay-{{ $user->id }}" class="select">
                                    <option value="">Choose Barangay</option>
                                </select>
                                <input type="hidden" name="barangay_name" id="barangay_name_{{ $user->id }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit">Update Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endforeach
<script>


document.addEventListener("DOMContentLoaded", function () {

    let cities = [], barangays = [];

    $.getJSON('/json/city.json', data => { cities = data; initialize(); });
    $.getJSON('/json/barangay.json', data => { barangays = data; initialize(); });

    function initialize() {
        if (!cities.length || !barangays.length) return;

        @foreach($users as $user)
        (function() {
            const id = "{{ $user->id }}";

            const $city = $(`#city-${id}`);
            const $barangay = $(`#barangay-${id}`);

            const $cityName = $(`#city_name_${id}`);
            const $barangayName = $(`#barangay_name_${id}`);

     
            let selectedCity = "{{ $user->city_code }}";
            let selectedBarangay = "{{ $user->barangay_code }}";

            function createOption(value, text, isSelected) {
                return `<option value="${value}" ${isSelected ? 'selected' : ''}>${text}</option>`;
            }

         
            $city.empty().append(createOption('', 'Choose City/Municipality', false));
            const scCities = cities.filter(c => c.province_code === "1263"); 
            scCities.sort((a, b) => a.city_name.localeCompare(b.city_name));

            scCities.forEach(c => {
                const isSelected = c.city_code == selectedCity;
                $city.append(createOption(c.city_code, c.city_name, isSelected));
                if (isSelected) $cityName.val(c.city_name);
            });

        
            function fillBarangays(cityCode) {
                $barangay.empty().append(createOption('', 'Choose Barangay', false));
                const filtered = barangays.filter(b => b.city_code === cityCode);
                filtered.sort((a, b) => a.brgy_name.localeCompare(b.brgy_name));

                filtered.forEach(b => {
                    const isSelected = b.brgy_code == selectedBarangay;
                    $barangay.append(createOption(b.brgy_code, b.brgy_name, isSelected));
                    if (isSelected) $barangayName.val(b.brgy_name);
                });
            }

            if (selectedCity) fillBarangays(selectedCity);

          
            $city.on('change', function() {
                const code = $(this).val();
                const name = $(this).find('option:selected').text();
                $cityName.val(name);
                fillBarangays(code);
            });

            $barangay.on('change', function() {
                const name = $(this).find('option:selected').text();
                $barangayName.val(name);
            });

        })();
        @endforeach
    }
});


document.addEventListener("DOMContentLoaded", () => {
    const editForms = document.querySelectorAll("[id^='editStaffForm-']");

    editForms.forEach(form => {
        const formId = form.id.split('-')[1];
        const emailInput = document.getElementById(`email_${formId}`);
        const phoneInput = document.getElementById(`phone_${formId}`);
        const emailError = document.getElementById(`email-error-${formId}`);
        const phoneError = document.getElementById(`phone-error-${formId}`);

        function showError(input, errorElement, message) {
            input.classList.add("invalid");
            errorElement.textContent = message;
            errorElement.style.color = "red";
        }

        function clearError(input, errorElement) {
            input.classList.remove("invalid");
            errorElement.textContent = "";
        }

        function validatePhone() {
            const value = phoneInput.value.trim();
            phoneInput.value = value.replace(/\D/g, "").slice(0, 11);

            if (phoneInput.value === "") {
                clearError(phoneInput, phoneError);
                return true; 
            }

            const pattern = /^[0-9]{10,11}$/;
            if (!pattern.test(phoneInput.value)) {
                showError(phoneInput, phoneError, "Must be 09XXXXXXXXX.");
                return false;
            }

            clearError(phoneInput, phoneError);
            return true;
        }

        let emailTimeout;
        async function validateEmail() {
            const value = emailInput.value.trim();
            clearError(emailInput, emailError);

            if (value === "") {
                showError(emailInput, emailError, "Email is required.");
                return false;
            }

            const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!pattern.test(value)) {
                showError(emailInput, emailError, "Please enter a valid email address.");
                return false;
            }

            if (emailTimeout) clearTimeout(emailTimeout);

            return new Promise(resolve => {
                emailTimeout = setTimeout(async () => {
                    try {
                        const res = await fetch(`/staff/check-email?email=${encodeURIComponent(value)}&user_id=${formId}`);
                        const data = await res.json();
                        if (!data.available) {
                            showError(emailInput, emailError, "Email already exists.");
                            resolve(false);
                        } else {
                            clearError(emailInput, emailError);
                            resolve(true);
                        }
                    } catch {
                        showError(emailInput, emailError, "Error checking email.");
                        resolve(false);
                    }
                }, 300); 
            });
        }

        emailInput.addEventListener("input", validateEmail);
        phoneInput.addEventListener("input", validatePhone);

        form.addEventListener("submit", async e => {
            e.preventDefault();
            const emailValid = await validateEmail();
            const phoneValid = validatePhone();

            if (emailValid && phoneValid) {
                form.submit();
            }
        });
    });
});



</script>
