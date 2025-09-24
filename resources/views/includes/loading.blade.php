
<div id="loadingOverlay" style="display: none;">
    <div class="spinner"></div>
    <p id="loadingText">Processing...</p>
</div>


<div id="toast" style="display: none;"></div>

<style>

#loadingOverlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    z-index: 9999;
}
.spinner {
    border: 6px solid #f3f3f3;
    border-top: 6px solid #3498db;
    border-radius: 50%;
    width: 50px; height: 50px;
    animation: spin 1s linear infinite;
    margin-bottom: 10px;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

#loadingText {
    color: white;
    font-size: 18px;
    margin-top: 10px;
}


#toast {
    position: fixed;
    top: 20px; right: 20px;
    background: #4caf50;
    color: #fff;
    padding: 15px 25px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    z-index: 10000;
    animation: fadeout 3s forwards;
}
@keyframes fadeout {
    0% { opacity: 1; }
    80% { opacity: 1; }
    100% { opacity: 0; transform: translateY(-20px); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {

    
    function showLoading(text = "Processing...") {
        document.getElementById('loadingText').innerText = text;
        document.getElementById('loadingOverlay').style.display = 'flex';
    }

  
    function hideLoading() {
        document.getElementById('loadingOverlay').style.display = 'none';
    }

    function showToast(message, color = "#4caf50") {
        const toast = document.getElementById('toast');
        toast.style.background = color;
        toast.innerText = message;
        toast.style.display = 'block';
        setTimeout(() => toast.style.display = 'none', 3000);
    }

    
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            showLoading("Logging in...");
           
        });
    }

  
    const logoutForm = document.getElementById('logoutForm');
    if (logoutForm) {
        logoutForm.addEventListener('submit', function(e) {
            showLoading("Logging out...");
             setTimeout(() => logoutForm.submit(), 150);
        });
    }

 
    @if(session('success'))
        showToast(@json(session('success')), "#4caf50");
    @endif

    @if(session('error'))
        showToast(@json(session('error')), "#f44336");
    @endif

});
</script>
