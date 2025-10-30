@extends('layouts.app')

@section('content')
<style>
  .login-bg {
    background: url('{{ asset('images/page-5-img-1.png') }}') no-repeat center center/cover;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .login-card {
    background: rgba(30, 46, 61, 0.85);
    border-radius: 16px;
    padding: 2.5rem 3rem;
    width: 100%;
    max-width: 420px;
    color: white;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
  }
  .login-card h2 {
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
  .btn-login {
    background-color: #234C6A;
    color: #fff;
    font-weight: 600;
    border: none;
    transition: 0.2s;
  }
  .btn-login:hover {
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

<div class="login-bg">
  <div class="login-card text-center">
    <h2 class="mb-4">OneHelp</h2>
    <h5 class="mb-4">Let’s Get You Connected</h5>

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="mb-3 text-start">
        <label for="email" class="form-label">Username or Email</label>
        <input type="text" id="email" name="email" class="form-control" required>
      </div>

      <div class="mb-3 text-start">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check text-start">
          <input class="form-check-input" type="checkbox" id="remember" name="remember">
          <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <a href="#" class="small">Forgot Password?</a>
      </div>

      <button type="submit" class="btn btn-login w-100 mb-3">Log In</button>

      <div class="text-center small">
        Don’t have an account? <a href="{{ route('register') }}">Register</a>
      </div>
    </form>
  </div>
</div>
@endsection
