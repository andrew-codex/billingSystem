<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Meter Inventory</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
    <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/electricmeterInven.css')}}">
</head>
<body>
@include('includes.sidebar')
@include('modals.add-electricMeter')
@include('includes.alerts')
@include('modals.edit-electricMeter')
@include('modals.archived-meter')

<div class="content">

    <header>
        <h2><a href="{{route('electricMeter.index')}}" class="title">
             <i class="fa-solid fa-layer-group"></i> Electric Meter Inventory
        </a>

        </h2>
      
    </header>


    <div class="main-content">
        
        <div class="filters">
            
                <div class="search-wrapper">
                    <i class="fa fa-search search-icon"></i>
                    <input type="search" value="{{request('search') }}" id="search" placeholder="Search electric meters...">
                </div>

    


            <select id="statusFilter" class="filter-select"> 
                <option value="all"> All</option>
                <option value="active"> Assigned</option>
                <option value="unassigned"> Unassigned</option>
                <option value="damaged"> Damaged</option>
            </select>

                <div class="header-dropdown">
                    <button class="header-ropdown-toggle"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    <div class="header-menu">
                        <button onclick="openArchivedMeter()"><i class="fa-solid fa-box-archive"></i> Archived List</button>
                    </div>
                </div>
        </div>



        <div class="table-wrapper" id="mainTableWrapper">
            <table id="electricMeterTable">
                <thead>
                    <tr>
                        <th>Consumer ID</th>
                        <th>Meter Number</th>
                        <th>Date added</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr> 
                </thead>
                <tbody id="mainTableContent">
                    
                    @foreach($electricMeters as $meter)
                    <tr> 
                        <td>{{ $meter->consumer_id }}</td>
                        <td>{{ $meter->electric_meter_number }}</td>
                        <td>{{ $meter->created_at->format('M d, Y') }}</td>
                     
                        <td>
                            @if($meter->status == 'active')
                                <span class="status active">Assigned</span>
                            @elseif($meter->status == 'unassigned')
                                <span class="status unassigned">Unassigned</span>
                            @elseif($meter->status == 'damaged')
                                <span class="status damaged">Damaged</span>
                            @elseif($meter->status == 'disconnected')
                                <span class="status disconnected">Disconnected</span>
                            @endif
                        </td>
                        <td class="actions">

                            <div class="table-dropdown">
                                <button class="table-dropdown-toggle"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                <div class="table-menu">
                                        <button type="button" class="edit-btn"
                                                onclick="openEditModal('{{ $meter->id }}', '{{ $meter->electric_meter_number }}', '{{ $meter->created_at->format('M d, Y') }}')">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            Edit
                                        </button>

                                     <form action="{{route('electric-meters.archive' , $meter->id)}}" method="POST"  style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="delete-btn"><i class="fa-solid fa-trash"></i> Archive</button>
                                     </form>
                                </div>
                            </div>
      

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination-buttons">
               <div class="pagination main-pagination">

                  <div class="results-info"> 
                    Showing {{ $electricMeters->firstItem() }} to {{ $electricMeters->lastItem() }} of {{ $electricMeters->total() }} results
                 </div>

                 <div>
                    @if ($electricMeters->onFirstPage())
                        <span class=" cursor-not-allowed">
                            <i class="fa-solid fa-chevron-left"></i> Previous
                        </span>
                    @else
                        <a href="{{ $electricMeters->previousPageUrl() }}" 
                        class=" transition">
                            <i class="fa-solid fa-chevron-left"></i> Previous
                        </a>
                    @endif


                    @if ($electricMeters->hasMorePages())
                        <a href="{{ $electricMeters->nextPageUrl() }}" 
                        class=" transition">
                            Next <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    @else
                        <span class=" cursor-not-allowed">
                            Next <i class="fa-solid fa-chevron-right"></i>
                        </span>
                    @endif

                 </div>
  
                    
                </div>

                </div>
         </div> 
    </div>  
<script>
    



    function openAddMeter(){
        document.querySelector(".add-electricMeter").classList.add('active');
    }

    function closedAddMeter(){
        document.querySelector(".add-electricMeter").classList.remove('active');
    }


    function openArchivedMeter(){
        document.querySelector(".archive-modal").classList.add('active');
    }

    function closedArchivedMeter(){
        document.querySelector(".archive-modal").classList.remove('active');
    }




function openEditModal(id, number, createdAt) {
    document.querySelector(".edit-meter").classList.add('active');
    document.getElementById('editForm').action = "/electricMeterUpdate/" + id;
    document.getElementById('edit_number').value = number;
    document.getElementById('edit_created').value = createdAt;

    
}
function closeEditModal() {
    document.querySelector(".edit-meter").classList.remove('active');
}

function attachHeaderToggle() {
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


document.addEventListener('DOMContentLoaded', attachHeaderToggle);



function attachHeaderDropdown() {
    const dropdowns = document.querySelectorAll('.table-dropdown'); 
    if (!dropdowns.length) return;

    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.table-dropdown-toggle');
        const menu = dropdown.querySelector('.table-menu');

        toggle.addEventListener('click', e => {
            e.stopPropagation();

           
            document.querySelectorAll('.table-menu.show').forEach(openMenu => {
                if (openMenu !== menu) openMenu.classList.remove('show');
            });

            menu.classList.toggle('show');
        });

   
        document.addEventListener('click', () => {
            menu.classList.remove('show');
        });
    });
}

document.addEventListener('DOMContentLoaded', attachHeaderDropdown);




$(document).ready(function() {
    let debounceMain;

  
    function fetchMainData(page_main = 1) {
        let search = $('#search').val();
        let status = $('#statusFilter').val();

        $.ajax({
            url: "{{ route('electricMeter.index') }}",
            type: "GET",
            data: { search: search, status: status, page_main: page_main },
            success: function(response) {
              
                $('#mainTableContent').html($(response.html).find('#mainTableContent').html());

             
                $('.main-pagination').html($(response.html).find('.main-pagination').html());

           
                attachHeaderDropdown();
            }
        });
    }

 
    $('#search').on('keyup', function() {
        clearTimeout(debounceMain);
        debounceMain = setTimeout(fetchMainData, 400);
    });

 
    $('#statusFilter').on('change', function() {
        fetchMainData();
    });


    $(document).on('click', '.main-pagination a', function(e) {
        e.preventDefault();
        let page_main = $(this).attr('href').split('page_main=')[1] || 1;
        fetchMainData(page_main);
    });
});

    
</script>
</body>
</html>