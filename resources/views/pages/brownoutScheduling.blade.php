<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brownout Scheduling</title>
    <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/brownout.css')}}">
     <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
</head>
<body>
  @include('includes.sidebar')
  @include('modals.add-scheduleBrownout')
   @include('modals.edit-brownOutSchedule')
  @include('includes.alerts')
  <div class="content">

       <header>
        <h2><a href="{{route('BrownoutScheduling.index')}}" class="title">
             <i class="fa-solid fa-calendar"></i> Brownout Scheduling
        </a>
           
        </h2>
        <button  onclick="openAddSchedule()"> <i class="fa-solid fa-plus"></i> Add New Schedule</button>

    </header>

    


    <div class="main-content">
          <div class="filters " id="filterForm">
                <div class="search-wrapper">
                    <i class="fa fa-search search-icon"></i>
                    <input type="search" id="searchInput" placeholder="Search area / location...">
                </div>

        

                <select class="filter-select">
                    <option>All Status</option>
                    <option>Upcoming</option>
                    <option>Ongoing</option>
                     <option>Completed</option>
                </select>

                <div class="menu-dropdown">
                <button class="dropdown-toggle">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>

                <div class="header-menu">
                    <button onclick="openArchivedSchedule()">
                    <i class="fa-solid fa-box-archive"></i> Archived List
                    </button>
                </div>
                </div>

            </div>



            <div class="mid-content">

                <table class ="brownout-sched">
                    <thead>
                        <tr>
                            <th>Area / Location</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Reason</th> 
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                      <tbody>
                         @forelse($schedules as $schedule)
                        <tr data-start="{{ $schedule->schedule_date }} {{ $schedule->start_time }}" 
                            data-end="{{ $schedule->schedule_date }} {{ $schedule->end_time }}">
                                  <td>{{ $schedule->area }}</td>
                                  <td>{{ $schedule->schedule_date }}</td>
                                  <td>{{ date('g:i A', strtotime($schedule->start_time)) }}</td>
                                    <td>{{ date('g:i A', strtotime($schedule->end_time)) }}</td>

                                  <td>{{ $schedule->description }}</td>
                                  <td>
                                    <span class="badge badge-{{ $schedule->status }}" data-status="{{ $schedule->status }}">
                                            {{ ucfirst($schedule->status) }}
                                        </span>
                                </td>
                                    <td>
                                    <div class="dropdown">
                                        <button class="dropdown-toggle" onclick="toggleDropdown(this)">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                               

                               
            @if($schedule->status != 'cancelled' && $schedule->status != 'archived')
            <button style="color:#22c55e;" class="btn-edit" onclick="openEditBrownOut({{ $schedule->id }})">
                <i class="fa-solid fa-pen"></i> Edit
            </button>
            @endif

   
            @if($schedule->status == 'upcoming')
            <form action="{{ route('schedule.cancel', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" style="color:#ef4444;"><i class="fa-solid fa-ban"></i> Cancel</button>
            </form>
            @endif

             @if($schedule->status != 'cancelled' && $schedule->status != 'upcoming' && $schedule->status != 'ongoing')
            <form action="{{ route('schedule.archive', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" style="color:#ef4444;"><i class="fa-solid fa-box-archive"></i> Archive</button>
            </form>
                 @endif

                                    
                                        </div>
                                    </div>

                                    </td>
                              </tr>
                          @empty
                              <tr>
                                  <td colspan="7" style="text-align:center;">No schedules found.</td>
                              </tr>
                          @endforelse
                      </tbody>

                </table>
                    
                
            </div>
            


 


                                

    </div>

  </div>


  <script>
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();

    document.querySelectorAll('tr[data-start][data-end]').forEach(row => {
        const start = new Date(row.dataset.start);
        const end = new Date(row.dataset.end);
        const badge = row.querySelector('.badge');
        const originalStatus = badge.dataset.status;

    
        if (originalStatus === 'cancelled' || originalStatus === 'archived') return;

        if (now < start) {
            badge.textContent = 'Upcoming';
            badge.className = 'badge badge-upcoming';
        } else if (now >= start && now <= end) {
            badge.textContent = 'Ongoing';
            badge.className = 'badge badge-ongoing';
        } else if (now > end) {
            badge.textContent = 'Completed';
            badge.className = 'badge badge-completed';
        }
    });
});

document.querySelectorAll('.dropdown-toggle').forEach(button => {
  button.addEventListener('click', function (e) {
    e.stopPropagation(); 
    closeAllDropdowns();
    const menu = this.nextElementSibling;
    menu.classList.toggle('active');
  });
});


document.addEventListener('click', closeAllDropdowns);

function closeAllDropdowns() {
  document.querySelectorAll('.header-menu.active').forEach(menu => {
    menu.classList.remove('active');
  });
}


function openArchivedSchedule() {
  alert("Opening Archived Schedule...");
}


function openEditBrownOut(id) {
  document.getElementById(`editModal-${id}`).classList.add('active');
}

function closeEditBrownOut(id) {
  document.getElementById(`editModal-${id}`).classList.remove('active');
}


window.addEventListener('click', function(e) {
  const modals = document.querySelectorAll('.brownout-overlay');
  modals.forEach(modal => {
    if (e.target === modal) {
      modal.classList.remove('active');
    }
  });
});
  
function openAddSchedule() {
  document.querySelector(".brownout-overlay").classList.add('active');
}

function closeModal() {
  document.querySelector(".brownout-overlay").classList.remove('active');
}


window.onclick = function(e) {
  const modal = document.getElementById("brownoutModal");
  if (e.target === modal) {
    closeModal();
  }
}


function toggleDropdown(btn) {
    const menu = btn.nextElementSibling;
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    document.addEventListener('click', function handler(e) {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.style.display = 'none';
            document.removeEventListener('click', handler);
        }
    });
}


 document.addEventListener("DOMContentLoaded", () => {

  window.openEditBrownOut = function(id) {
    const modal = document.getElementById(`editModal-${id}`);
    const form = modal.querySelector(`#editForm-${id}`);

   
    if (!form.dataset.originalData) {
      const formData = new FormData(form);
      const original = {};
      formData.forEach((value, key) => original[key] = value);
      form.dataset.originalData = JSON.stringify(original);
    }

    modal.classList.add("active");
  };


  window.closeEditBrownOut = function(id) {
    const modal = document.getElementById(`editModal-${id}`);
    const form = modal.querySelector(`#editForm-${id}`);
    const originalData = form.dataset.originalData ? JSON.parse(form.dataset.originalData) : null;

    if (originalData) {
      for (const key in originalData) {
        const input = form.querySelector(`[name="${key}"]`);
        if (input) input.value = originalData[key];
      }
    }

    modal.classList.remove("active");
  };


  document.querySelectorAll("form[id^='editForm-']").forEach(form => {
    form.addEventListener("submit", e => {
      const originalData = JSON.parse(form.dataset.originalData || "{}");
      const currentData = new FormData(form);
      let changed = false;

      currentData.forEach((value, key) => {
        if (value != originalData[key]) changed = true;
      });

      if (!changed) {
        e.preventDefault();
        Swal.fire({
          icon: "info",
          title: "No changes detected",
          text: "Please modify at least one field before saving.",
          confirmButtonColor: "#3b82f6",
        });
      }
    });
  });


  window.addEventListener("click", function(e) {
    document.querySelectorAll(".brownout-overlay.active").forEach(modal => {
      if (e.target === modal) modal.classList.remove("active");
    });
  });
});


document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-start][data-end]').forEach(row => {
        const now = new Date();
        const start = new Date(row.dataset.start);
        const end = new Date(row.dataset.end);
        const badge = row.querySelector('.badge');

        if (badge.classList.contains('badge-cancelled') || badge.classList.contains('badge-archived')) return;

        if (now < start) {
            badge.textContent = 'Upcoming';
            badge.className = 'badge badge-upcoming';
        } else if (now >= start && now <= end) {
            badge.textContent = 'Ongoing';
            badge.className = 'badge badge-ongoing';
        } else {
            badge.textContent = 'Completed';
            badge.className = 'badge badge-completed';
        }
    });
});

  </script>
</body>
</html>
