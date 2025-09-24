<link rel="stylesheet" href="{{asset('/CSS_Styles/modalCSS/archived.css')}}">
<link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
 
<div class="archived-modal" id="archivedModal">
    <div class="modal-content">
        <button class="close-btn" onclick="closeArchivedList()"><i class="fa-solid fa-xmark"></i></button>
        <h2 class="archived-title">Archived Users</h2>

        @if($archivedUsers->isEmpty())
            <p class="no-archived">No archived users available.</p>
        @else
            <div class="bulk-actions" id="bulkActions" style="display:none; margin-bottom:10px; text-align:right;">
                <button type="button" class="btn-restore" id="bulkRestore">Restore Selected</button>
                <button type="button" class="btn-delete" id="bulkDelete">Delete Selected</button>
            </div>

            

            <div class="table-wrapper">
                <table class="archived-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Archived At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($archivedUsers as $user)
                            <tr>
                                <td><input type="checkbox" class="user-checkbox" value="{{ $user->id }}"></td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td>{{ $user->updated_at->format('M d, Y') }}</td>
                                <td>
                              
                                    <form action="{{ route('users.restore', $user->id) }}" method="POST" class="restore-form" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="btn-restore restore-btn">Restore</button>
                                    </form>

                              
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="delete-form" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-delete delete-btn">Delete Permanently</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
function openArchivedList() {
    document.getElementById("archivedModal").classList.add("active");
     fetchArchivedUsers();
}

function closeArchivedList() {
    document.getElementById("archivedModal").classList.remove("active");
}



const bulkRestoreUrl = "{{ route('users.bulkRestore') }}";
const bulkDeleteUrl = "{{ route('users.bulkDelete') }}";

document.addEventListener("DOMContentLoaded", function () {
    const selectAll = document.getElementById("selectAll");
    const bulkActions = document.getElementById("bulkActions");
    const bulkRestore = document.getElementById("bulkRestore");
    const bulkDelete = document.getElementById("bulkDelete");
    const singleRestoreBtns = document.querySelectorAll(".restore-btn");
    const singleDeleteBtns = document.querySelectorAll(".delete-btn");

    function updateBulkActions() {
        const checkboxes = document.querySelectorAll(".user-checkbox");
        const selected = document.querySelectorAll(".user-checkbox:checked").length;

        if (selected >= 2) {
            bulkActions.style.display = "block";
            singleRestoreBtns.forEach(btn => btn.disabled = true);
            singleDeleteBtns.forEach(btn => btn.disabled = true);
        } else {
            bulkActions.style.display = "none";
            singleRestoreBtns.forEach(btn => btn.disabled = false);
            singleDeleteBtns.forEach(btn => btn.disabled = false);
        }

        selectAll.checked = selected > 0 && selected === checkboxes.length;
    }

    if (selectAll) {
        selectAll.addEventListener("change", function () {
            document.querySelectorAll(".user-checkbox").forEach(cb => cb.checked = this.checked);
            updateBulkActions();
        });
    }

    document.querySelectorAll(".user-checkbox").forEach(cb => {
        cb.addEventListener("change", updateBulkActions);
    });


    bulkRestore.addEventListener("click", function () {
        const selectedIds = [...document.querySelectorAll(".user-checkbox:checked")].map(cb => cb.value);
        if (selectedIds.length < 2) return;

        Swal.fire({
            title: "Restore Users?",
            text: `You are about to restore ${selectedIds.length} users.`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#2563eb",
            cancelButtonColor: "#6b7280",
            confirmButtonText: "Yes, restore"
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.createElement("form");
                form.action = bulkRestoreUrl;
                form.method = "POST";
                form.style.display = "none";

                const csrfInput = document.createElement("input");
                csrfInput.type = "hidden";
                csrfInput.name = "_token";
                csrfInput.value = "{{ csrf_token() }}";
                form.appendChild(csrfInput);

                selectedIds.forEach(id => {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "ids[]";
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        });
    });


    bulkDelete.addEventListener("click", function () {
        const selectedIds = [...document.querySelectorAll(".user-checkbox:checked")].map(cb => cb.value);
        if (selectedIds.length < 2) return;

        Swal.fire({
            title: "Delete Users?",
            text: `You are about to permanently delete ${selectedIds.length} users. This cannot be undone.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc2626",
            cancelButtonColor: "#6b7280",
            confirmButtonText: "Yes, delete"
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.createElement("form");
                form.action = bulkDeleteUrl;
                form.method = "POST";
                form.style.display = "none";

                const csrfInput = document.createElement("input");
                csrfInput.type = "hidden";
                csrfInput.name = "_token";
                csrfInput.value = "{{ csrf_token() }}";
                form.appendChild(csrfInput);

                selectedIds.forEach(id => {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "id[]";
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    
    function bindRestoreDelete() {
        document.querySelectorAll(".restore-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                const form = this.closest("form");
                Swal.fire({
                    title: "Restore User?",
                    text: "This user will be restored and reactivated.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#2563eb",
                    cancelButtonColor: "#6b7280",
                    confirmButtonText: "Yes, restore"
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        document.querySelectorAll(".delete-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                const form = this.closest("form");
                Swal.fire({
                    title: "Delete Permanently?",
                    text: "This action cannot be undone.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc2626",
                    cancelButtonColor: "#6b7280",
                    confirmButtonText: "Yes, delete"
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    }

    bindRestoreDelete();
});
</script>
