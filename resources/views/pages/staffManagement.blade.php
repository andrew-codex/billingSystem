<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('/CSS_Styles/mainCss/base.css') }}">
<link rel="stylesheet" href="{{ asset('/CSS_Styles/mainCss/staffManagement.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Staff Management</title>
</head>
<body>

@include('includes.sidebar')
@include('includes.alerts')
        @include('modals.add-staff')  
        @include('modals.edit-staff')
        @include('modals.archived-staff')

<div class="content">
    <header>
        <h2>
            <a href="{{route('staff.index')}}" class="title"><i class="fa-solid fa-user"></i> Staff Management</a>
        </h2>
        <button onclick="openAddStaff()"> <i class="fa-solid fa-plus"></i> Add New Staff</button>
    </header>

    <div class="main-content">
        
        <div class="filters" id="filterForm">
            <div class="search-wrapper">
                <i class="fa fa-search search-icon"></i>
                <input type="search" id="searchInput" placeholder="Search staff...">
            </div>

            <select id="statusFilter" class="filter-select">
                <option value="all">All</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="leave">On Leave</option>
            </select>


                <div class="header-dropdown">
                    <button class="header-ropdown-toggle"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    <div class="header-menu">
                        <button onclick="openArchivedList()"><i class="fa-solid fa-box-archive"></i> Archived</button>
                    </div>
                </div>
        </div>

        <div class="table-card staff-table">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="staffTbody"></tbody>
            </table>
        </div>
         <div class="staff-footer">
            <div class="staff-summary"></div>
            <div class="pagination"></div>
        </div>
         </div>
    </div>
</div>

<script>
function attachHeaderDropdown() {
    const dropdown = document.querySelector('.header-dropdown');
    if (!dropdown) return;

    const toggle = dropdown.querySelector('.header-ropdown-toggle');
    const menu = dropdown.querySelector('.header-menu');


    toggle.addEventListener('click', e => {
        e.stopPropagation();
        menu.classList.toggle('show'); 
    });

   
    document.addEventListener('click', () => {
        menu.classList.remove('show');
    });
}


document.addEventListener('DOMContentLoaded', attachHeaderDropdown);





document.addEventListener('DOMContentLoaded', () => {
    const editRouteTemplate = "{{ route('staff.update', ['id' => ':id']) }}";
    const searchInput = document.getElementById('searchInput');
    const roleButtons = document.querySelectorAll('.role-btn');
    const statusDropdown = document.getElementById('statusFilter');
    const tbody = document.getElementById('staffTbody');
    const paginationContainer = document.querySelector('.pagination');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let typingTimer;

   
    function fetchUsers(query = '', role = 'all', status = 'all', page = 1) {
        fetch(`/staff/search?query=${encodeURIComponent(query)}&role=${role}&status=${status}&page=${page}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(response => {
            tbody.innerHTML = '';
            const users = response.data;

            const summary = document.querySelector('.staff-summary');
            if (response.total > 0) {
                summary.textContent = `Showing ${response.count} of ${response.total} staff members`;
            } else {
                summary.textContent = 'No staff members found';
            }

            if (users.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;color:#888;">No users found.</td></tr>`;
                paginationContainer.innerHTML = '';
                return;
            }

            users.forEach(user => {
                tbody.innerHTML += `
                    <tr>
                        <td style="color: #1e293b;">${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.address ?? ''}</td>
                        <td>${user.status === 'active' ? '<span class="status-badge active">Active</span>' 
                             : user.status === 'inactive' ? '<span class="status-badge inactive">Inactive</span>' 
                             : '<span class="status-badge leave">On Leave</span>'}</td>
                        <td>${user.role}</td>
                        <td>${renderActionButtons(user)}</td>
                    </tr>`;
            });

            paginationContainer.innerHTML = response.pagination;
            attachPaginationListeners();
            attachDropdownListeners();
        })
        .catch(err => console.error(err));
    }

  
    function renderActionButtons(user) {
        return `
        <div class="action-dropdown">
            <button class="dropdown-toggle"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            <div class="dropdown-menu">
                <button class="edit-btn" 
                    data-id="${user.id}" 
                    data-name="${user.name}" 
                    data-email="${user.email ?? ''}" 
                    data-phone="${user.phone ?? ''}" 
                    data-address="${user.address ?? ''}" 
                    data-role="${user.role}">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </button>
                ${user.status === 'active' ? `
                    <button style="color:#ef4444;" data-action="toggle" data-id="${user.id}" data-status="inactive"><i class="fa-solid fa-user-minus"></i> Deactivate</button>
                    <button data-action="toggle" data-id="${user.id}" data-status="leave"><i class="fa-solid fa-calendar"></i> Leave</button>
                ` : user.status === 'inactive' ? `
                    <button style="color:#22c55e;" data-action="toggle" data-id="${user.id}" data-status="active"><i class="fa-solid fa-check"></i> Activate</button>
                    <button style="color:#ef4444;" data-action="archive" data-id="${user.id}"><i class="fa-solid fa-user-xmark"></i> Archive</button>
                ` : `
                    <button data-action="toggle" data-id="${user.id}" data-status="active"><i class="fa-solid fa-calendar-days"></i> Return</button>
                    <button style="color:#ef4444;" data-action="archive"  data-id="${user.id}"><i class="fa-solid fa-user-xmark"></i> Archive</button>
                `}
            </div>
        </div>`;
    }

   
    function submitAction(userId, action, status = null) {
        const url = action === 'archive' ? `/staff/archive/${userId}` : `/staff/toggle-status/${userId}`;
        const data = status ? { status } : {};
        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if(res.success){
                Swal.fire({ icon: 'success', title: 'Success', text: res.message || 'Action completed', timer: 1500, showConfirmButton: false });
   if(action === 'archive') {
               
                setTimeout(() => {
                    location.reload();
                }, 500); 
            } else {
                refreshUsers();
            }
             
            } else {
                Swal.fire('Error', res.message || 'Action failed', 'error');
            }
        })
        .catch(err => Swal.fire('Error', 'Server error', 'error'));
    }

    
    tbody.addEventListener('click', function(e) {
        const target = e.target.closest('button');
        if(!target) return;

      
        const action = target.dataset.action;
        const userId = target.dataset.id;
        const status = target.dataset.status;
        if(action === 'toggle' || action === 'archive') {
            submitAction(userId, action, status);
            target.closest('.action-dropdown')?.classList.remove('show');
        }

      
        if(target.classList.contains('edit-btn')){
            const modal = document.getElementById('editStaffModal');
            modal.style.display = 'flex';

            modal.querySelector('#edit_name').value = target.dataset.name;
            modal.querySelector('#edit_email').value = target.dataset.email;
            modal.querySelector('#edit_phone').value = target.dataset.phone ?? '';
            modal.querySelector('#edit_address').value = target.dataset.address ?? '';
            modal.querySelector('#edit_role').value = target.dataset.role;
            modal.querySelector('#edit_password').value = '';

            const form = modal.querySelector('#editStaffForm');
            form.action = editRouteTemplate.replace(':id', target.dataset.id);
        }
    });

    
    function attachDropdownListeners() {
        document.querySelectorAll('.action-dropdown').forEach(dropdown => {
            dropdown.querySelector('.dropdown-toggle').addEventListener('click', e => {
                e.stopPropagation();
                document.querySelectorAll('.action-dropdown').forEach(d => d !== dropdown && d.classList.remove('show'));
                dropdown.classList.toggle('show');
            });
        });
    }

    document.addEventListener('click', () => document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('show')));

 
    function attachPaginationListeners() {
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const page = new URL(link.href).searchParams.get('page') || 1;
                refreshUsers(page);
            });
        });
    }

   
    searchInput.addEventListener('input', () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(refreshUsers, 300);
    });
    roleButtons.forEach(btn => btn.addEventListener('click', () => {
        roleButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        refreshUsers();
    }));
    statusDropdown.addEventListener('change', refreshUsers);


    refreshUsers();

    function refreshUsers(page = 1){
        const activeRole = document.querySelector('.role-btn.active')?.dataset.role || 'all';
        const activeStatus = statusDropdown.value || 'all';
        fetchUsers(searchInput.value, activeRole, activeStatus, page);
    }
});


function closeEditModal() {
    document.getElementById('editStaffModal').style.display = 'none';
}


window.onclick = function(event) {
    const modal = document.getElementById('editStaffModal');
    if (event.target === modal) closeEditModal();
}
</script>
</body>
</html>
