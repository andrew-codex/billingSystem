<link rel="stylesheet" href="{{asset('/CSS_Styles/modalCSS/manage-linemen.css')}}">


<div id="manageGroupsModal" class="group-modal-overlay">
    <div class="manage-groups-container">
        <div class="manage-groups-header">
            <div class="header-content">
                <div class="modal-icon">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <div class="header-text">
                    <h3>Manage Groups</h3>
                    <p style="color:#f8fafc;">View, edit, and organize your line men groups</p>
                </div>
            </div>
            <button type="button" class="group-close-btn" onclick="closeManageGroups()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="manage-groups-body">
            <div class="groups-toolbar">
                <div class="search-wrapper">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" id="groupSearch" placeholder="Search groups...">
                </div>
                <button class="btn-add-group" onclick="openAddGroup(); closeManageGroups();">
                    <i class="fa-solid fa-plus"></i> New Group
                </button>
            </div>

<div class="groups-list" id="groupsList">
    @if(isset($groups) && $groups->count() > 0)
        @foreach($groups as $group)
            <div class="group-item"
                 data-group-id="{{ $group->id }}"
                 data-update-url="{{ route('groups.update', $group->id) }}">
                <div class="group-info">
                    <div class="group-avatar"><i class="fa-solid fa-users"></i></div>
                    <div class="group-details">
                        <h4 class="group-name-text">{{ $group->group_name }}</h4>
                        <p class="group-description">{{ $group->description ?? 'No description' }}</p>
                        <span class="member-count">{{ $group->linemen_count ?? 0 }} members</span>
                    </div>
                </div>
                <div class="group-actions">
                    <button type="button" class="btn-icon" onclick="openEditGroup(this)" title="Edit Group">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                  
                    <form method="POST"
                          action="{{ route('groups.destroyGroup', $group->id) }}"
                          class="delete-group-form"
                          data-group-name="{{ addslashes($group->group_name) }}"
                          style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon btn-danger" title="Delete Group">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    @else
                    <div class="empty-groups">
                        <i class="fa-solid fa-users-slash"></i>
                        <h4>No Groups Found</h4>
                        <p>Create your first group to organize line men</p>
                        <button class="btn-primary" onclick="openAddGroup(); closeManageGroups();">
                            <i class="fa-solid fa-plus"></i> Create First Group
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


<div id="editGroupModal" class="group-modal-overlay" style="display:none; z-index:6500;">
    <div class="group-modal-container">
        <div class="group-modal-header">
            <div class="header-content">
                <div class="modal-icon">
                    <i class="fa-solid fa-pen"></i>
                </div>
                <div class="header-text">
                    <h3>Edit Group</h3>
                    <p>Update group information</p>
                </div>
            </div>
            <button type="button" class="group-close-btn" onclick="closeEditGroup()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="group-modal-body">
            <form id="editGroupForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" id="editGroupId" name="group_id">
                <div class="input-wrapper">
                    <label for="edit_group_name" class="input-label">
                        <i class="fa-solid fa-tag"></i> Group Name
                    </label>
                    <input type="text" name="group_name" id="edit_group_name" class="group-input" required>
                </div>
                <div class="input-wrapper">
                    <label for="edit_description" class="input-label">
                        <i class="fa-solid fa-align-left"></i> Description
                    </label>
                    <textarea name="description" id="edit_description" class="group-textarea" rows="4"></textarea>
                </div>
                <div class="group-modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeEditGroup()">
                        <i class="fa-solid fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-save"></i> Update Group
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

function openManageGroups() {
    const m = document.getElementById('manageGroupsModal');
    m.style.display = 'flex';
    m.classList.add('is-open');
}
function closeManageGroups() {
    const m = document.getElementById('manageGroupsModal');
    m.style.display = 'none';
    m.classList.remove('is-open');
}
function closeEditGroup() {
    document.getElementById('editGroupModal').style.display = 'none';
}


function openEditGroup(btn) {
    const item = btn.closest('.group-item');
    if (!item) return;

    const id = item.dataset.groupId;
    const action = item.dataset.updateUrl || `/groups/${id}`;

    const name = item.querySelector('.group-name-text')?.textContent.trim() || '';
    const descEl = item.querySelector('.group-description');
    const rawDesc = descEl ? descEl.textContent.trim() : '';
    const desc = (rawDesc === 'No description') ? '' : rawDesc;

    const form = document.getElementById('editGroupForm');
    form.action = action;
    document.getElementById('editGroupId').value = id;
    document.getElementById('edit_group_name').value = name;
    document.getElementById('edit_description').value = desc;

    const overlay = document.getElementById('editGroupModal');
    overlay.style.display = 'flex';
    setTimeout(() => document.getElementById('edit_group_name').focus(), 50);
}



document.addEventListener('submit', function(e) {
    const form = e.target;
    if (!form.classList.contains('delete-group-form')) return;

    e.preventDefault();
    const groupName = form.dataset.groupName || 'this group';

    if (window.Swal) {
        Swal.fire({
            title: 'Delete Group?',
            html: `Group: <strong>${groupName}</strong><br><small>This cannot be undone.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Delete',
            reverseButtons: true
        }).then(res => { if (res.isConfirmed) form.submit(); });
    } else {
        if (confirm(`Delete group "${groupName}"? This cannot be undone.`)) form.submit();
    }
});




const searchInput = document.getElementById('groupSearch');
if (searchInput) {
    searchInput.addEventListener('input', e => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.group-item').forEach(item => {
            const n = (item.querySelector('.group-name-text')?.textContent || '').toLowerCase();
            const d = (item.querySelector('.group-description')?.textContent || '').toLowerCase();
            item.style.display = (n.includes(term) || d.includes(term)) ? 'flex' : 'none';
        });
    });
}

document.addEventListener('click', e => {
    const mg = document.getElementById('manageGroupsModal');
    const eg = document.getElementById('editGroupModal');
    if (e.target === mg) closeManageGroups();
    if (e.target === eg) closeEditGroup();
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeEditGroup();
        closeManageGroups();
    }
});
</script>