<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="{{ route('pages.admins.local.dashboard') }}" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>GoTeach</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="{{ asset('template/img/user.jpg') }}" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0">{{ auth()->user()->prenom ?? 'Admin' }}</h6>
                <span>Admin local</span>
            </div>
        </div>
        <div class="navbar-nav w-100">
            <a href="{{ route('pages.admins.local.dashboard') }}" class="nav-item nav-link {{ request()->routeIs('pages.admins.local.dashboard') ? 'active' : '' }}"><i class="fa fa-bolt me-2"></i>Nouveautés</a>
            <a href="{{ route('admin.local.jeunes.index') }}" class="nav-item nav-link {{ request()->routeIs('admin.local.jeunes.*') ? 'active' : '' }}"><i class="fa fa-users me-2"></i>Jeunes</a>
            <a href="#" class="nav-item nav-link"><i class="fa fa-building me-2"></i>Employeurs</a>
            <a href="#" class="nav-item nav-link"><i class="fa fa-briefcase me-2"></i>Offres</a>
            <a href="#" class="nav-item nav-link"><i class="fa fa-chart-bar me-2"></i>Statistiques</a>
        </div>
    </nav>
</div>



