<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
      <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/reconnection.css')}}">
    <title>Reconnection & Lineman Assignment   </title>
</head>
<body>
    @include('includes.sidebar')
    @include('modals.add-lineMan')
    @include('includes.alerts')

<div class="content">

    <header>
        <h2><a href="{{route('reconnection.index')}}" class="title">
             <i class="fa-solid fa-layer-group"></i> Reconnection & Line Man Assignment
        </a>

        </h2>
        <button  onclick="openAddLineMan()"> <i class="fa-solid fa-plus"></i> Add New Line Man</button>
    </header>


    <div class="stat-cards">

    <div class="stats">
      <div class="icon-box" style="background: #FEF9C3;   color: #EAB308;">
         <i class="fa-solid fa-circle-exclamation"></i>
      </div>
      <div class="stat-info">
        <p class="label">PENDING</p>
        <p class="number">12</p>
    </div>

      
 

    </div>

    <div class="stats">
      <div class="icon-box" style="color:#3B82F6; background: #e5f1faff;;">
         <i class="fa-solid fa-clock"></i>
      </div>
      <div class="stat-info">
        <p class="label">ACTIVE</p>
        <p class="number">12</p>
      </div>
    </div>


    <div class="stats">
      <div class="icon-box" style="background: #b8f1daff; 
  color: #10B981;">
         <i class="fa-solid fa-circle-check"></i>
      </div>
      <div class="stat-info">
        <p class="label">COMPLETED</p>
        <p class="number">12</p>
      </div>
     
   
    </div>

    <div class="stats">
      <div class="icon-box" style="background: #D1FAE5;  color: #10B981;">
         <i class="fa-solid fa-circle-user"></i>
      </div>
      <div class="stat-info">
        <p class="label">AVAILABLE</p>
        <p class="number">12</p>
      </div>
      
   
    </div>

    </div>


    <div class="tab-container">
      <button class="tab active">Reconnection Requests</button>
      <button class="tab">Line Men Management</button>
    </div>

    <div class="tab-content" >
      <div class="tab-pane active" id="reconnection">
        <p>Content for Reconnection Requests</p>
      </div>
      <div class="tab-pane" id="linemen">

            <div class="filters">

                <div class="search-wrapper">
                    <i class="fa fa-search search-icon"></i>
                    <input type="search" id="searchInput"  placeholder="Search line men...">
                </div>

                <select id="statusFilter" class="filter-select">
                      <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                      <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                      <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Disconnected</option>
                </select>

            </div>

         

            <div class="card-grid fade-in">

            @foreach($linemen as $lineman)
                <div class="card">
                  <div class="card-header">
                    <div class="avatar">RB</div>
                    <div class="info">
                      <h3>{{$lineman->first_name}}</h3>
                      <p> ID: {{$lineman->id}}</p>
                    </div>
                   <span class="status {{ $lineman->availability ? 'available' : 'not-available' }}">
                    {{ $lineman->availability ? 'Available' : 'Not Available' }}
                  </span>

                  </div>
                  <div class="card-body">
                    <div class="row">
                      <span><strong>Active Jobs</strong><br> 0</span>
                    </div>
                    <div class="row">
                      <span><strong>Completed Today</strong><br> 3</span>
                    </div>
                  </div>
                  <div class="card-footer">
                    <button class="btn-outline">View Profile</button>
                    <button class="btn-primary">Assign Job</button>
                  </div>
                </div>
              </div>
              @endforeach

            </div>



      </div>






   
</div>



<script>

  function openAddLineMan(){
    document.querySelector('.line-man-content').classList.add('active');
  }

  function closedAddLineMan(){
    document.querySelector('.line-man-content').classList.remove('active');
  }

  const tabs = document.querySelectorAll(".tab");
  const panes = document.querySelectorAll(".tab-pane");

  tabs.forEach((tab, index) => {
    tab.addEventListener("click", () => {

      tabs.forEach(t => t.classList.remove("active"));
      panes.forEach(p => p.classList.remove("active"));


      tab.classList.add("active");
      panes[index].classList.add("active");
    });
  });



</script>
    
</body>
</html>