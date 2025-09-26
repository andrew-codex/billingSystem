@foreach($linemen as $lineman)
<div class="profile-modal" id="profile-modal-{{ $lineman->id }}">
    <div class="modal-content">
        <button class="close-btn" onclick="closeProfile({{ $lineman->id }})">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="profile-card">
            <div class="avatar">
                {{ strtoupper(substr($lineman->first_name,0,1) . substr($lineman->last_name,0,1)) }}
            </div>

            <h3>{{ $lineman->first_name }} {{ $lineman->last_name }}</h3>
            @if($lineman->suffix)
                <p class="suffix">{{ $lineman->suffix }}</p>
            @endif

            <span class="status 
                @if($lineman->status === 'inactive' || $lineman->status === 'on_leave') not-available
                @elseif($lineman->availability) available
                @else not-available @endif">
                @if($lineman->status === 'inactive')
                    Deactivated
                @elseif($lineman->status === 'on_leave')
                    On Leave
                @else
                    {{ $lineman->availability ? 'Available' : 'Not Available' }}
                @endif
            </span>

            <div class="info-section">
                <p style="color:#f8fafc;"><strong style="color:#f8fafc;">Contact:</strong> {{ $lineman->contact_number }}</p>
                <p style="color:#f8fafc;"><strong style="color:#f8fafc;">Address:</strong> {{$lineman->full_address}}
                </p>
            </div>

        </div>
    </div>
</div>
@endforeach

<style>

.profile-modal {
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.6);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    padding: 20px;
    overflow-y: auto; 
    transition: opacity 0.3s ease;
}
.profile-modal.active {
    display: flex;
    animation: fadeIn 0.3s ease;
}


@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}


.modal-content {
      background: #1e293b;
      
    padding: 30px;
    border-radius: 16px;
    width: 500px;
    max-width: 95%;
    position: relative;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    transition: transform 0.3s ease;
}
.profile-modal.active .modal-content {
    transform: translateY(0);
}


.close-btn {
    position: absolute;
    top: 14px;
    right: 14px;
    background: transparent;
    border: none;
    color: #cbd5e1;
    font-size: 1.5rem;
    cursor: pointer;
    transition: color 0.2s;
}
.close-btn:hover { color: #ef4444; }


.profile-card { 
    text-align: center; 
    font-family: 'Segoe UI', sans-serif;
}
.profile-card .avatar {
    width: 90px; height: 90px;
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
    color: #fff;
    border-radius: 50%;
    font-size: 2rem;
    display: flex;
    align-items: center; justify-content: center;
    margin: 0 auto 15px;
    font-weight: bold;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
.profile-card h3 {
    margin: 0 0 5px;
    font-size: 1.4rem;
     color: #f8fafc;
}
.profile-card .suffix {
     color: #f8fafc;
    font-size: 0.9rem;
    margin-bottom: 10px;
}


.profile-card .status {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 12px;
     color: #f8fafc;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    margin-bottom: 15px;
}
.status.available { background-color: #16a34a; }
.status.not-available { background-color: #dc2626; }


.info-section {
    text-align: left;
    margin-top: 10px;
    font-size: 0.95rem;
     color: #f8fafc;
}
.info-section p {
    margin: 6px 0;
    line-height: 1.4;
}


@media(max-width:600px) {
    .modal-content { width: 90%; padding: 20px; }
    .profile-card .avatar { width: 70px; height: 70px; font-size: 1.5rem; }
}
</style>

<script>
function openProfile(id) {
    document.getElementById('profile-modal-' + id).classList.add('active');
}

function closeProfile(id) {
    document.getElementById('profile-modal-' + id).classList.remove('active');
}
</script>
