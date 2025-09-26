<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Plugin JS -->
<script src="https://cdn.jsdelivr.net/npm/philippine-address-selector@latest/dist/philippine-address-selector.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('/JsFiles/ph-address-selector.js') }}"></script>
        <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/consumer.css')}}">
    <title>Consumer & Account Management</title>
</head>
<body>
    @include('includes.sidebar')
    @include('modals.add-consumer')
    @include('includes.alerts')
    @include('modals.edit-consumer')
    @include('modals.archived-consumer')
    @include('modals.assignNew-meter')
    <!-- @include('modals.meter-history') -->
<div class="content">


    <header>
        <h2><a href="{{route('consumer.index')}}" class="title">
             <i class="fa-solid fa-user"></i> Consumer & Meter Management
        </a>
           
        </h2>
        <button  onclick="openAddConsumer()"> <i class="fa-solid fa-plus"></i> Add New Consumer</button>
    </header>
 



    <div class="main-content">

                <div class="filters" id="filterForm">
                    <div class="search-wrapper">
                        <i class="fa fa-search search-icon"></i>
                        <input type="search" id="searchInput" value="{{request('searchConsumer') }}" placeholder="Search consumer...">
                    </div>

                            <select id="statusFilter" class="filter-select">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Disconnected</option>
                            </select>

                            <select id="houseTypeFilter" class="filter-select">
                                <option value="all" {{ request('house_type') == 'all' ? 'selected' : '' }}>All Types</option>
                                <option value="residential" {{ request('house_type') == 'residential' ? 'selected' : '' }}>Residential</option>
                                <option value="commercial" {{ request('house_type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="industrial" {{ request('house_type') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                            </select>


                                   <div class="menu-dropdown">
                                        <button class="dropdown-toggle"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                        <div class="header-menu">
                                             <button onclick="openArchivedConsumer()"><i class="fa-solid fa-box-archive"></i> Archived List</button>
                                        </div>
                                    </div>


                     </div>

        
        
        <div class="consumer-table-wrapper">
            <table class="consumer-table">
                <thead>
                    <tr>
                        <th>Consumer</th>
                        <th>Meter Number</th>
                        <th>Address</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    
                    </tr>
                </thead>
                <tbody id="consumersTables">

                    @foreach($consumers as $consumer)
                    <tr>
                        <td>
                           <strong>{{ $consumer->full_name }}</strong><br>
                            <small>ID: {{$consumer-> id}}</small>
                        </td>
                      
                        <td>{{ $consumer->electricMeters->first()->electric_meter_number ?? 'N/A'}}</td>
                       
                        <td>{{$consumer->city_name}}</td>
                        <td>
                            @if($consumer->house_type == 'residential')
                             <span class="badge badge-residential">residential</span>
                            @elseif($consumer->house_type == 'commercial')
                              <span class="badge badge-commercial">commercial</span>
                            @elseif($consumer->house_type == 'industrial')
                              <span class="badge badge-industrial">industrial</span>
                            @endif
                        </td>
                        <td>
                            @if($consumer->status == 'active')
                            <span class="badge badge-active">Active</span>
                            @elseif($consumer->status == 'inactive')
                            <span class=" badge badge-inactive" >Disconnected</span>
                            @endif
                        </td>
                        <td>
                            <div class="menu-dropdown">
                                <button class="dropdown-toggle"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                <div class="header-menu">
                                 
                                     <button style="color:#22c55e;"  onclick="openEditConsumer({{ $consumer->id }})"> <i class="fa-solid fa-pen-to-square"></i> Edit</button>
                                     <form action="{{route('consumer.archived', $consumer->id)}}" method="POST">
                                           @csrf
                                        @method('PUT')
                                        <button type="submit" style="color:#ef4444;"><i class="fa-solid fa-user-minus"></i> Archive</button>
                                     </form>
                                  
                                        @php
                                            $activeMeter = $consumer->electricMeters->where('status', 'active')->first();
                                        @endphp

                                        @if($activeMeter)
                                            <a href="{{ route('meters.transfer.form', $activeMeter->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fa fa-exchange"></i> Transfer/Replace
                                            </a>
                                        @endif

                                        <a href="javascript:void(0);" 
                                        class="dropdown-item" 
                                        data-consumer-id="{{ $consumer->id }}" 
                                        onclick="openAssignMeterModal(this)">
                                        <i class="fa fa-bolt"></i> Assign New Meter
                                        </a>
                                    
                                </div>
                            </div>
                        </td>
                        
                    </tr>
     

                    @endforeach
             
                </tbody>
 
            </table>
            <div id="editModalsWrapper">
                @foreach($consumers as $consumer)
                    @include('modals.edit-consumer', ['consumer' => $consumer])
                @endforeach
            </div>
                            <div id="noDataMessage" class="no-data-message" style="display: {{ $consumers->isEmpty() ? 'block' : 'none' }}">
                            <i class="fa-solid fa-users-slash"></i> No consumers available.
                        </div>

           <div class="pagination-buttons">
               <div class="pagination main-pagination">

                  <div class="results-info"> 
                    Showing {{ $consumers->firstItem() }} to {{ $consumers->lastItem() }} of {{ $consumers->total() }} results
                 </div>
    
                 <div>
                    @if ($consumers->onFirstPage())
                        <span class=" cursor-not-allowed">
                            <i class="fa-solid fa-chevron-left"></i> Previous
                        </span>
                    @else
                        <a href="{{ $consumers->previousPageUrl() }}" 
                        class=" transition">
                            <i class="fa-solid fa-chevron-left"></i> Previous
                        </a>
                    @endif


                    @if ($consumers->hasMorePages())
                        <a href="{{ $consumers->nextPageUrl() }}" 
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




function openAssignMeterModal(el) {
    let consumerId = el.getAttribute('data-consumer-id');
    document.getElementById('assign_consumer_id').value = consumerId;
    document.getElementById('assignMeterForm').action = `/consumers/${consumerId}/assign-meter`;
    document.getElementById('assignMeterModal').style.display = 'block';
}

function closeAssignMeterModal() {  
    document.getElementById('assignMeterModal').style.display = 'none';
}




        function openAddConsumer(){
            document.querySelector(".add-consumer").classList.add('active');
        }

        function closedAddConsumer(){
            document.querySelector(".add-consumer").classList.remove('active');
        }

function openEditConsumer(id) {
    const modal = document.getElementById(`edit-consumer-${id}`);
    modal.style.display = "flex";

    // Correct selectors (dynamic IDs)
    const $region   = $(`#region-${id}`);
    const $province = $(`#province-${id}`);
    const $city     = $(`#city-${id}`);
    const $barangay = $(`#barangay-${id}`);

    const regionCode   = $region.data("selected");
    const provinceCode = $province.data("selected");
    const cityCode     = $city.data("selected");
    const barangayCode = $barangay.data("selected");

    // Apply in order after plugin populates options
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




function closeEditConsumer(id) {
    document.getElementById(`edit-consumer-${id}`).style.display = "none";
}


    function openArchivedConsumer(){
            document.querySelector(".archived-consumer").classList.add('active');
        }

        function closedArchivedConsumer(){
            document.querySelector(".archived-consumer").classList.remove('active');
        }





  function attachHeaderDropdown() {
    const dropdowns = document.querySelectorAll('.menu-dropdown'); 
    if (!dropdowns.length) return;

    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.header-menu');

        toggle.addEventListener('click', e => {
            e.stopPropagation();

           
            document.querySelectorAll('.header-menu.show').forEach(openMenu => {
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
        let searchConsumer = $('#searchInput').val();
        let status = $('#statusFilter').val();
        let house_type = $('#houseTypeFilter').val();

        $.ajax({
            url: "{{ route('consumer.index') }}",
            type: "GET",
            data: { searchConsumer: searchConsumer, status: status, house_type: house_type, page_main: page_main },
            success: function(response) {
                $('#consumersTables').html($(response.html).find('#consumersTables').html());
                    $('#editModalsWrapper').html($(response.html).find('#editModalsWrapper').html());
                $('.main-pagination').html($(response.html).find('.main-pagination').html());
                

                
                attachHeaderDropdown(); 
            }
        });
    }

  
    $('#searchInput').on('keyup', function() {
        clearTimeout(debounceMain);
        debounceMain = setTimeout(fetchMainData, 400);
    });

  
    $('#statusFilter , #houseTypeFilter').on('change', function() {
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