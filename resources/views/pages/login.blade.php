@extends('layouts.app')

@section('title', 'Login - OneHelp')

@section('content')
<div style="background: linear-gradient(135deg, #90C7D0 0%, #5FB9A8 100%); min-height: 100vh; padding: 4rem 0; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg" style="border: 3px solid #1A4D5E; border-radius: 20px; overflow: hidden;">
                    <div class="card-header text-center" style="background: #1A4D5E; color: white; padding: 2rem;">
                        <h2 style="font-weight: 700; margin: 0;">Welcome Back</h2>
                        <p style="margin: 0.5rem 0 0; opacity: 0.9;">Login to continue your journey</p>
                    </div>

                    <div class="card-body" style="padding: 2.5rem;">
                        @if(session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger" role="alert">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.submit') }}">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label fw-bold" style="color: #1A4D5E;">Email Address</label>
                                <input type="email" name="email" autocomplete="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus style="padding: 0.75rem; border: 2px solid #1A4D5E; border-radius: 10px;">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" style="color: #1A4D5E;">Password</label>
                                <input type="password" name="password" autocomplete="current-password" class="form-control @error('password') is-invalid @enderror" required style="padding: 0.75rem; border: 2px solid #1A4D5E; border-radius: 10px;">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" style="border: 2px solid #1A4D5E;">
                                <label class="form-check-label" for="remember" style="color: #1A4D5E;">
                                    Remember me
                                </label>
                            </div>

                            <button type="submit" class="btn w-100 mb-3" style="background: #F4D58D; color: #1A4D5E; font-weight: 700; padding: 1rem; border-radius: 10px; border: 2px solid #1A4D5E; font-size: 1.1rem;">
                                Login
                            </button>

                            <div class="text-center">
                                <a href="#" style="color: #2C7A6E; font-weight: 600; text-decoration: none;">Forgot your password?</a>
                            </div>
                        </form>

                        <hr style="margin: 2rem 0; border-top: 2px solid #1A4D5E;">

                        <div class="text-center">
                            <p style="color: #1A4D5E; margin-bottom: 1rem;">Don't have an account yet?</p>
                            <a href="{{ route('register') }}" class="btn w-100" style="background: white; color: #1A4D5E; font-weight: 700; padding: 1rem; border-radius: 10px; border: 2px solid #1A4D5E;">
                                Create an Account
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection