<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1B3C53;">
  <div class="container">
    <a class="navbar-brand fw-bold text-white" href="{{ route('home') }}">OneHelp</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item"><a href="{{ route('home') }}" class="nav-link text-white">HOME</a></li>
        <li class="nav-item"><a href="{{ route('about') }}" class="nav-link text-white">ABOUT US</a></li>
        <li class="nav-item"><a href="{{ route('events') }}" class="nav-link text-white">EVENTS</a></li>
        <li class="nav-item ms-3"><a href="{{ route('login') }}" class="btn btn-primary" style="background-color:#234C6A; border:none;">LOG IN</a></li>
        <li class="nav-item ms-2"><a href="{{ route('register') }}" class="btn" style="background-color:#F7D47E; color:#1E2E3D; font-weight:600;">JOIN</a></li>
      </ul>
    </div>
  </div>
</nav>
