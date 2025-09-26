<link rel="stylesheet" href="{{asset('/CSS_Styles/modalCSS/edit-linemen.css')}}">
@foreach($linemen as $lineman)
<div id="edit-lineman-{{ $lineman->id }}" class="edit-lineman">
    <div class="modal-content">
        <button class="close-btn" onclick="closeEdit({{ $lineman->id }})">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h2 class="modal-title">Edit Line Man</h2>

        <form id="editLinemanForm-{{ $lineman->id }}" method="POST" action="">
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
                    <select name="suffix" id="suffix_{{ $lineman->id }}" class="select">
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
                    <input type="text" name="contact" id="contact_number{{ $lineman->id }}" value="{{ old('contact_number', $lineman->contact_number) }}" placeholder="09XXXXXXXXX" pattern="09[0-9]{9}">
                </div>
            </div>

            <h3 class="section-title">Address Information</h3>
            <div class="form-columns">
<select name="region_code" id="region_{{ $lineman->id }}" class="region-select select">
    <option value="">-- Select Region --</option>
    @foreach($regions as $region)
        <option value="{{ $region->code }}" 
            {{ $lineman->region_code == $region->code ? 'selected' : '' }}>
            {{ $region->name }}
        </option>
    @endforeach
</select>


<select name="province_code" id="province_{{ $lineman->id }}" class="select">
    <option value="{{ $lineman->province_code }}" selected>
        {{ $provinceName ?? '-- Select Province --' }}
    </option>
</select>


<select name="city_code" id="city_{{ $lineman->id }}" class="select">
    <option value="{{ $lineman->city_code }}" selected>
        {{ $cityName ?? '-- Select City --' }}
    </option>
</select>


<select name="barangay_code" id="barangay_{{ $lineman->id }}" class="select">
    <option value="{{ $lineman->barangay_code }}" selected>
        {{ $barangayName ?? '-- Select Barangay --' }}
    </option>
</select>
                <div class="form-group">
                    <label for="street_{{ $lineman->id }}">Street / Prk</label>
                    <input type="text" name="street" id="street_{{ $lineman->id }}" value="{{ old('street', $lineman->street) }}">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Save Changes</button>
                <button type="button" class="cancel-btn" onclick="closeEdit({{ $lineman->id }})">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endforeach






<script>
document.addEventListener("DOMContentLoaded", function() {
    function fetchOptions(url, target, selected = null) {
        fetch(url)
            .then(res => res.json())
            .then(data => {
                let options = `<option value="">-- Select --</option>`;
                data.forEach(item => {
                    options += `<option value="${item.code}" ${item.code == selected ? 'selected' : ''}>${item.name}</option>`;
                });
                document.getElementById(target).innerHTML = options;
            });
    }


    document.getElementById('region').addEventListener('change', function() {
        fetchOptions(`/psgc/provinces?region_code=${this.value}`, 'province');
        document.getElementById('city').innerHTML = `<option value="">-- Select City --</option>`;
        document.getElementById('barangay').innerHTML = `<option value="">-- Select Barangay --</option>`;
    });

   
    document.getElementById('province').addEventListener('change', function() {
        fetchOptions(`/psgc/cities?province_code=${this.value}`, 'city');
        document.getElementById('barangay').innerHTML = `<option value="">-- Select Barangay --</option>`;
    });


    document.getElementById('city').addEventListener('change', function() {
        fetchOptions(`/psgc/barangays?city_code=${this.value}`, 'barangay');
    });
});
</script>





