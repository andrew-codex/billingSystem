<link rel="stylesheet" href="{{asset('/CSS_Styles/modalCSS/edit-linemen.css')}}">
@foreach($linemen as $lineman)
<div id="edit-lineman-{{ $lineman->id }}" class="edit-lineman">
    <div class="modal-content">
        <button class="close-btn" onclick="closeEdit({{ $lineman->id }})">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h2 class="modal-title">Edit Line Man</h2>

        <form id="editLinemanForm-{{ $lineman->id }}" method="POST" action="{{route('lineman.update', $lineman->id)}}">
            @csrf
            @method('PUT')

            <div class="form-columns">
                <div class="form-group">
                    <label for="first_name_{{ $lineman->id }}">First Name*</label>
                    <input type="text" name="first_name" id="first_name_{{ $lineman->id }}" value="{{ old('first_name', $lineman->first_name) }}" required>
                </div>
                <div class="form-group">
                    <label for="middle_name_{{ $lineman->id }}">Middle Name</label>
                    <input type="text" name="middle_name" id="middle_name_{{ $lineman->id }}" value="{{ old('middle_name', $lineman->middle_name) }}">
                </div>
                <div class="form-group">
                    <label for="last_name_{{ $lineman->id }}">Last Name*</label>
                    <input type="text" name="last_name" id="last_name_{{ $lineman->id }}" value="{{ old('last_name', $lineman->last_name) }}" required>
                </div>
                <div class="form-group">
                    <label for="suffix_{{ $lineman->id }}">Suffix</label>
                    <select style="width: 85%;" name="suffix" id="suffix_{{ $lineman->id }}" class="select">
                        <option value="">-- None --</option>
                        <option value="Jr." {{ old('suffix', $lineman->suffix) == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                        <option value="Sr." {{ old('suffix', $lineman->suffix) == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                        <option value="II" {{ old('suffix', $lineman->suffix) == 'II' ? 'selected' : '' }}>II</option>
                        <option value="III" {{ old('suffix', $lineman->suffix) == 'III' ? 'selected' : '' }}>III</option>
                        <option value="IV" {{ old('suffix', $lineman->suffix) == 'IV' ? 'selected' : '' }}>IV</option>
                    </select>
                </div>

                
                <div class="form-group">
                    <label for="contact_number{{ $lineman->id }}">Contact</label>
                    <input type="text" name="contact_number" id="contact_number{{ $lineman->id }}" value="{{ old('contact_number', $lineman->contact_number) }}" placeholder="09XXXXXXXXX" pattern="09[0-9]{9}">
                </div>

<div class="form-group">
    <label for="group_id_{{ $lineman->id }}">Group Name</label>
    <select style="width: 85%;" name="group_id" id="group_id_{{ $lineman->id }}" class="select">
        <option value="">-- Select Group --</option>
        @foreach($groups as $group)
            <option value="{{ $group->id }}"
                {{ (string) old('group_id', $lineman->group_id) === (string) $group->id ? 'selected' : '' }}>
                {{ $group->group_name }}
            </option>
        @endforeach
    </select>
    @error('group_id') <small class="text-danger">{{ $message }}</small> @enderror
</div>


        
            </div>

            <h3 class="section-title">Address Information</h3>
            <div class="form-columns">
                
                <div class="form-group">
                        <label>City</label>
                        <select style="width: 85%;" class="select" id="city-{{ $lineman->id }}" name="city_code" class="address-select city-select">
                            <option value="">Choose City</option>
                        </select>
                        <input type="hidden" name="city_name" id="city_name_{{ $lineman->id }}">
                </div>

                <div class="form-group">
                    <label>Barangay</label>
                        <select style="width: 85%;" class="select" id="barangay-{{ $lineman->id }}" name="barangay_code" class="address-select barangay-select">
                            <option value="">Choose Barangay</option>
                        </select>
                        <input type="hidden" name="barangay_name" id="barangay_name_{{ $lineman->id }}">
                </div>

                <div class="form-group">
                    <label for="street_{{ $lineman->id }}">Street / Prk</label>
                    <input type="text" name="street" id="street_{{ $lineman->id }}" value="{{ old('street', $lineman->street) }}">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Save Changes</button>

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

        @foreach($linemen as $lineman)
        (function() {
            const id = "{{ $lineman->id }}";

            const $city = $(`#city-${id}`);
            const $barangay = $(`#barangay-${id}`);

            const $cityName = $(`#city_name_${id}`);
            const $barangayName = $(`#barangay_name_${id}`);

     
            let selectedCity = "{{ $lineman->city_code }}";
            let selectedBarangay = "{{ $lineman->barangay_code }}";

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





