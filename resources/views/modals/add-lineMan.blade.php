<link rel="stylesheet" href="{{ asset('/CSS_Styles/modalCSS/add-lineMan.css') }}">
  <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('/JsFiles/ph-address-selector.js') }}"></script>
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
                    <label>Region</label>
                       <select id="region" class="form-control"></select>
    <input type="hidden" name="region_name" id="region-text">

                </div>

                <div class="form-group">
                      <label>Province</label>
                        <select id="province" class="form-control"></select>
    <input type="hidden" name="province_name" id="province-text">
                    
                </div>

                <div class="form-group">
                      <label>City</label>
                        <select id="city" class="form-control"></select>
    <input type="hidden" name="city_name" id="city-text">

                    
                </div>


                <div class="form-group">
                                      <label>Barangay</label>
                  <select id="barangay" class="form-control"></select>
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



$(document).ready(function () {

    $("#region, #province, #city, #barangay").ph_address({
        region: "#region",
        province: "#province",
        city: "#city",
        barangay: "#barangay",
        details: {
            region: "#region-text",
            province: "#province-text",
            city: "#city-text",
            barangay: "#barangay-text"
        }
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