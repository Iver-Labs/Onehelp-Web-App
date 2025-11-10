@extends('layouts.app')

@section('title', 'Register - OneHelp')

@section('content')
<div style="background: linear-gradient(135deg, #90C7D0 0%, #5FB9A8 100%); min-height: 100vh; padding: 4rem 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-lg" style="border: 3px solid #1A4D5E; border-radius: 20px; overflow: hidden;">
                    <div class="card-header text-center" style="background: #1A4D5E; color: white; padding: 2rem;">
                        <h2 style="font-weight: 700; margin: 0;">Join OneHelp</h2>
                        <p style="margin: 0.5rem 0 0; opacity: 0.9;">Start making a difference today</p>
                    </div>

                    <div class="card-body" style="padding: 2rem;">
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs mb-4" id="registrationTabs" role="tablist" style="border-bottom: 3px solid #1A4D5E;">
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link active w-100" id="volunteer-tab" data-bs-toggle="tab" data-bs-target="#volunteer" type="button" role="tab" style="font-weight: 700; font-size: 1.1rem; color: #1A4D5E; padding: 1rem;">
                                    <i class="fas fa-hands-helping me-2"></i>Volunteer
                                </button>
                            </li>
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link w-100" id="organization-tab" data-bs-toggle="tab" data-bs-target="#organization" type="button" role="tab" style="font-weight: 700; font-size: 1.1rem; color: #1A4D5E; padding: 1rem;">
                                    <i class="fas fa-building me-2"></i>Organization
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="registrationTabsContent">
                            <!-- VOLUNTEER REGISTRATION -->
                            <div class="tab-pane fade show active" id="volunteer" role="tabpanel">
                                <form method="POST" action="{{ route('register.volunteer') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">First Name *</label>
                                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Last Name *</label>
                                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Email Address *</label>
                                        <input type="email" name="email" autocomplete="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Password *</label>
                                            <input type="password" name="password" autocomplete="new-password" class="form-control @error('password') is-invalid @enderror" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Confirm Password *</label>
                                            <input type="password" name="password_confirmation" autocomplete="new-password" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Date of Birth</label>
                                        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}">
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Address</label>
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Bio</label>
                                        <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="3" placeholder="Tell us about yourself and your interests...">{{ old('bio') }}</textarea>
                                        @error('bio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn w-100" style="background: #F4D58D; color: #1A4D5E; font-weight: 700; padding: 1rem; border-radius: 10px; border: 2px solid #1A4D5E; font-size: 1.1rem;">
                                        Register as Volunteer
                                    </button>
                                </form>
                            </div>

                            <!-- ORGANIZATION REGISTRATION -->
                            <div class="tab-pane fade" id="organization" role="tabpanel">
                                <form method="POST" action="{{ route('register.organization') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Organization Name *</label>
                                        <input type="text" name="org_name" class="form-control @error('org_name') is-invalid @enderror" value="{{ old('org_name') }}" required>
                                        @error('org_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Organization Type *</label>
                                            <select name="org_type" class="form-select @error('org_type') is-invalid @enderror" required>
                                                <option value="">Select type...</option>
                                                <option value="NGO" {{ old('org_type') == 'NGO' ? 'selected' : '' }}>NGO</option>
                                                <option value="Non-Profit" {{ old('org_type') == 'Non-Profit' ? 'selected' : '' }}>Non-Profit</option>
                                                <option value="Community Group" {{ old('org_type') == 'Community Group' ? 'selected' : '' }}>Community Group</option>
                                                <option value="School" {{ old('org_type') == 'School' ? 'selected' : '' }}>School</option>
                                                <option value="Religious Organization" {{ old('org_type') == 'Religious Organization' ? 'selected' : '' }}>Religious Organization</option>
                                                <option value="Other" {{ old('org_type') == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('org_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Registration Number</label>
                                            <input type="text" name="registration_number" class="form-control @error('registration_number') is-invalid @enderror" value="{{ old('registration_number') }}">
                                            @error('registration_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Email Address *</label>
                                        <input type="email" name="email" autocomplete="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Password *</label>
                                            <input type="password" name="password" autocomplete="new-password" class="form-control @error('password') is-invalid @enderror" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Confirm Password *</label>
                                            <input type="password" name="password_confirmation" autocomplete="new-password" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Contact Person *</label>
                                            <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{ old('contact_person') }}" required>
                                            @error('contact_person')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Phone Number *</label>
                                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Address *</label>
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2" required>{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Description *</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Tell us about your organization's mission and goals..." required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Organization Logo</label>
                                        <input type="file" name="logo_image" class="form-control @error('logo_image') is-invalid @enderror" accept="image/jpeg,image/jpg,image/png">
                                        <small class="text-muted">Accepted formats: JPG, JPEG, PNG (Max 2MB)</small>
                                        @error('logo_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn w-100" style="background: #F4D58D; color: #1A4D5E; font-weight: 700; padding: 1rem; border-radius: 10px; border: 2px solid #1A4D5E; font-size: 1.1rem;">
                                        Register Organization
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <p style="color: #1A4D5E;">Already have an account? <a href="{{ route('login') }}" style="color: #2C7A6E; font-weight: 700;">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
    }
    .nav-tabs .nav-link.active {
        background: transparent;
        border-bottom: 3px solid #F4D58D;
        color: #1A4D5E !important;
    }
    .nav-tabs .nav-link:hover {
        border-bottom: 3px solid #F4D58D;
    }
</style>
@endpush
@endsection