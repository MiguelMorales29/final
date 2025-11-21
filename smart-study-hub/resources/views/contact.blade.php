<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Smart Study Hub — Contact Us</title>
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
            <a class="nav-link" href="{{ route('about') }}">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="{{ route('contact') }}">Contact</a>
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

  <section id="contact" class="hero-section d-flex align-items-center" style="overflow-x: hidden;">
    <div class="container text-center">
      <h1 class="display-5 fw-bold lh-tight text-brand-700">Get in Touch</h1>
      <p class="lead text-slate-600 max-w-2xl mx-auto">We'd love to hear from you. Send us a message or find our contact info below.</p>
    </div>
  </section>

  <section id="contact-form" class="py-16" style="background-color: #F8FAFC;">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-6">
          <div class="subject-card card p-6">
            <h2 class="fw-extrabold text-2xl mb-4 text-brand-700">Send Us a Message</h2>
            <form id="contactForm" novalidate>
              <div class="mb-3">
                <label for="name" class="form-label fw-bold text-slate-700">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label fw-bold text-slate-700">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
              </div>
              <div class="mb-3">
                <label for="subject" class="form-label fw-bold text-slate-700">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
              </div>
              <div class="mb-3">
                <label for="message" class="form-label fw-bold text-slate-700">Message</label>
                <textarea class="form-control" id="message" name="message" rows="6" placeholder="Your message here..." required></textarea>
              </div>
              <button type="submit" class="btn btn-accent rounded-pill px-6 py-3">Send Message</button>
            </form>
            <div id="contactAlert" class="alert alert-success mt-3 d-none" role="alert">
              Thank you for your message! We will get back to you soon.
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="subject-card card p-6">
            <h2 class="fw-extrabold text-2xl mb-4 text-brand-700">Contact Information</h2>
            <ul class="list-unstyled text-slate-600 fs-6 mb-4">
              <li class="mb-3"><i class="bi bi-envelope-fill me-2 text-brand-600"></i>Email: <a href="mailto:hello@smartstudyhub.edu" class="text-brand-600">hello@smartstudyhub.edu</a></li>
              <li class="mb-3"><i class="bi bi-telephone-fill me-2 text-brand-600"></i>Phone: +63 912 345 6789</li>
              <li class="mb-3"><i class="bi bi-geo-alt-fill me-2 text-brand-600"></i>Address: 123 Learning St, Manila, Philippines</li>
            </ul>
            <h5 class="fw-bold text-brand-700 mb-3">Follow Us</h5>
            <div class="d-flex gap-3">
              <a href="#" aria-label="Follow us on Facebook" class="text-brand-600 fs-4" rel="noopener"><i class="bi bi-facebook" aria-hidden="true"></i></a>
              <a href="#" aria-label="Follow us on Twitter" class="text-brand-600 fs-4" rel="noopener"><i class="bi bi-twitter" aria-hidden="true"></i></a>
              <a href="#" aria-label="Follow us on Instagram" class="text-brand-600 fs-4" rel="noopener"><i class="bi bi-instagram" aria-hidden="true"></i></a>
              <a href="#" aria-label="Connect with us on LinkedIn" class="text-brand-600 fs-4" rel="noopener"><i class="bi bi-linkedin" aria-hidden="true"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="map" class="py-16 bg-white">
    <div class="container">
      <div class="text-center mb-8">
        <h2 class="fw-extrabold text-3xl md:text-4xl mb-2 text-brand-700">Our Location</h2>
        <p class="text-slate-600">Visit us at our office in Manila</p>
      </div>
      <div class="subject-card card p-0 overflow-hidden">
        <div class="ratio ratio-16x9">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.0737476502716!2d121.04424937416885!3d14.648528989821152!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b1d24ff3e9b7%3A0x9a2d00e1c2f2b212!2sSmart%20Study%20Hub!5e0!3m2!1sen!2sph!4v1695200000000!5m2!1sen!2sph"
            style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
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
      <hr class="footer-border my-4">
      <div class="text-center text-footer-secondary">&copy; 2025 Smart Study Hub. All rights reserved.</div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.querySelectorAll('a[href^=\'#\']').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => { if (entry.isIntersecting) { entry.target.style.opacity = '1'; entry.target.style.transform = 'translateY(0)'; } });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    document.querySelectorAll('.subject-card').forEach(el => {
      el.style.opacity = '0'; el.style.transform = 'translateY(30px)'; el.style.transition = 'opacity 0.6s ease, transform 0.6s ease'; observer.observe(el);
    });
    window.addEventListener('scroll', () => {
      const navbar = document.getElementById('topNav');
      if (window.scrollY > 50) navbar.classList.add('scrolled'); else navbar.classList.remove('scrolled');
    });
    document.getElementById('contactForm').addEventListener('submit', function(e){
      e.preventDefault();
      const form = e.target;
      if(!form.checkValidity()) { form.reportValidity(); return; }
      document.getElementById('contactAlert').classList.remove('d-none');
      form.reset();
    });
  </script>
</body>
</html>



