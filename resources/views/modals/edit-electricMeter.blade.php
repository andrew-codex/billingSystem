<link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
<link rel="stylesheet" href="{{asset('/CSS_Styles/modalCSS/edit-electricMeter.css')}}">

<div id="editModal" class="edit-meter">
    <div class="modal-content">
        <button class="close-btn" onclick="closeEditModal()"><i class="fa-solid fa-xmark"></i></button>
        <h2 class="modal-title">Edit Electric Meter</h2>

        <form id="editForm" method="POST">
            @csrf 
            @method('PUT')

            <div class="form-group">
                <label for="electric_meter_number">Electric Meter Number</label>
                <input type="text" name="electric_meter_number" id="edit_number" required>
            </div>

<div class="form-group">
    <label>Date Added</label>
    <input type="text" name= "created_at" id="edit_created" readonly>
</div>



            <div class="form-actions">
            
            <button type="submit">Update</button>

            </div>

            
        </form>
    </div>
</div>
