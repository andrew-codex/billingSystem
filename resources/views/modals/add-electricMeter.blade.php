
 <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
<link rel="stylesheet" href="{{asset('/CSS_Styles/modalCSS/add-electricMeter.css')}}">

<div class="add-electricMeter">
    <div class="modal-content">

            <button class="close-btn" onclick="closedAddMeter()"><i class="fa-solid fa-xmark"></i></button>
            <h2 class="modal-title">Register Electric Meter</h2>

            <form action="{{route('electricMeter.store')}}" method="POST">
                @csrf 
                @method('POST')

                <div class="form-columns">
                    <div class="form-group">
                        <label for="electric_meter_number">Electric Meter Number</label>
                        <input type="text" name="electric_meter_number" id="electric_meter_number" value="{{ old('electric_meter_number') }}" required>
                        <small id="error_meter" class="error-message"></small>
                        @error('electric_meter_number')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="">Select Status</option>
                            <option value="active">Active</option>
                            <option value="unassigned">Unassigned</option>
                            <option value="damaged">Damaged</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <div class="form-actions">

                           
                            <button type="submit" id="submitBtn" class="btn btn-primary">Add</button>
                    </div>
                </div>

            </form>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
    const meterInput = document.getElementById("electric_meter_number");
    const errorMeter = document.getElementById("error_meter");
    const submitBtn = document.getElementById("submitBtn");

    let meterValid = false; 

    function setMessage(el, msg, isValid) {
        el.innerText = msg;
        el.classList.remove("text-danger", "text-success");
        if (isValid === true) el.classList.add("text-success");
        if (isValid === false) el.classList.add("text-danger");

     
        meterValid = isValid === true;
        submitBtn.disabled = !meterValid;
    }

    meterInput.addEventListener("blur", function () {
        const meter = meterInput.value.trim();

        if (!meter) {
            setMessage(errorMeter, "", null);
            submitBtn.disabled = true;
            return;
        }

        fetch("{{ route('check.meter') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ electric_meter_number: meter })
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                setMessage(errorMeter, " This meter number already exists.", false);
            } else {
                setMessage(errorMeter, " Meter number is available.", true);
            }
        })
        .catch(() => {
            setMessage(errorMeter, " Error checking meter.", false);
        });
    });
});

</script>