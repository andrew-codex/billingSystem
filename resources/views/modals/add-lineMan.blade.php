<link rel="stylesheet" href="{{ asset('/CSS_Styles/modalCSS/add-lineMan.css') }}">
  <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="line-man-content">
    <div class="content-modal">
        <button class="close-btn" onclick="closedAddLineMan()"><i class="fa-solid fa-xmark"></i></button>
        <h2 class="modal-title">Create a Line Man</h2>

        <form action="{{route('linemen.create')}}" method="POST" id="lineman-form">
            @csrf
            @method('POST')

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" placeholder="Enter first name" required>
                @error('first_name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name</label>
                <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" placeholder="Enter middle name">
                @error('middle_name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
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
                <label for="group_name">Group Name</label>
                <input type="text" name="group_name" id="group_name" value="{{ old('group_name') }}" class="form-control">
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
                    <label>City</label>
                    <select id="city" name="city_code" class="select"></select>
                    <input type="hidden" name="city_name" id="city-text"> 
                </div>


                <div class="form-group">
                  <label>Barangay</label>
                  <select id="barangay" name="barangay_code" class="select"></select>
                 <input type="hidden" name="barangay_name" id="barangay-text">  
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



    
</script>