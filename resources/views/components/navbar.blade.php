<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
             <img src="{{ asset('images/Onehelp-white-logo.svg') }}" alt="OneHelp Logo" class="navbar-logo">
            <!-- <span>OneHelp</span> -->
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="background: white;">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('events') }}">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">About</a>
                </li>

                @auth
                    {{-- Authenticated User Menu --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i>
                            @if(auth()->user()->isVolunteer() && auth()->user()->volunteer)
                                {{ auth()->user()->volunteer->full_name }}
                            @elseif(auth()->user()->isOrganization() && auth()->user()->organization)
                                {{ auth()->user()->organization->org_name }}
                            @else
                                {{ auth()->user()->email }}
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            @if(auth()->user()->isVolunteer())
                                <li><a class="dropdown-item" href="{{ route('volunteer.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a></li>
                            @elseif(auth()->user()->isOrganization())
                                <li><a class="dropdown-item" href="{{ route('organization.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a></li>
                            @elseif(auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                                </a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    {{-- Guest User Menu --}}
                    <li class="nav-item">
                        <a class="btn btn-login" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-signup" href="{{ route('register') }}">Sign Up</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

@push('styles')
<style>
    .dropdown-menu {
        border: 2px solid #1A4D5E;
        border-radius: 10px;
        padding: 0.5rem;
    }
    .dropdown-item {
        border-radius: 5px;
        padding: 0.5rem 1rem;
    }
    .dropdown-item:hover {
        background: #90C7D0;
        color: #1A4D5E;
    }
</style>
@endpush