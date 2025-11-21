<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us | Smart Study Hub</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"/>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
  <style>
    /* Disable animated blobs on about/contact pages to prevent horizontal scrolling */
    .hero-section::before,
    .hero-section::after {
      display: none !important;
    }
  </style>
</head>
<body class="font-poppins text-slate-800" style="overflow-x: hidden;">
  <nav id="topNav" class="navbar navbar-expand-lg fixed-top navbar-light transition-all">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
        <div class="logo-circle d-flex align-items-center justify-content-center">
          <img src="{{ asset('images/sshlogo.png') }}" alt="Smart Study Hub Logo" class="logo-img">
        </div>
        <span class="fw-bold text-lg md:text-xl">Smart Study Hub</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Nav Links -->
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}#home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="{{ route('about') }}">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('contact') }}">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}#courses">Courses</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}#testimonials">Testimonials</a>
          </li>
@guest
          <li class="nav-item">
            <a class="btn btn-primary btn-sm rounded-pill me-2" href="{{ route('login') }}">Login</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-outline-primary btn-sm rounded-pill" href="{{ route('register') }}">Register</a>
          </li>
@endguest
@auth
          <li class="nav-item">
            <a class="btn btn-primary btn-sm rounded-pill" href="{{ route('dashboard') }}">Dashboard</a>
          </li>
@endauth
        </ul>
      </div>
    </div>
  </nav>

  <section class="hero-section d-flex align-items-center" style="overflow-x: hidden;">
    <div class="container text-center">
      <h1 class="display-5 fw-bold lh-tight text-brand-700">About Us</h1>
      <p class="lead text-slate-600 max-w-2xl mx-auto">Building the future of learning — anytime, anywhere.</p>
    </div>
  </section>

  <section class="py-16" style="background-color: #F8FAFC;">
    <div class="container">
      <div class="text-center mb-8">
        <h2 class="fw-extrabold text-3xl md:text-4xl mb-2 text-brand-700">Meet Our Team</h2>
        <p class="text-slate-600">The passionate individuals behind Smart Study Hub</p>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="subject-card card h-100 p-4 reveal">
            <div class="text-center">
              <div class="position-relative mb-3">
                <img src="{{ asset('images/bea.jpg') }}" alt="Ma. Bea Francine C. Magayones" class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                <div class="position-absolute top-0 start-0 w-100 h-100 rounded-circle" style="background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(20, 184, 166, 0.1)); z-index: -1;"></div>
              </div>
              <h5 class="fw-bold text-brand-700">Ma. Bea Francine C. Magayones</h5>
              <p class="text-slate-600 fw-bold">Documentation Lead</p>
              <p class="small text-slate-600">Ensures all project information is well-documented and accessible.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="subject-card card h-100 p-4 reveal">
            <div class="text-center">
              <div class="position-relative mb-3">
                <img src="{{ asset('images/mark.jpg') }}" alt="Mark Lorence C. Magayones" class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                <div class="position-absolute top-0 start-0 w-100 h-100 rounded-circle" style="background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(20, 184, 166, 0.1)); z-index: -1;"></div>
              </div>
              <h5 class="fw-bold text-brand-700">Mark Lorence C. Magayones</h5>
              <p class="text-slate-600 fw-bold">Front-End Designer</p>
              <p class="small text-slate-600">Creates the visual layout and user interface of the project.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="subject-card card h-100 p-4 reveal">
            <div class="text-center">
              <div class="position-relative mb-3">
                <img src="{{ asset('images/morales.jpg') }}" alt="Juan Miguel Morales" class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                <div class="position-absolute top-0 start-0 w-100 h-100 rounded-circle" style="background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(20, 184, 166, 0.1)); z-index: -1;"></div>
              </div>
              <h5 class="fw-bold text-brand-700">Juan Miguel Morales</h5>
              <p class="text-slate-600 fw-bold">Back-End Developer</p>
              <p class="small text-slate-600">Builds and maintains the server-side functionality and database.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-16 bg-white">
    <div class="container">
      <div class="row g-5">
        <div class="col-md-6">
          <div class="p-6">
            <h2 class="fw-extrabold text-2xl mb-4 text-brand-700">Our Mission</h2>
            <p class="text-slate-600 leading-relaxed">At <strong>Smart Study Hub</strong>, our mission is to empower students with knowledge, tools, and resources that make learning engaging and effective. We strive to remove barriers to education and make quality learning accessible to everyone.</p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-6">
            <h2 class="fw-extrabold text-2xl mb-4 text-brand-700">Our Vision</h2>
            <p class="text-slate-600 leading-relaxed">We envision a global community where technology connects learners with the best resources and educators. Our goal is to be the go-to hub for students seeking growth, collaboration, and academic success.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-16" style="background-color: #F1F5F9;">
    <div class="container">
      <div class="text-center mb-8">
        <h2 class="fw-extrabold text-3xl md:text-4xl mb-2 text-brand-700">Why Choose Smart Study Hub?</h2>
        <p class="text-slate-600">Discover what makes us the preferred learning platform</p>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="subject-card card h-100 p-4 text-center">
            <div class="feature-icon mb-3 fs-1">📘</div>
            <h5 class="fw-bold text-brand-700">Comprehensive Resources</h5>
            <p class="text-slate-600">From math to history, access structured study guides, videos, and practice materials in one place.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="subject-card card h-100 p-4 text-center">
            <div class="feature-icon mb-3 fs-1">👩‍🏫</div>
            <h5 class="fw-bold text-brand-700">Expert Tutors</h5>
            <p class="text-slate-600">Our platform connects students with experienced educators to provide personalized support.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="subject-card card h-100 p-4 text-center">
            <div class="feature-icon mb-3 fs-1">🌐</div>
            <h5 class="fw-bold text-brand-700">Learn Anywhere</h5>
            <p class="text-slate-600">All materials are online and mobile-friendly, so you can study anytime, anywhere.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-16 bg-white">
    <div class="container">
      <div class="text-center mb-8">
        <h2 class="fw-extrabold text-3xl md:text-4xl mb-2 text-brand-700">Our Core Values</h2>
        <p class="text-slate-600">The principles that guide everything we do</p>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="subject-card card h-100 p-4 text-center">
            <h5 class="fw-bold text-brand-700">Innovation</h5>
            <p class="text-slate-600">We embrace modern tools and technologies to make learning more engaging.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="subject-card card h-100 p-4 text-center">
            <h5 class="fw-bold text-brand-700">Collaboration</h5>
            <p class="text-slate-600">We believe learning is best achieved together, through shared resources and teamwork.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="subject-card card h-100 p-4 text-center">
            <h5 class="fw-bold text-brand-700">Excellence</h5>
            <p class="text-slate-600">We're dedicated to providing top-quality content and support to every learner.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-16" style="background-color: #F1F5F9;">
    <div class="container">
      <div class="cta-banner text-center">
        <h2 class="fw-extrabold text-3xl mb-3 text-brand-700">Ready to Start Learning with Us?</h2>
        <p class="text-slate-600 mb-4">Join thousands of students already using Smart Study Hub to achieve their academic goals.</p>
        <a href="{{ route('contact') }}" class="btn btn-accent px-6 py-3 rounded-pill">Get Started</a>
      </div>
    </div>
  </section>

  <footer class="footer-custom pt-10 pb-6">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-6">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div class="logo-circle small-circle d-flex align-items-center justify-content-center">SSH</div>
            <span class="fw-bold fs-5">Smart Study Hub</span>
          </div>
          <p class="text-footer-secondary mb-3">Organize materials, learn consistently, and see your progress — all in one calm, focused space.</p>
        </div>
        <div class="col-6 col-md-3">
          <h6 class="fw-bold mb-2 text-footer-primary">Explore</h6>
          <ul class="list-unstyled text-footer-secondary">
            <li><a href="{{ route('home') }}#subjects" class="footer-link">Subjects</a></li>
            <li><a href="{{ route('home') }}#materials" class="footer-link">Materials</a></li>
            <li><a href="{{ route('home') }}#video" class="footer-link">Videos</a></li>
          </ul>
        </div>
        <div class="col-6 col-md-3">
          <h6 class="fw-bold mb-2 text-footer-primary">Links</h6>
          <ul class="list-unstyled text-footer-secondary">
            <li><a href="{{ route('about') }}" class="footer-link">About</a></li>
            <li><a href="{{ route('contact') }}" class="footer-link">Contact</a></li>
          </ul>
        </div>
      </div>
      <hr class="footer-border my-4" />
      <div class="text-center text-footer-muted small">&copy; 2025 Smart Study Hub. All rights reserved.</div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    document.querySelectorAll('.reveal').forEach(el => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(30px)';
      el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      observer.observe(el);
    });
    window.addEventListener('scroll', () => {
      const navbar = document.getElementById('topNav');
      if (window.scrollY > 50) navbar.classList.add('scrolled');
      else navbar.classList.remove('scrolled');
    });
  </script>
</body>
</html>



