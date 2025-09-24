<link rel="stylesheet" href="{{asset('/CSS_Styles/modalCSS/archive-meter.css')}}">
<link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">



<div class="archive-modal" id="archiveModal">
    <div class="archive-modal-content">
       
        <button class="close-btn" onclick="closedArchivedMeter()">  <i class="fa-solid fa-xmark"></i></button>

        <h2 class="archived-title">Archived Electric Meters</h2>

        
        <div class="archive-wrapper" id="archiveTableWrapper">
            <div class="archive-controls">
                <input type="text" value="{{request('search_archive') }}"  id="searchInput" placeholder="Search...">

                <form id="bulkDeleteForm" action="{{ route('electricMeter.bulkDelete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" id="bulkDeleteBtn" class="btn-delete">Delete Selected</button>
                </form>
            </div>

            <table id="archiveMeterTable" class="archived-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Consumer ID</th>
                        <th>Meter Number</th>
                        <th>Date Added</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr> 
                </thead>
                <tbody id="archiveTableContent">
                    @foreach($archivedMeters as $meter)
                    <tr> 
                        <td><input type="checkbox" name="ids[]" value="{{ $meter->id }}" form="bulkDeleteForm"></td>
                        <td>{{ $meter->consumer_id }}</td>
                        <td>{{ $meter->electric_meter_number }}</td>
                        <td>{{ $meter->created_at->format('M d, Y') }}</td>
                        <td><span class="status archived">Archived</span></td>
                        <td>
                            <form action="{{ route('electricMeter.destroy', $meter->id) }}" method="POST" class="deleteForm inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete deleteBtn">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                 
                    @endforeach
                </tbody>
            </table>
 @if($archivedMeters->isEmpty())
    <div class="empty-message" id="noResult">
        No records found
    </div>
@endif

<div class="archive-pagination" id="archivePagination">
    <div class="results-info">
        Showing {{ $archivedMeters->count() }} of {{ $archivedMeters->total() }} archived meters
    </div>
    <div class="pagination-links">
        @if ($archivedMeters->onFirstPage())
            <span class="disabled"><i class="fa-solid fa-chevron-left"></i> Previous</span>
        @else
            <a href="{{ $archivedMeters->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i> Previous</a>
        @endif

        @if ($archivedMeters->hasMorePages())
            <a href="{{ $archivedMeters->nextPageUrl() }}">Next <i class="fa-solid fa-chevron-right"></i></a>
        @else
            <span class="disabled">Next <i class="fa-solid fa-chevron-right"></i></span>
        @endif
    </div>
</div>



        </div>
    </div>
</div>

<script>$(document).on('click', '.deleteBtn', function() {
    let form = $(this).closest('.deleteForm')[0]; 
    Swal.fire({
        title: "Delete this record?",
        text: "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e74c3c",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) form.submit();
    });
});


document.getElementById('bulkDeleteBtn').addEventListener('click', function () {
    Swal.fire({
        title: "Delete selected records?",
        text: "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e74c3c",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, delete them!"
    }).then((result) => {
        if (result.isConfirmed) document.getElementById('bulkDeleteForm').submit();
    });
});

function updateBulkDeleteVisibility() {
    let selectedCount = document.querySelectorAll('input[name="ids[]"]:checked').length;
    let bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    if (selectedCount >= 2) {  
        bulkDeleteBtn.style.display = "inline-block";
    } else {
        bulkDeleteBtn.style.display = "none";
    }
}


document.querySelectorAll('input[name="ids[]"]').forEach(cb => {
    cb.addEventListener('change', updateBulkDeleteVisibility);
});


document.getElementById('selectAll').addEventListener('change', function () {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = this.checked);
    updateBulkDeleteVisibility();
});


updateBulkDeleteVisibility();

    let debounceArchive;

function fetchArchiveData(page_archive = 1) {
    let search_archive = $('#searchInput').val();

    $.ajax({
        url: "{{ route('electricMeter.index') }}",
        type: "GET",
        data: { search_archive: search_archive, page_archive: page_archive },
     success: function(response) {
  
    $('#archiveTableContent').html($(response.html).find('#archiveTableContent').html());
    
 
    $('#archivePagination').html($(response.html).find('#archivePagination').html());

    updateBulkDeleteVisibility();
}

    });
}


$('#searchInput').on('keyup', function() {
    clearTimeout(debounceArchive);
    debounceArchive = setTimeout(fetchArchiveData, 400);
});

$(document).on('click', '.archive-pagination a', function(e) {
    e.preventDefault();
    let page_archive = $(this).attr('href').split('page_archive=')[1] || 1;
    fetchArchiveData(page_archive);
});
</script>