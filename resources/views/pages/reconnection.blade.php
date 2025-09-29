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
@include('modals.linemenProfile')
@include('modals.edit-linemen')


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
        <p class="number">0</p>
    </div>

      
 

    </div>

    <div class="stats">
      <div class="icon-box" style="color:#3B82F6; background: #e5f1faff;;">
         <i class="fa-solid fa-clock"></i>
      </div>
      <div class="stat-info">
        <p class="label">ACTIVE</p>
        <p class="number">0</p>
      </div>
    </div>


    <div class="stats">
      <div class="icon-box" style="background: #b8f1daff; 
  color: #10B981;">
         <i class="fa-solid fa-circle-check"></i>
      </div>
      <div class="stat-info">
        <p class="label">COMPLETED</p>
        <p class="number">0</p>
      </div>
     
   
    </div>

    <div class="stats">
      <div class="icon-box" style="background: #D1FAE5;  color: #10B981;">
         <i class="fa-solid fa-circle-user"></i>
      </div>
    
      <div class="stat-info">
        <p class="label">AVAILABLE</p>
        <p class="number">{{$availableCount ?? 0}}</p>
      </div>
 
      
   
    </div>

    </div>


    <div class="tab-container">
      <button class="tab active">Reconnection Requests</button>
      <button class="tab">Line Men Management</button>
    </div>

    <div class="tab-content" >
      <div class="tab-pane active" id="reconnection">

        <div class="request-content">
                <div class="search-wrapper">
                    <i class="fa fa-search search-icon"></i>
                    <input type="search" id="searchInput"  placeholder="Search request...">
                </div>

                <div class="card">
                
                        <p>example</p>
                         <button>Assign</button>
                  
                </div>

                <div class="card">
                  
                        <p>example</p>
            <button>Assign</button>
                </div>

                <div class="card">
               
                        <p>example</p>
                         <button>Assign</button>
                </div>
            
        </div>
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
                                  @php
                                      $initials = strtoupper(substr($lineman->first_name, 0, 1) . substr($lineman->last_name, 0, 1));
                                  @endphp   
                                  <div class="avatar">{{ $initials }}</div>
                                  <div class="info">
                                      <h3>{{$lineman->first_name}}</h3>
                                      <p> ID: {{$lineman->id}}</p>
                                  </div>

                                  <span class="status 
                                      @if($lineman->status === 'inactive') not-available 
                                      @elseif($lineman->availability) available 
                                      @else not-available 
                                      @endif">
                                      @if($lineman->status === 'inactive')
                                          Deactivated
                                      @elseif($lineman->status === 'on_leave')
                                          On Leave
                                      @else
                                          {{ $lineman->availability ? 'Available' : 'Not Available' }}
                                      @endif
                                  </span>

                                  <div class="header-dropdown">
                                      <button class="header-dropdown-toggle"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                      <div class="header-menu">
                                          @if($lineman->status === 'active')
                                          
                                              <form action="{{ route('linemen.deactivate', $lineman->id) }}" method="POST">
                                                  @csrf
                                                  <button type="submit" style="color:#ef4444;">
                                                      <i class="fa-solid fa-user-minus"></i> Deactivate
                                                  </button>
                                              </form>

                                              <button style="color:#22c55e;"  onclick="openEdit({{ $lineman->id }})"> <i class="fa-solid fa-pen-to-square"></i> Edit</button>




      



            <form action="{{ route('linemen.onleave', $lineman->id) }}" method="POST">
                @csrf
                <button type="submit" style="color:#f59e0b;">
                    <i class="fa-solid fa-user-clock"></i> On Leave
                </button>
            </form>

        @elseif($lineman->status === 'inactive')
      
            <form action="{{ route('linemen.activate', $lineman->id) }}" method="POST">
                @csrf
                <button type="submit" style="color:#16a34a;">
                    <i class="fa-solid fa-user-check"></i> Activate
                </button>
            </form>

            <form action="{{ route('linemen.archive', $lineman->id) }}" method="POST">
                @csrf
                <button type="submit" style="color:#6b7280;">
                    <i class="fa-solid fa-archive"></i> Archive
                </button>
            </form>

        @elseif($lineman->status === 'on_leave')
            
            <form action="{{ route('linemen.back_from_leave', $lineman->id) }}" method="POST">
                @csrf
                <button type="submit" style="color:#16a34a;">
                    <i class="fa-solid fa-arrow-rotate-left"></i> Back from Leave
                </button>
            </form>
        @endif
    </div>
</div>



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
                                  <button class="btn-outline" onclick="openProfile({{ $lineman->id }})">
                                      <i class="fa-solid fa-user"></i> View Profile
                                  </button>
                                  <button class="btn-primary"><i class="fa-solid fa-briefcase"></i> Assign Job</button>
                              </div>
                          </div>
                      @endforeach
                  </div>

      </div>

</div>





<script>

function openEdit(id) {
    const modal = document.getElementById(`edit-lineman-${id}`).classList.add('active');
  


    const $region   = $(`#region-${id}`);
    const $province = $(`#province-${id}`);
    const $city     = $(`#city-${id}`);
    const $barangay = $(`#barangay-${id}`);

    const regionCode   = $region.data("selected");
    const provinceCode = $province.data("selected");
    const cityCode     = $city.data("selected");
    const barangayCode = $barangay.data("selected");

 
    if (regionCode) {
        $region.val(regionCode).trigger("change");

        setTimeout(() => {
            if (provinceCode) $province.val(provinceCode).trigger("change");
        }, 400);

        setTimeout(() => {
            if (cityCode) $city.val(cityCode).trigger("change");
        }, 800);

        setTimeout(() => {
            if (barangayCode) $barangay.val(barangayCode).trigger("change");
        }, 1200);
    }
}

function closeEdit(id) {
    document.getElementById('edit-lineman-' + id).classList.remove('active');
}




function attachHeaderToggle() {
    const dropdowns = document.querySelectorAll('.header-dropdown');

    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.header-dropdown-toggle');
        const menu = dropdown.querySelector('.header-menu');

        toggle.addEventListener('click', e => {
            e.stopPropagation();
            menu.classList.toggle('show');
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.header-menu').forEach(menu => menu.classList.remove('show'));
    });
}

document.addEventListener('DOMContentLoaded', attachHeaderToggle);


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