
<link rel="stylesheet" href="{{asset('/CSS_Styles/modalCSS/assignNew-meter.css')}}">

<div class="modal" id="assignMeterModal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeAssignMeterModal()">&times;</span>
        <h2>Assign New Meter</h2>

        <form id="assignMeterForm" method="POST">
            @csrf
            <input type="hidden" name="consumer_id" id="assign_consumer_id">

            <label for="meter_id">Select Available Meter:</label>
            <select name="meter_id" id="meter_id" class="form-control" required>
                <option value="">-- Choose Meter --</option>
                @foreach($meters as $meter)
                    <option value="{{ $meter->id }}">{{ $meter->electric_meter_number }}</option>
                @endforeach
            </select>

                <div class="form-group">
                    <label for="house_type" class="select-label">House Type</label>
                    <select name="house_type" id="house_type">
                        <option value="">-- Select House Type --</option>
                        <option value="residential" {{ old('house_type') == 'residential' ? 'selected' : '' }}>Residential</option>
                        <option value="commercial" {{ old('house_type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                        <option value="industrial" {{ old('house_type') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                    </select>
                </div> 

            <label for="installation_date">Installation Date:</label>
            <input type="date" name="installation_date" class="form-control" required>

            <button type="submit" class="btn btn-primary">Assign Meter</button>
        </form>
    </div>
</div>

