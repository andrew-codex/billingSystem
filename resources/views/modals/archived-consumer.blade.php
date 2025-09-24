<link rel="stylesheet" href="{{asset('/CSS_Styles/modalCSS/archive-consumer.css')}}">
   <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">

<div class="archived-consumer">
    <div class="modal-content">
            <button class="close-btn" onclick="closedArchivedConsumer()"><i class="fa-solid fa-xmark"></i></button>
            <h2 class="archived-title">Archived Consumers</h2>


                @if($archivedConsumers->isEmpty())
                   <p class="no-archived">No archived consumer available.</p>
                @else

                <div class="consumers-table">
                  <table class="archived-consumer-table">
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
                <tbody>

                    @foreach($archivedConsumers as $archivedConsumer)
                    <tr>
                        <td>
                            <strong>{{$archivedConsumer->full_name}}</strong><br>
                            <small>ID: {{$archivedConsumer-> id}}</small>
                        </td>
                      
                        <td>{{ $archivedConsumer->electricMeters->first()->electric_meter_number ?? 'N/A'}}</td>
                       
                        <td>{{$archivedConsumer->address}}</td>
                        <td><span class="badge badge-type">{{$archivedConsumer->house_type}}</span></td>
                        <td><span class="badge badge-active">{{$archivedConsumer->status}}</span></td>
                        <td>
                            <div class="table-dropdown">
                                <button class="table-dropdown-toggle"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                <div class="table-menu">

                                <form action="{{route('consumer.unArchived', $archivedConsumer->id)}}" method="POST">
                                    @csrf
                                    @method('PUT')
                                     <button type="submit"><i class="fa-solid fa-rotate-left"></i> Restore</button>

                                </form>

                               
                                   
                                     <form action="{{route('consumer.destroy', $archivedConsumer->id)}}" method="POST">
                                           @csrf
                                        @method('DELETE')
                                        <button type="submit" style="color:#ef4444;"><i class="fa-solid fa-user-minus"></i> Delete</button>
                                     </form>
                                    
                                    
                                </div>
                            </div>
                        </td>
                        
                    </tr>
     
              
                    @endforeach
             @endif
                </tbody>
 
            </table>

        </div>
    </div>
</div>

<script>
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
</script>