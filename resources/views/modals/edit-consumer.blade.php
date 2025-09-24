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
                    <div class="form-group">
                        <label for="region_{{ $consumer->id }}">Region</label>
                        <select name="region_code" id="region_{{ $consumer->id }}" class="region-select select" data-consumer="{{ $consumer->id }}">
                            <option value="">-- Select Region --</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->code }}" {{ $consumer->region_code == $region->code ? 'selected' : '' }}>
                                    {{ $region->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="province_{{ $consumer->id }}">Province</label>
                        <select name="province_code" id="province_{{ $consumer->id }}" class="select" data-selected="{{ $consumer->province_code ?? '' }}">
                            <option value="">-- Select Province --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="city_{{ $consumer->id }}">City / Municipality</label>
                        <select name="city_code" id="city_{{ $consumer->id }}" class="select" data-selected="{{ $consumer->city_code ?? '' }}">
                            <option value="">-- Select City --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="barangay_{{ $consumer->id }}">Barangay</label>
                        <select name="barangay_code" id="barangay_{{ $consumer->id }}" class="barangay-select select" data-selected="{{ $consumer->barangay_code ?? '' }}">
                            <option value="">-- Select Barangay --</option>
                        </select>
                    </div>

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

<script>


document.addEventListener("DOMContentLoaded", () => {
    async function loadOptions(url, select, selectedValue = null, placeholder = "-- Select --") {
        try {
            const res = await fetch(url);
            if (!res.ok) return;

            const data = await res.json();
            select.innerHTML = `<option value="">${placeholder}</option>`;

            data.data.forEach(item => {
                const opt = document.createElement("option");
                opt.value = item.code;
                opt.textContent = item.name;
                if (selectedValue && selectedValue == item.code) opt.selected = true;
                select.appendChild(opt);
            });
        } catch (e) {
            console.error("Error loading options:", e);
        }
    }

    document.querySelectorAll(".region-select").forEach(regionSelect => {
        const consumerId    = regionSelect.dataset.consumer;
        const provinceSelect = document.getElementById(`province_${consumerId}`);
        const citySelect     = document.getElementById(`city_${consumerId}`);
        const barangaySelect = document.getElementById(`barangay_${consumerId}`);

        const provinceSelected = provinceSelect.dataset.selected || null;
        const citySelected     = citySelect.dataset.selected || null;
        const barangaySelected = barangaySelect.dataset.selected || null;

(async () => {
    if (regionSelect.value) {
        // Provinces
        await loadOptions(
            `/psgc/provinces?region_code=${regionSelect.value}`,
            provinceSelect,
            provinceSelected,
            "-- Select Province --"
        );

        // Cities
        if (provinceSelected) {
            await loadOptions(
                `/psgc/cities?province_code=${provinceSelected}`,
                citySelect,
                citySelected,
                "-- Select City --"
            );
        }

        // Barangays
        if (citySelected) {
            await loadOptions(
                `/psgc/barangays?city_code=${citySelected}`,
                barangaySelect,
                barangaySelected,
                "-- Select Barangay --"
            );
        }
    }
})();

       
        regionSelect.addEventListener("change", () => {
            loadOptions(`/psgc/provinces?region_code=${regionSelect.value}`, provinceSelect, null, "-- Select Province --");
            citySelect.innerHTML = "<option value=''>-- Select City --</option>";
            barangaySelect.innerHTML = "<option value=''>-- Select Barangay --</option>";
        });

        provinceSelect.addEventListener("change", () => {
            loadOptions(`/psgc/cities?province_code=${provinceSelect.value}`, citySelect, null, "-- Select City --");
            barangaySelect.innerHTML = "<option value=''>-- Select Barangay --</option>";
        });

        citySelect.addEventListener("change", () => {
            loadOptions(`/psgc/barangays?city_code=${citySelect.value}`, barangaySelect, null, "-- Select Barangay --");
        });
    });
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

        // ✅ Phone validation
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
