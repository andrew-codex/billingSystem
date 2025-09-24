



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

    