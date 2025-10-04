<link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">

<div id="brownoutModal" class="brownout-overlay">
  <div class="brownout-modal">
    
    <div class="brownout-header">
      <h2>Add New Brownout Schedule</h2>
      <button class="brownout-close-btn" onclick="closeModal()">&times;</button>
    </div>
    
    <div class="brownout-body">
      <p class="brownout-subtitle">Create a new brownout schedule for a specific area and time period.</p>
      
      <form id="brownoutForm" method="POST" action="{{route('store.schedule')}}">
        @csrf 
        @method('POST')
        <label>Area / Location</label>
        <input type="text" name="area" placeholder="Enter area or location" required>
        
        <div class="brownout-form-row">
          <div>
            <label>Date</label>
            <input type="date" name="schedule_date" required>
          </div>
        </div>
        
        <div class="brownout-form-row">
          <div>
            <label>Start Time</label>
            <input type="time" name="start_time" required>
          </div>
          <div>
            <label>End Time</label>
            <input style="width:180px;" type="time" name="end_time" required>
          </div>
        </div>
        
        <label>Reason</label>
        <textarea name="description" placeholder="Enter reason for brownout"></textarea>
      </form>
    </div>
    
    <div class="brownout-footer">
      <button class="brownout-btn-cancel" onclick="closeModal()">Cancel</button>
      <button class="brownout-btn-save" type="submit" form="brownoutForm">Save Schedule</button>
    </div>
  </div>
</div>

<style>
.brownout-overlay {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.65); 
  align-items: center;
  justify-content: center;
  z-index: 2000;
}
.brownout-overlay.active {
  display: flex;
}

.brownout-modal {
  background: #1f2937;  
  color: #f9fafb;      
  border-radius: 10px;
  width: 500px;
  max-width: 90%;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
  display: flex;
  flex-direction: column;
  animation: fadeIn 0.25s ease;
}

.brownout-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 20px;
  border-bottom: 1px solid #2d2f3a; 
}
.brownout-header h2 {
  font-size: 18px;
  margin: 0;
  color: #ffffff;
}
.brownout-close-btn {
  background: none;
  border: none;
  font-size: 22px;
  cursor: pointer;
  color: #9ca3af;
  transition: color 0.2s;
}
.brownout-close-btn:hover {
  color: #f9fafb;
}

.brownout-body {
  padding: 20px;
}
.brownout-subtitle {
  font-size: 13px;
  color: #9ca3af;
  margin-bottom: 15px;
}

.brownout-body label {
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 5px;
  display: block;
  color: #e5e7eb;
}

.brownout-body input, 
.brownout-body select, 
.brownout-body textarea {
  width: 90%;
  padding: 10px 12px;
  margin-bottom: 15px;
  border: 1px solid #374151;
  border-radius: 8px;
  font-size: 14px;
  background: #2d2f3a;
  color: #f9fafb;
  transition: border 0.2s, box-shadow 0.2s, background 0.2s;
}
.brownout-body input:focus, 
.brownout-body select:focus, 
.brownout-body textarea:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59,130,246,0.35);
  outline: none;
  background: #374151;
}
.brownout-body textarea {
  min-height: 70px;
  resize: vertical;
}

.brownout-form-row {
  display: flex;
  gap: 12px;
}
.brownout-form-row > div {
  flex: 1;
}

.brownout-footer {
  padding: 14px 20px;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  border-top: 1px solid #2d2f3a;
}
.brownout-btn-cancel {
  background: #374151;
  color: #d1d5db;
  border: none;
  padding: 8px 14px;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.2s;
}
.brownout-btn-cancel:hover {
  background: #4b5563;
}
.brownout-btn-save {
  background: #3b82f6;
  color: #fff;
  border: none;
  padding: 8px 14px;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.2s, box-shadow 0.2s;
}
.brownout-btn-save:hover {
  background: #2563eb;
  box-shadow: 0 4px 12px rgba(37,99,235,0.35);
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
