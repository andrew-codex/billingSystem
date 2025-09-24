document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const roleButtons = document.querySelectorAll('.role-btn');
    const statusDropdown = document.getElementById('statusFilter');
    const tbody = document.getElementById('staffTbody');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || window.csrfToken;
    let typingTimer;

  
    function attachDropdownListeners() {
        document.querySelectorAll('.action-dropdown').forEach(dropdown => {
            const button = dropdown.querySelector('.dropdown-toggle');
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.action-dropdown').forEach(d => {
                    if (d !== dropdown) d.classList.remove('show');
                });
                dropdown.classList.toggle('show');
            });
        });
    }

    document.addEventListener('click', () => {
        document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('show'));
    });

    const toggleUrl = (id) => `/staff/${id}/toggle-status`;
    const archiveUrl = (id) => `/staff/${id}/archive`;

 
    function submitAction(userId, action, status = null) {
        const url = action === 'archive' ? archiveUrl(userId) : toggleUrl(userId);
        const formData = new FormData();
        if(status) formData.append('status', status);
        formData.append('_token', csrfToken);
        formData.append('_method', 'PATCH');

        fetch(url, {
            method: 'POST', // use POST with _method=PATCH
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                const activeRole = document.querySelector('.role-btn.active')?.dataset.role || 'all';
                const activeStatus = statusDropdown.value || 'all';
                fetchUsers(searchInput.value, activeRole, activeStatus);
            } else {
                alert(res.message || "Failed to update status.");
            }
        })
        .catch(err => console.error(err));
    }

    // Render action buttons
    function renderActionButtons(user) {
        let buttons = `
            <button 
                class="edit-btn"
                data-id="${user.id}"
                data-name="${user.name}"
                data-email="${user.email ?? ''}"
                data-phone="${user.phone ?? ''}"
                data-address="${user.address ?? ''}"
                data-role="${user.role}"
                onclick="openEditModal(this)">
                <i class="fa-solid fa-pen-to-square"></i> Edit
            </button>
        `;

        if(user.status === 'active') {
            buttons += `
                <button onclick="submitAction(${user.id}, 'toggle', 'inactive')" style="color:#ef4444;">
                    <i class="fa-solid fa-user-minus"></i> Deactivate
                </button>
                <button onclick="submitAction(${user.id}, 'toggle', 'leave')">
                    <i class="fa-solid fa-calendar-xmark"></i> Leave
                </button>
            `;
        } else if(user.status === 'inactive') {
            buttons += `
                <button onclick="submitAction(${user.id}, 'toggle', 'active')" style="color:#22c55e;">
                    <i class="fa-solid fa-check"></i> Activate
                </button>
                <button onclick="submitAction(${user.id}, 'archive')">
                    <i class="fa-solid fa-box-archive"></i> Archive
                </button>
            `;
        } else if(user.status === 'leave') {
            buttons += `
                <button onclick="submitAction(${user.id}, 'toggle', 'active')">
                    <i class="fa-solid fa-calendar-check"></i> Return from Leave
                </button>
                <button onclick="submitAction(${user.id}, 'archive')">
                    <i class="fa-solid fa-box-archive"></i> Archive
                </button>
            `;
        }

        return buttons;
    }

    // Fetch users from server
    function fetchUsers(query = '', role = 'all', status = 'all') {
        fetch(`/staff/search?query=${encodeURIComponent(query)}&role=${role}&status=${status}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(response => {
            tbody.innerHTML = '';
            const users = response.data;
            if(!users || users.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; color:#888;">No users found.</td></tr>`;
            } else {
                users.forEach(user => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>${user.address ?? ''}</td>
                            <td>${user.status === 'active' ? '<span class="status-badge active">Active</span>' :
                                 user.status === 'inactive' ? '<span class="status-badge inactive">Inactive</span>' :
                                 '<span class="status-badge leave">On Leave</span>'}</td>
                            <td>${user.role}</td>
                            <td>
                                <div class="action-dropdown">
                                    <button class="dropdown-toggle">⋮</button>
                                    <div class="dropdown-menu">
                                        ${renderActionButtons(user)}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                attachDropdownListeners();
            }
        })
        .catch(err => console.error(err));
    }

    // Search input
    searchInput.addEventListener('input', () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            const activeRole = document.querySelector('.role-btn.active')?.dataset.role || 'all';
            const activeStatus = statusDropdown.value || 'all';
            fetchUsers(searchInput.value, activeRole, activeStatus);
        }, 300);
    });

    // Role buttons
    roleButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            roleButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const activeStatus = statusDropdown.value || 'all';
            fetchUsers(searchInput.value, btn.dataset.role, activeStatus);
        });
    });

    // Status dropdown
    statusDropdown.addEventListener('change', () => {
        const activeRole = document.querySelector('.role-btn.active')?.dataset.role || 'all';
        fetchUsers(searchInput.value, activeRole, statusDropdown.value || 'all');
    });

    attachDropdownListeners();
});