<link rel="stylesheet" href="{{ asset('/CSS_Styles/modalCSS/add-lineMan.css') }}">
  <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">

<div class="line-man-content">
    <div class="content-modal">
        <button class="close-btn" onclick="closedAddLineMan()"><i class="fa-solid fa-xmark"></i></button>
        <h2 class="modal-title">Create a Line Man</h2>

        <form action="{{route('linemen.create')}}" method="POST" id="lineman-form">
            @csrf
            @method('POST')

            <div class="form-group">
                <label for="first_name">First Name*</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" placeholder="Enter first name" required>
                @error('first_name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name*</label>
                <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" placeholder="Enter middle name">
                @error('middle_name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="last_name">Last Name*</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" placeholder="Enter last name" required>
                @error('last_name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="suffix">Suffix</label>
                <select name="suffix" id="suffix" class="select">
                    <option value="">-- None --</option>
                    <option value="Jr.">Jr.</option>
                    <option value="Sr.">Sr.</option>
                    <option value="II">II</option>
                    <option value="III">III</option>
                    <option value="IV">IV</option>
                </select>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="contact_number" id="phone" value="{{ old('contact_number') }}" placeholder="09XXXXXXXXX" required pattern="09[0-9]{9}">
                <small class="text-danger" id="error_phone"></small>
                @error('contact_number') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="address-section">
                <h3 class="section-title">Address Information</h3>
                <div class="form-columns">
                    <div class="form-group">
                        <label for="region">Region</label>
                        <select style="width:300px;"id="region" name="region_code" class="select" required>
                            <option value="">-- Select Region --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="province">Province</label>
                        <select id="province" name="province_code" class="select" required>
                            <option value="">-- Select Province --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="city">City / Municipality</label>
                        <select id="city" name="city_code" class="select" required>
                            <option value="">-- Select City --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="barangay">Barangay</label>
                        <select id="barangay" name="barangay_code" class="select" required>
                            <option value="">-- Select Barangay --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="street">Prk/Street</label>
                        <input type="text" name="street" id="street" value="{{ old('street') }}" placeholder="Enter street" required>
                        @error('street') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Line Man</button>
            </div>
        </form>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
    

    const phoneInput = document.getElementById("phone");

   
   
    const errorPhone = document.getElementById("error_phone");

    function setMessage(el, msg, isValid) {
        el.innerText = msg;
        el.classList.remove("text-danger", "text-success");
        if (!msg) return;
        if (isValid === true) el.classList.add("text-success");
        if (isValid === false) el.classList.add("text-danger");
    }

  



 
    phoneInput.addEventListener("input", function () {
        const phone = phoneInput.value;
        if (!phone) return setMessage(errorPhone, "", null);

        const phoneRegex = /^(09\d{9}|\+639\d{9})$/;
        if (!phoneRegex.test(phone)) {
            setMessage(errorPhone, "Must be 09XXXXXXXXX", false);
        }else{
              setMessage(errorPhone, "", null);
        }
    });

  
    phoneInput.addEventListener("keypress", function (e) {
        if (!/[0-9+]/.test(e.key)) e.preventDefault();
    });
});



    const REGIONS = @json($regions);
    const PROVINCES = @json($provinces);

document.addEventListener("DOMContentLoaded", function () {
    const regionSelect = document.getElementById("region");
    const provinceSelect = document.getElementById("province");
    const citySelect = document.getElementById("city");
    const barangaySelect = document.getElementById("barangay");


    fetch("/psgc/regions/all")
        .then(res => res.json())
        .then(regions => {
            regions.forEach(r => {
                const opt = document.createElement("option");
                opt.value = r.code;
                opt.textContent = r.name;
                regionSelect.appendChild(opt);
            });
        });

    regionSelect.addEventListener("change", function () {
        provinceSelect.innerHTML = "<option value=''>-- Select Province --</option>";
        citySelect.innerHTML = "<option value=''>-- Select City --</option>";
        barangaySelect.innerHTML = "<option value=''>-- Select Barangay --</option>";

        if (!this.value) return;

        fetch(`/psgc/provinces/all?region_code=${this.value}`)
            .then(res => res.json())
            .then(provinces => {
                provinces.forEach(p => {
                    const opt = document.createElement("option");
                    opt.value = p.code;
                    opt.textContent = p.name;
                    provinceSelect.appendChild(opt);
                });
            });
    });


    provinceSelect.addEventListener("change", function () {
        citySelect.innerHTML = "<option value=''>-- Select City --</option>";
        barangaySelect.innerHTML = "<option value=''>-- Select Barangay --</option>";

        if (!this.value) return;

        fetch(`/psgc/cities/all?province_code=${this.value}`)
            .then(res => res.json())
            .then(cities => {
                cities.forEach(c => {
                    const opt = document.createElement("option");
                    opt.value = c.code;
                    opt.textContent = c.name;
                    citySelect.appendChild(opt);
                });
            });
    });

 
    citySelect.addEventListener("change", function () {
        barangaySelect.innerHTML = "<option value=''>-- Select Barangay --</option>";
        if (!this.value) return;

        fetch(`/psgc/barangays/all?city_code=${this.value}`)
            .then(res => res.json())
            .then(barangays => {
                barangays.forEach(b => {
                    const opt = document.createElement("option");
                    opt.value = b.code;
                    opt.textContent = b.name;
                    barangaySelect.appendChild(opt);
                });
            });
    });
});
</script>