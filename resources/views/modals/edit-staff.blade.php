<!-- edit-staff.blade.php -->
<link rel="stylesheet" href="{{ asset('/CSS_Styles/modalCSS/edit-staff.css') }}">
<link rel="stylesheet" href="{{ asset('/CSS_Styles/mainCss/base.css') }}">
@foreach($users as $user)
<div class="edit-staff" id="edit-staff-{{ $user->id }}">
    <div class="modal-content">
        <button class="close-btn" onclick="closeEditStaff({{ $user->id }})"><i class="fa-solid fa-xmark"></i></button>

        <h2 class="modal-title">Edit Staff</h2>

        <form id="editStaffForm" method="POST" action="{{route('staff.update', $user->id)}}">
            @csrf
            @method('PUT')

            <div class="form-columns">
                <div class="form-column">

                <div class="form-group">
                    <label for="name_{{ $user->id }}">Name</label>
                    <input type="text" name="name" id="name_{{ $user->id }}" value="{{ old('name', $user->name) }}" required>
                </div>

                    <div class="form-group">
                        <label for="email_{{ $user->id }}">Email</label>
                        <input type="email" name="email" id="email_{{ $user->id }}" value="{{ old('email', $user->email) }}"   required>
                    </div>

                    <div class="form-group">
                        <label for="phone_{{ $user->id }}">Phone</label>
                        <input type="text" name="phone" id="phone_{{ $user->id}}" value="{{ old('phone', $user->phone) }}" >
                    </div>
                </div>

                <div class="form-column">

                     <div class="form-group">
                        <label>City</label>
                        <select class="select" id="city-{{ $user->id }}" name="city_code" class="address-select city-select">
                            <option value="">Choose City</option>
                        </select>
                        <input type="hidden" name="city_name" id="city_name_{{ $user->id }}">
                     </div>

                <div class="form-group">
                    <label>Barangay</label>
                        <select class="select" id="barangay-{{ $user->id }}" name="barangay_code" class="address-select barangay-select">
                            <option value="">Choose Barangay</option>
                        </select>
                        <input type="hidden" name="barangay_name" id="barangay_name_{{ $user->id }}">
                </div>

                    <div class="form-group">
                        <label for="edit_password">Password</label>
                        <input type="password" name="password" id="edit_password" placeholder="Leave blank to keep current password">
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

            <div class="form-actions">
                <button type="submit">Update Staff</button>
            </div>
        </form>
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


</script>
