 function openArchivedList() {
        document.getElementById("archivedModal").classList.add("active");
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

        selectAll.checked = (selected > 0 && selected === checkboxes.length);
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
        const selected = [...document.querySelectorAll(".user-checkbox:checked")].map(cb => cb.value);
        if (selected.length < 2) return;

        Swal.fire({
            title: "Restore Users?",
            text: `You are about to restore ${selected.length} users.`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#2563eb",
            cancelButtonColor: "#6b7280",
            confirmButtonText: "Yes, restore"
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.getElementById("bulkActionForm");
                form.action = bulkRestoreUrl; // ✅ use resolved URL
                form.method = "POST";

                form.querySelectorAll("input[name='ids[]']").forEach(el => el.remove());
                selected.forEach(id => {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "ids[]";
                    input.value = id;
                    form.appendChild(input);
                });

                form.submit();
            }
        });
    });

    bulkDelete.addEventListener("click", function () {
        const selected = [...document.querySelectorAll(".user-checkbox:checked")].map(cb => cb.value);
        if (selected.length < 2) return;

        Swal.fire({
            title: "Delete Users?",
            text: `You are about to permanently delete ${selected.length} users. This cannot be undone.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc2626",
            cancelButtonColor: "#6b7280",
            confirmButtonText: "Yes, delete"
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.getElementById("bulkActionForm");
                form.action = bulkDeleteUrl; // ✅ use resolved URL
                form.method = "POST";

                form.querySelectorAll("input[name='ids[]']").forEach(el => el.remove());
                selected.forEach(id => {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "ids[]";
                    input.value = id;
                    form.appendChild(input);
                });

                form.submit();
            }
        });
    });

    // Single restore/delete buttons
    bindRestoreDelete();
});

