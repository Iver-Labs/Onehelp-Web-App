@extends('layouts.app')

@section('content')
<style>
  .register-bg {
    background: url('{{ asset('images/page-6-img-1.png') }}') no-repeat center center/cover;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .register-card {
    background: rgba(30, 46, 61, 0.85);
    border-radius: 16px;
    padding: 2.5rem 3rem;
    width: 100%;
    max-width: 420px;
    color: white;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
  }
  .register-card h2 {
    color: #F7D47E;
    font-weight: 700;
  }
  .form-control {
    background-color: #F7D47E;
    border: none;
    color: #1E2E3D;
    font-weight: 500;
  }
  .form-control::placeholder {
    color: #1E2E3D;
    opacity: 0.7;
  }
  .btn-register {
    background-color: #234C6A;
    color: #fff;
    font-weight: 600;
    border: none;
    transition: 0.2s;
  }
  .btn-register:hover {
    background-color: #18374D;
  }
  a {
    color: #F7D47E;
    text-decoration: none;
  }
  a:hover {
    text-decoration: underline;
  }
</style>

<div class="register-bg">
  <div class="register-card text-center">
    <h2 class="mb-4">OneHelp</h2>
    <h5 class="mb-4">Become a Volunteer</h5>

    {{-- ✅ Updated form action --}}
    <form method="POST" action="{{ route('register.store') }}">
      @csrf

      <div class="mb-3 text-start">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
      </div>

      <div class="mb-3 text-start">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
      </div>

      <div class="mb-4 text-start">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
      </div>

      <button type="submit" class="btn btn-register w-100 mb-3">Join Now</button>

      <div class="text-center small">
        Already have an account? <a href="{{ route('login') }}">Log In</a>
      </div>
    </form>
  </div>
</div>
@endsection
