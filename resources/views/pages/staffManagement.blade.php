<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('/CSS_Styles/mainCss/base.css') }}">
    <link rel="stylesheet" href="{{ asset('/CSS_Styles/mainCss/staffManagement.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Staff Management</title>
</head>
<body>
    @include('includes.sidebar')
    @include('modals.add-staff')
    @include('includes.alerts')
    @include('modals.edit-staff')
    @include('modals.archived-staff')

    <div class="content">
        <header>
            <h2>
                <a href="{{ route('staff.index') }}" class="title">
                    <i class="fa-solid fa-user"></i> Staff Management
                </a>
            </h2>
            <button onclick="openAddStaff()"><i class="fa-solid fa-plus"></i> Add New Staff</button>
        </header>

        <div class="main-content">
            <div class="filters" id="filterForm">
                <div class="search-wrapper">
                    <i class="fa fa-search search-icon"></i>
                    <input type="search" id="searchInput" value="{{ request('searchUser') }}" placeholder="Search staff...">
                </div>

<select id="statusFilter" class="filter-select">
    <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All Status</option>
    <option value="active" {{ request('status', 'all') == 'active' ? 'selected' : '' }}>Active</option>
    <option value="inactive" {{ request('status', 'all') == 'inactive' ? 'selected' : '' }}>Inactive</option>
    <option value="leave" {{ request('status', 'all') == 'leave' ? 'selected' : '' }}>On Leave</option>
</select>



                <div class="menu-dropdown header-dropdown">
                    <button class="dropdown-toggle"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    <div class="header-menu">
                        <button onclick="openArchivedStaff()"><i class="fa-solid fa-box-archive"></i> Archived List</button>
                    </div>
                </div>
            </div>

            <div class="consumer-table-wrapper">
                <table class="staff-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="staffTables">
                        @foreach($users as $user)
                            <tr>
                                <td><strong>{{ $user->name }}</strong><br><small>ID: {{ $user->id }}</small></td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge badge-staff">{{ $user->role }}</span></td>
                                <td>
                                    @if($user->status == 'active')
                                        <span class="badge badge-active">Active</span>
                                    @elseif($user->status == 'inactive')
                                        <span class="badge badge-inactive">Inactive</span>
                                    @elseif($user->status == 'leave')
                                    <span class="badge badge-on_leave">On Leave</span>
                                    @endif
                                </td>
                                <td>
                                 <div class="menu-dropdown action-dropdown">
    <button class="dropdown-toggle"><i class="fa-solid fa-ellipsis-vertical"></i></button>
    <div class="dropdown-menu">

        @if($user->status === 'leave')
           
            <form action="{{ route('staff.toggleStatus', $user->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="active">
                <button type="submit" style="color:#15803d;">
                    <i class="fa-solid fa-undo"></i> Return
                </button>
            </form>

       
            <form action="{{ route('staff.archive', $user->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" style="color:#ef4444;">
                    <i class="fa-solid fa-user-minus"></i> Archive
                </button>
            </form>

        @else
       
            <button style="color:#22c55e;" onclick="openEditStaff({{ $user->id }})">
                <i class="fa-solid fa-pen-to-square"></i> Edit
            </button>

          
            @if($user->status !== 'active')
            <form action="{{ route('staff.toggleStatus', $user->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="active">
                <button type="submit" style="color:#15803d;">
                    <i class="fa-solid fa-user-check"></i> Activate
                </button>
            </form>
            @endif

        
            @if($user->status !== 'inactive')
            <form action="{{ route('staff.toggleStatus', $user->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="inactive">
                <button type="submit" style="color:#b91c1c;">
                    <i class="fa-solid fa-user-times"></i> Deactivate
                </button>
            </form>
            @endif

          
            @if($user->status !== 'leave')
            <form action="{{ route('staff.toggleStatus', $user->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="leave">
                <button type="submit" style="color:#f59e0b;">
                    <i class="fa-solid fa-plane"></i> On Leave
                </button>
            </form>
            @endif

            
            <form action="{{ route('staff.archive', $user->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" style="color:#ef4444;">
                    <i class="fa-solid fa-user-minus"></i> Archive
                </button>
            </form>
        @endif

    </div>
</div>


                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div id="editModalsWrapper">
                    @foreach($users as $user)
                        @include('modals.edit-staff', ['user' => $user])
                    @endforeach
                </div>

                <div id="noDataMessage" class="no-data-message" style="display: {{ $users->isEmpty() ? 'block' : 'none' }}">
                    <i class="fa-solid fa-users-slash"></i> No staff available.
                </div>

                  @if($users->lastPage() > 1)
                <div class="pagination-buttons">
                    <div class="pagination main-pagination">
                        <div class="results-info">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                        </div>
                      
                        <div>
                            @if ($users->onFirstPage())
                                <span class="cursor-not-allowed"><i class="fa-solid fa-chevron-left"></i> Previous</span>
                            @else
                                <a href="{{ $users->previousPageUrl() }}" class="transition"><i class="fa-solid fa-chevron-left"></i> Previous</a>
                            @endif

                            @if ($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" class="transition">Next <i class="fa-solid fa-chevron-right"></i></a>
                            @else
                                <span class="cursor-not-allowed">Next <i class="fa-solid fa-chevron-right"></i></span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

<script>
function openAddStaff() {
     document.querySelector(".add-staff").classList.add('active');
     }
function closeAddStaff() { 
    document.querySelector(".add-staff").classList.remove('active'); 
}

function openEditStaff(id) {
    const modal = document.getElementById(`edit-staff-${id}`).classList.add('active');



 
    const $city     = $(`#city-${id}`);
    const $barangay = $(`#barangay-${id}`);


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

function closeEditStaff(id) { 
    document.getElementById(`edit-staff-${id}`).classList.remove('active'); 
}

function openArchivedStaff() { document.querySelector(".archived-modal").classList.add('active'); }
function closeArchivedStaff() { document.querySelector(".archived-modal").classList.remove('active'); }

function attachDropdowns() {
  
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
        toggle.replaceWith(toggle.cloneNode(true));
    });

    document.querySelectorAll('.menu-dropdown, .action-dropdown').forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.header-menu') || dropdown.querySelector('.dropdown-menu');

        toggle.addEventListener('click', e => {
            e.stopPropagation();
         
            document.querySelectorAll('.header-menu.show, .action-dropdown.show .dropdown-menu').forEach(openMenu => {
                if(openMenu !== menu) {
                    if(openMenu.closest('.action-dropdown')) {
                        openMenu.closest('.action-dropdown').classList.remove('show');
                    } else {
                        openMenu.classList.remove('show');
                    }
                }
            });
            if(dropdown.classList.contains('action-dropdown')) {
                dropdown.classList.toggle('show');
            } else {
                menu.classList.toggle('show');
            }
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.header-menu.show, .action-dropdown.show .dropdown-menu').forEach(menu => {
            if(menu.closest('.action-dropdown')) {
                menu.closest('.action-dropdown').classList.remove('show');
            } else {
                menu.classList.remove('show');
            }
        });
    });
}

$(document).ready(function() {
    let debounceMain;
   attachDropdowns();
    function fetchMainData(page_main = 1) {
        let searchUser = $('#searchInput').val();
        let status = $('#statusFilter').val();

        $.ajax({
            url: "{{ route('staff.index') }}",
            type: "GET",
            data: { searchUser: searchUser, status: status, page_main: page_main },
            success: function(response) {
                $('#staffTables').html($(response.html).find('#staffTables').html());
                $('#editModalsWrapper').html($(response.html).find('#editModalsWrapper').html());
                $('.main-pagination').html($(response.html).find('.main-pagination').html());

                attachDropdowns();
                $('#statusFilter').val(status); 
            }
        });
    }

    $('#searchInput').on('keyup', function() {
        clearTimeout(debounceMain);
        debounceMain = setTimeout(fetchMainData, 400);

           attachDropdowns();
    });


    $('#statusFilter').on('change', function() {
        fetchMainData();
           attachDropdowns();
    });

    $(document).on('click', '.main-pagination a', function(e) {
        e.preventDefault();
        let page_main = $(this).attr('href').split('page_main=')[1] || 1;
        fetchMainData(page_main);
           attachDropdowns();
    });
});


</script>
</body>
</html>
