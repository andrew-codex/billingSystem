  <link rel="stylesheet" href="{{asset('/CSS_Styles/modalCSS/edit-consumer.css')}}">
  

  @foreach($consumers as $consumer)
<div id="edit-consumer-{{ $consumer->id }}" class="edit-consumer hidden">
    <div class="modal-content">
        <button class="close-btn" onclick="closeEditConsumer({{ $consumer->id }})">
            <i class="fa-solid fa-xmark"></i>
        </button>
        
        <h2 class="modal-title">Edit Consumer</h2>

        <form id="editConsumerForm-{{ $consumer->id }}" action="{{ route('consumer.update', $consumer->id) }}" method="POST">
            @csrf
            @method('PUT')

           
            <div class="form-columns">
                <div class="form-group">
                    <label for="first_name_{{ $consumer->id }}">First Name</label>
                    <input type="text" name="first_name" id="first_name_{{ $consumer->id }}"
                           value="{{ old('first_name', $consumer->first_name) }}" required>
                </div>

                <div class="form-group">
                    <label for="last_name_{{ $consumer->id }}">Last Name</label>
                    <input type="text" name="last_name" id="last_name_{{ $consumer->id }}"
                           value="{{ old('last_name', $consumer->last_name) }}" required>
                </div>

                <div class="form-group">
                    <label for="middle_name_{{ $consumer->id }}">Middle Name</label>
                    <input type="text" name="middle_name" id="middle_name_{{ $consumer->id }}"
                           value="{{ old('middle_name', $consumer->middle_name) }}" required>
                </div>

                <div class="form-group">
                    <label for="suffix_{{ $consumer->id }}">Suffix</label>
                    <select name="suffix" id="suffix_{{ $consumer->id }}" class="select">
                        <option value="">-- None --</option>
                        <option value="Jr." {{ old('suffix', $consumer->suffix) == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                        <option value="Sr." {{ old('suffix', $consumer->suffix) == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                        <option value="II" {{ old('suffix', $consumer->suffix) == 'II' ? 'selected' : '' }}>II</option>
                        <option value="III" {{ old('suffix', $consumer->suffix) == 'III' ? 'selected' : '' }}>III</option>
                        <option value="IV" {{ old('suffix', $consumer->suffix) == 'IV' ? 'selected' : '' }}>IV</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="phone_{{ $consumer->id }}">Phone Number</label>
                    <input type="text" name="phone" id="phone_{{ $consumer->id }}"
                           value="{{ old('phone', $consumer->phone) }}">
                    <small class="text-danger" id="error_phone_{{ $consumer->id }}"></small>
                </div>

                <div class="form-group">
                    <label for="email_{{ $consumer->id }}">Email Address</label>
                    <input type="email" name="email" id="email_{{ $consumer->id }}"
                           value="{{ old('email', $consumer->email) }}" required>
                    <small class="text-danger" id="error_email_{{ $consumer->id }}"></small>
                </div>
            </div>

          
            <div class="address-section">
                <h3 class="section-title">Address Information</h3>
                <div class="form-columns">



        <select id="region-{{ $consumer->id }}" name="region_code" class="address-select region-select">
            <option value="">Loading Regions...</option>
        </select>
        <input type="hidden" name="region_name" id="region_name_{{ $consumer->id }}">

        <select id="province-{{ $consumer->id }}" name="province_code" class="address-select province-select">
            <option value="">Choose Province</option>
        </select>
        <input type="hidden" name="province_name" id="province_name_{{ $consumer->id }}">

        <select id="city-{{ $consumer->id }}" name="city_code" class="address-select city-select">
            <option value="">Choose City</option>
        </select>
        <input type="hidden" name="city_name" id="city_name_{{ $consumer->id }}">

        <select id="barangay-{{ $consumer->id }}" name="barangay_code" class="address-select barangay-select">
            <option value="">Choose Barangay</option>
        </select>
        <input type="hidden" name="barangay_name" id="barangay_name_{{ $consumer->id }}">



                    
                    <div class="form-group">
                        <label for="street_{{ $consumer->id }}">Street</label>
                        <input type="text" name="street" id="street_{{ $consumer->id }}"
                               value="{{ old('street', $consumer->street) }}" required>
                    </div>
                </div>
            </div>

        
            <div class="other-info">
               <h3 class="section-title">Electric Meters Other Info</h3>
                <div class="form-group">
                    
                    <label for="installation_date_{{ $consumer->id }}">Installation Date</label>
                    <input  style="width:400px;" type="date" name="installation_date" id="installation_date_{{ $consumer->id }}"
                           value="{{ old('installation_date', $consumer->installation_date ? \Carbon\Carbon::parse($consumer->installation_date)->format('Y-m-d') : '') }}">
                </div>

                <div class="form-group">
                    <label for="house_type_{{ $consumer->id }}">House Type</label>
                    <select style="width:400px;" name="house_type" id="house_type_{{ $consumer->id }}">
                        <option value="">-- Select --</option>
                        <option value="residential" {{ old('house_type', $consumer->house_type) == 'residential' ? 'selected' : '' }}>Residential</option>
                        <option value="commercial" {{ old('house_type', $consumer->house_type) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                        <option value="industrial" {{ old('house_type', $consumer->house_type) == 'industrial' ? 'selected' : '' }}>Industrial</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Consumer</button>
            </div>
        </form>
    </div>
</div>

@endforeach

<script src="{{ asset('/JsFiles/ph-address-selector.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Load all JSON data once
    let regions = [], provinces = [], cities = [], barangays = [];
    $.getJSON('/json/region.json', data => { regions = data; initialize(); });
    $.getJSON('/json/province.json', data => { provinces = data; initialize(); });
    $.getJSON('/json/city.json', data => { cities = data; initialize(); });
    $.getJSON('/json/barangay.json', data => { barangays = data; initialize(); });

    function initialize() {
        if (!regions.length || !provinces.length || !cities.length || !barangays.length) return;

        // Loop over all consumers
        @foreach($consumers as $consumer)
        (function() {
            const id = "{{ $consumer->id }}";

            const $region = $(`#region-${id}`);
            const $province = $(`#province-${id}`);
            const $city = $(`#city-${id}`);
            const $barangay = $(`#barangay-${id}`);

            const $regionName = $(`#region_name_${id}`);
            const $provinceName = $(`#province_name_${id}`);
            const $cityName = $(`#city_name_${id}`);
            const $barangayName = $(`#barangay_name_${id}`);

            // Saved codes from DB
            let selectedRegion = "{{ $consumer->region_code }}";
            let selectedProvince = "{{ $consumer->province_code }}";
            let selectedCity = "{{ $consumer->city_code }}";
            let selectedBarangay = "{{ $consumer->barangay_code }}";

            function createOption(value, text, isSelected) {
                return `<option value="${value}" ${isSelected ? 'selected' : ''}>${text}</option>`;
            }

            // Populate regions
            $region.empty();
            regions.forEach(r => {
                const isSelected = r.region_code == selectedRegion;
                $region.append(createOption(r.region_code, r.region_name, isSelected));
                if (isSelected) $regionName.val(r.region_name);
            });

            // Populate provinces
            function fillProvinces(regionCode) {
                $province.empty();
                $city.empty();
                $barangay.empty();

                const filtered = provinces.filter(p => p.region_code == regionCode);
                filtered.forEach(p => {
                    const isSelected = p.province_code == selectedProvince;
                    $province.append(createOption(p.province_code, p.province_name, isSelected));
                    if (isSelected) $provinceName.val(p.province_name);
                });

                if (selectedProvince) fillCities(selectedProvince);
            }

            // Populate cities
            function fillCities(provinceCode) {
                $city.empty();
                $barangay.empty();

                const filtered = cities.filter(c => c.province_code == provinceCode);
                filtered.forEach(c => {
                    const isSelected = c.city_code == selectedCity;
                    $city.append(createOption(c.city_code, c.city_name, isSelected));
                    if (isSelected) $cityName.val(c.city_name);
                });

                if (selectedCity) fillBarangays(selectedCity);
            }

            // Populate barangays
            function fillBarangays(cityCode) {
                $barangay.empty();
                const filtered = barangays.filter(b => b.city_code == cityCode);
                filtered.forEach(b => {
                    const isSelected = b.brgy_code == selectedBarangay;
                    $barangay.append(createOption(b.brgy_code, b.brgy_name, isSelected));
                    if (isSelected) $barangayName.val(b.brgy_name);
                });
            }

            // Event listeners
            $region.on('change', function() {
                const code = $(this).val();
                const name = $(this).find('option:selected').text();
                $regionName.val(name);
                fillProvinces(code);
            });

            $province.on('change', function() {
                const code = $(this).val();
                const name = $(this).find('option:selected').text();
                $provinceName.val(name);
                fillCities(code);
            });

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

            // Initialize cascading selects
            if (selectedRegion) fillProvinces(selectedRegion);

        })();
        @endforeach
    }
});





document.addEventListener("DOMContentLoaded", function () {
    @foreach($consumers as $consumer)
    (function() {
        const formId = "editConsumerForm-{{ $consumer->id }}";
        const emailInput = document.querySelector(`#${formId} #email_{{ $consumer->id }}`);
        const phoneInput = document.querySelector(`#${formId} #phone_{{ $consumer->id }}`);

        const errorEmail = document.querySelector(`#${formId} #error_email_{{ $consumer->id }}`);
        const errorPhone = document.querySelector(`#${formId} #error_phone_{{ $consumer->id }}`);

        function setMessage(el, msg, isValid) {
            el.innerText = msg;
            el.classList.remove("text-danger", "text-success");
            if (!msg) return;
            el.classList.add(isValid ? "text-success" : "text-danger");
        }


        emailInput.addEventListener("blur", function () {
            const email = emailInput.value.trim();
            if (!email) return setMessage(errorEmail, "", null);

            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!regex.test(email)) {
                return setMessage(errorEmail, "Invalid email format.", false);
            }

            fetch("{{ route('check.email') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ email, ignore_id: {{ $consumer->id }} })
            })
            .then(res => res.json())
            .then(data => {
                setMessage(errorEmail, data.exists ? "Email already in use." : "Email available.", !data.exists);
            })
            .catch(() => setMessage(errorEmail, "Error checking email.", false));
        });

     
        phoneInput.addEventListener("input", function () {
            const phone = phoneInput.value.trim();
            if (!phone) return setMessage(errorPhone, "", null);

            const regex = /^(09\d{9}|\+639\d{9})$/;
            setMessage(errorPhone,
                regex.test(phone) ? "" : "Must be 09XXXXXXXXX",
                regex.test(phone)
            );
        });

        phoneInput.addEventListener("keypress", function (e) {
            if (!/[0-9+]/.test(e.key)) e.preventDefault();
        });
    })();
    @endforeach
});







</script>
