  <link rel="stylesheet" href="{{ asset('/CSS_Styles/modalCSS/add-consumer.css') }}">
  <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">

<!-- plugin CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/philippine-address-selector@latest/dist/philippine-address-selector.min.css">

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- plugin JS -->
<script src="https://cdn.jsdelivr.net/npm/philippine-address-selector@latest/dist/philippine-address-selector.min.js"></script>




 @include('includes.alerts')
<div class="add-consumer">
    <div class="modal-content">
        <button class="close-btn" onclick="closedAddConsumer()">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h2 class="modal-title">Create a Consumer</h2>


        <div class="modal-body">
            <form id="consumerForm" action="{{ route('consumers.store') }}" method="POST">
                @csrf
                @method('POST')

                <div class="form-columns">
            
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" 
                               placeholder="Enter first name" required>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" 
                               placeholder="Enter last name" required>
                    </div>

                    <div class="form-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" 
                               placeholder="Enter middle name" required>
                    </div>

                    <div class="form-group">
                        <label for="suffix">Suffix</label>
                        <select  name="suffix" id="suffix" class="select">
                            <option value="">-- None --</option>
                            <option value="Jr.">Jr.</option>
                            <option value="Sr.">Sr.</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                        </select>
                    </div>

                
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" 
                               placeholder="example@email.com" required>
                        <small class="text-danger" id="error_email"></small>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                               placeholder="09XXXXXXXXX">
                        <small class="text-danger" id="error_phone"></small>
                        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                
                <div class="address-section">
                    <h3 class="section-title">Address Information</h3>
                    <div class="form-columns">



            


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
                            <label for="street">Prk/Street</label>
                            <input type="text" name="street" id="street" value="{{ old('street') }}" 
                                   placeholder="Enter street" required>
                        </div>
                    </div>
                </div>

      
                <div class="other-info">
                    <h3 class="section-title">  Electric Meters Other Info</h3>
                  

                    <div class="form-group">
                        <label for="electric_meter_id" class="select-label">Electric Meter</label>
                        <select style="width:400px;" name="electric_meter_number" id="electric_meter_number" class="select">
                            <option value="">-- Select Meter --</option>
                            @foreach ($meters as $meter)
                                <option value="{{ $meter->id }}" {{ old('electric_meter_number') == $meter->id? 'selected' : '' }}>
                                    {{ $meter->electric_meter_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="installation_date">Installation Date</label>
                        <input style="width:400px;"  type="date" name="installation_date" id="installation_date" 
                               value="{{ old('installation_date') }}" >
                    </div>

                    <div class="form-group">
                        <label for="house_type" class="select-label">House Type</label>
                        <select style="width:400px;" name="house_type" id="house_type" class="select">
                            <option value="">-- Select House Type --</option>
                            <option value="residential" {{ old('house_type') == 'residential' ? 'selected' : '' }}>Residential</option>
                            <option value="commercial" {{ old('house_type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                            <option value="industrial" {{ old('house_type') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Consumer</button>
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

 
    barangayDropdown.addEventListener("change", function () {
        document.querySelector("#barangay-text").value = 
            this.options[this.selectedIndex].text;
    });
});




    

document.addEventListener("DOMContentLoaded", function () {
    
    const emailInput = document.getElementById("email");
    const phoneInput = document.getElementById("phone");

   
    const errorEmail = document.getElementById("error_email");
    const errorPhone = document.getElementById("error_phone");

    function setMessage(el, msg, isValid) {
        el.innerText = msg;
        el.classList.remove("text-danger", "text-success");
        if (!msg) return;
        if (isValid === true) el.classList.add("text-success");
        if (isValid === false) el.classList.add("text-danger");
    }

  



    emailInput.addEventListener("blur", function () {
    const email = emailInput.value.trim();

    
    if (!email) {
        setMessage(errorEmail, "", null);
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        setMessage(errorEmail, "Invalid email format.", false);
        return;
    }

    fetch("{{ route('check.email') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ email })
    })
    .then(res => res.json())
    .then(data => {
        setMessage(
            errorEmail,
            data.exists ? "Email already in use." : "Email available.",
            !data.exists
        );
    })
    .catch(() => setMessage(errorEmail, "Error checking email.", false));
});

 
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


</script>
