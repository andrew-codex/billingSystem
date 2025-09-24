<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brownout Scheduling</title>
    <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/brownout.css')}}">
     <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
</head>
<body>
  @include('includes.sidebar')

  <div class="content">

       <header>
        <h2><a href="{{route('BrownoutScheduling.index')}}" class="title">
             <i class="fa-solid fa-calendar"></i> Brownout Scheduling
        </a>
           
        </h2>
        <button  onclick="openAddSchedule()"> <i class="fa-solid fa-plus"></i> Add New Schedule</button>

    </header>

    


    <div class="main-content">
          <div class="filters " id="filterForm">
                <div class="search-wrapper">
                    <i class="fa fa-search search-icon"></i>
                    <input type="search" id="searchInput" placeholder="Search area / location...">
                </div>

                <select class="filter-select">
                    <option>All Areas</option>
                    <option>Residential</option>
                    <option>Commercial</option>
                    <option>Industrial</option>
                </select>

                <select class="filter-select">
                    <option>All Status</option>
                    <option>Upcoming</option>
                    <option>Ongoing</option>
                     <option>Completed</option>
                </select>
            </div>



            <div class="mid-content">

                <table class ="brownout-sched">
                    <thead>
                        <tr>
                            <th>Area / Location</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Reason</th> 
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                           
                        </tr>
                    </tbody>

                </table>
                    
                
            </div>
            


 


                                

    </div>

  </div>
</body>
</html>