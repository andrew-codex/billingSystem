<link rel="stylesheet" href="{{ asset('/CSS_Styles/includeCSS/sidebar.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
 <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
<aside class="sidebar" id="sidebar">
   
    <div class="profile-section">
        <img src="{{ asset('/images/user-logo.jpg') }}" alt="Profile Picture" class="profile-img">
        <h4 class="profile-name">Admin</h4>
    </div>

    <ul class="menu-list">
        <li>
            <a href="{{ route('dashboard.index') }}" 
               class="fade-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i> <span>Dashboard</span> 
            </a>
        </li>

        <li>
            <a href="{{ route('consumer.index') }}" 
               class="fade-link {{ request()->routeIs('consumer.index') ? 'active' : '' }}">
                <i class="fa-solid fa-users-gear"></i> <span>Consumer & Electric Meter Management</span> 
            </a>
        </li>

        <li>
            <a href="{{ route('staff.index') }}" 
               class="fade-link {{ request()->routeIs('staff.index') ? 'active' : '' }}">
                <i class="fa-solid fa-user-tie"></i> <span>Staff Management</span> 
            </a>
        </li>

        <li>
            <a href="" 
               class="fade-link {{ request()->routeIs('') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice-dollar"></i> <span>Billing Management</span>
            </a>
        </li>

        <li>
            <a href="{{route('reconnection.index')}}" 
               class="fade-link {{ request()->routeIs('reconnection.index') ? 'active' : '' }}">
                <i class="fa-solid fa-user-check"></i> <span>Reconnection & Lineman Assignment</span> 
            </a>
        </li>

        <li>
            <a href="{{route('BrownoutScheduling.index')}}" 
               class="fade-link {{ request()->routeIs('BrownoutScheduling.index') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar"></i> <span>Brownout Scheduling</span> 
            </a>
        </li>

        <li>
            <a href="{{ route('electricMeter.index') }}" 
               class="fade-link {{ request()->routeIs('electricMeter.index') ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group"></i> <span>Electric Meter Inventory</span> 
            </a>
        </li>

        <li class="logout">
            <a  href=""
               onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" >
                <i class="fa-solid fa-right-from-bracket"></i>Logout
            </a>
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </li>
    </ul>
</aside>
