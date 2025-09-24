<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
    <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/dashboard.css')}}">
    <title>Dashboard</title>
</head>
<body>
    @include('includes.sidebar')
    @include('includes.loading')

    <div class="content">
        <header>
            <h2><a href="{{route('dashboard.index')}}" class="title">
                 <i class="fa-solid fa-gauge"></i> Dashboard Overview
            </a>
            </h2>
                    <div class="header-dropdown">
                      <div class="dropdown">
                       
                        <button class="dropdown-btn">
                        <i class="fa-solid fa-circle-user"></i>
                        </button>
                        <div class="dropdown-content">
                        <a href="#"><i class="fa-solid fa-user-gear"></i> Settings</a>
                        <div class="history-wrapper">
                            <a href="javascript:void(0);" class="history-toggle">
                                <i class="fa-solid fa-clock"></i> Transaction History
                            </a>

                            <ul class="history-list" style="display: none;">
                                @forelse($recentHistories as $history)
                                    <li>
                                        <strong>{{ $history->meter->electric_meter_number ?? 'N/A' }}</strong>
                                        assigned to <strong>{{ $history->consumer->full_name ?? 'Unknown' }}</strong>
                                        by <strong>{{ $history->changedBy->name ?? 'System' }}</strong>
                                        <span class="history-date">{{ $history->created_at->format('M d, Y H:i') }}</span>
                                    </li>
                                @empty
                                    <li>No recent history available.</li>
                                @endforelse
                                <li><a href="" class="view-all">View All</a></li>
                            </ul>
                        </div>
                        <a href="#"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                        </div>
                      </div>
                    </div>
        </header>

   


  

    <div class="main-content">

    
        <div class="dashboard-content">
    
    <div class="main-grid">
       
        <div class="left-column">
          
            <div class="stats-grid">
                <div class="stat-box blue">{{$totalConsumers}} <br><span>Example</span></div>
                <div class="stat-box green">5 <br><span>Example</span></div>
                <div class="stat-box red">1 <br><span>Example</span></div>
                <div class="stat-box purple">12 <br><span>Example</span></div>
                <div class="stat-box orange">7 <br><span>Examples</span></div>
                <div class="stat-box teal">3 <br><span>Example</span></div>
            </div>

      
            <div class="graph-box">
                <h3> Graph</h3>
               
            </div>

         
            <div class="quick-actions">
                <h3> Quick Actions</h3>
                <button class="btn blue">+ Add Staff</button>
                <button class="btn green">+ Add Consumer</button>
                
            </div>
        </div>

        
        <div class="side-column">
            <div class="sidebar-box">
                <h3> Recent Activity</h3>
                <ul>
                    <li>Example</li>
                    <li>Example</li>
                    <li>Example</li>
                </ul>
            </div>
            <div class="sidebar-box">
                <h3> Recent Transactions</h3>
                <ul>
                    <li>Example</li>
                    <li>Example</li>
                    <li>Example</li>
                </ul>
            </div>
        </div>
    </div>
</div>





     </div>

</div>

    <script>










document.addEventListener("DOMContentLoaded", () => {
  const btn = document.querySelector(".dropdown-btn");
  const menu = document.querySelector(".dropdown-content");

  btn.addEventListener("click", (e) => {
    e.stopPropagation(); 
    menu.classList.toggle("show");
  });


  document.addEventListener("click", () => {
    menu.classList.remove("show");
  });
});




document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('.history-toggle');

    toggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const historyList = this.nextElementSibling;
            if (historyList.style.display === 'none' || historyList.style.display === '') {
                historyList.style.display = 'block';
            } else {
                historyList.style.display = 'none';
            }
        });
    });
});


</script>
</body>
</html>