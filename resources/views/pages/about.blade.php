@extends('layouts.app')

@section('content')
<style>
  .about-hero {
    background: url('{{ asset('images/page-2.png') }}') no-repeat center center/cover;
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    position: relative;
  }

  .about-hero::after {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(30, 46, 61, 0.7);
  }

  .about-hero .content {
    position: relative;
    z-index: 1;
  }

  .about-section {
    background-color: #F5F7FA;
    padding: 4rem 1rem;
  }

  .mission {
    background-color: #234C6A;
    color: white;
    padding: 4rem 1rem;
  }

  .mission h2 {
    color: #F7D47E;
  }

  .about-values {
    background-color: #F7D47E;
    color: #1E2E3D;
    padding: 4rem 1rem;
  }

  .about-values h2 {
    font-weight: 700;
  }

  .about-values .card {
    border: none;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.1);
  }
</style>

<!-- HERO -->
<section class="about-hero">
  <div class="content">
    <h1 class="fw-bold display-5 mb-3">About OneHelp</h1>
    <p class="lead">Connecting Hearts to Communities — Empowering people to create positive change, one act of kindness at a time.</p>
  </div>
</section>

<!-- WHO WE ARE -->
<section class="about-section text-center">
  <div class="container">
    <h2 class="fw-bold mb-4">Who We Are</h2>
    <p class="mx-auto" style="max-width: 800px;">
      OneHelp is a volunteer management platform built to connect passionate individuals with organizations that are making a difference.
      Our mission is to simplify how people discover, join, and contribute to causes that matter most.
      We believe that every act of service — big or small — helps strengthen the community we all share.
    </p>
  </div>
</section>

<!-- MISSION -->
<section class="mission text-center">
  <div class="container">
    <h2 class="fw-bold mb-4">Our Mission</h2>
    <p class="mx-auto" style="max-width: 750px;">
      To empower volunteers and organizations through meaningful partnerships that drive sustainable development.
      We aim to make volunteering more accessible, inclusive, and impactful for everyone.
    </p>
  </div>
</section>

<!-- OUR VALUES -->
<section class="about-values text-center">
  <div class="container">
    <h2 class="fw-bold mb-5">Our Core Values</h2>
    <div class="row g-4 justify-content-center">
      <div class="col-md-4">
        <div class="card p-4 h-100">
          <h4 class="fw-bold mb-3">💛 Compassion</h4>
          <p>We lead with empathy and kindness in every action we take.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 h-100">
          <h4 class="fw-bold mb-3">🌍 Collaboration</h4>
          <p>We build bridges between people and organizations to amplify impact.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 h-100">
          <h4 class="fw-bold mb-3">🚀 Empowerment</h4>
          <p>We empower individuals to take action and be part of meaningful change.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SUSTAINABLE FUTURE -->
<section class="py-5" style="background-color:#EAF2F8;">
  <div class="container text-center">
    <h2 class="fw-bold mb-4">Building a Sustainable Future Together</h2>
    <p class="mx-auto mb-4" style="max-width:800px;">
      OneHelp aligns with the United Nations Sustainable Development Goals, promoting equality, education, zero hunger, and stronger communities.
    </p>
    <img src="{{ asset('images/page-13-img-1.png') }}" alt="SDG Goals" class="img-fluid" style="max-width:400px;">
  </div>
</section>
@endsection
