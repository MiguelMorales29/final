<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Smart Study Hub — Learn Smarter</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">


  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


  <!-- Google Fonts: Poppins (Bold) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700;800&display=swap" rel="stylesheet" />

  <!-- Optional icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"/>

  <!-- Custom styles -->
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />

</head>

<body class="font-poppins text-slate-800">

  <!-- ======================= NAVBAR ======================= -->
  <nav id="topNav" class="navbar navbar-expand-lg fixed-top navbar-light transition-all">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="#">
      <!-- Logo image inside the circle -->
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
                <a class="nav-link" href="#home">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('about') }}">About Us</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('contact') }}">Contact</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#courses">Courses</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#testimonials">Testimonials</a>
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

    
  </nav>
  

  <!-- ======================= HERO / LANDING ======================= -->
  <!-- Multiple gradients ONLY here (landing page) -->
  <header id="home" class="hero-section position-relative overflow-hidden">
    <!-- Decorative wave bottom -->
    <svg class="hero-wave" viewBox="0 0 1440 180" xmlns="http://www.w3.org/2000/svg">
      <path d="M0,64 C240,160 480,0 720,64 C960,128 1200,96 1440,32 L1440,180 L0,180 Z" fill="rgba(255,255,255,0.85)"></path>
    </svg>

    <div class="container position-relative z-10">
      
      <div class="row align-items-center py-5 min-vh-100">
        <div class="col-lg-7 text-white">
          <div class="mb-4 d-flex align-items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" onerror="this.style.display='none'" alt="Logo" class="h-12 w-auto"/>
            <h1 class="display-5 fw-bold lh-tight mb-0">Learn. Evolve. Succeed.</h1>
          </div>
          <p class="lead mb-4 max-w-2xl">
            A dynamic online learning platform that keeps your study materials organized and your progress on track — calm, focused, effective.
          </p>



          <div class="d-flex flex-wrap gap-2">
            <a href="#subjects" class="btn btn-outline-dark rounded-pill px-4">Browse Subjects</a>
            <a href="#materials" class="btn btn-light rounded-pill px-4 text-brand-600">Explore Materials</a>
          </div>

          <!-- Stats -->
          <div class="row g-3 mt-5">
            <div class="col-4">
              <div class="stat">
                <div class="h3 fw-bold mb-0"><span class="counter" data-target="1200">0</span>+</div>
                <small class="text-black">Resources</small>
              </div>
            </div>
            <div class="col-4">
              <div class="stat">
                <div class="h3 fw-bold mb-0"><span class="counter" data-target="48">0</span></div>
                <small class="text-black">Subjects</small>
              </div>
            </div>
            <div class="col-4">
              <div class="stat">
                <div class="h3 fw-bold mb-0"><span class="counter" data-target="35">0</span>k</div>
                <SMALL class="text-black">Learners</SMALL>
              </div>
            </div>
          </div>
        </div>

<!-- ======================= CARD SLIDER SECTION ======================= -->
<div class="col-lg-5 mt-5 mt-lg-0">
  <div class="hero-media card border-0 shadow-xl position-relative overflow-hidden">

    <!-- Carousel Wrapper -->
    <div id="studyCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
      
      <!-- Carousel Inner -->
      <div class="carousel-inner">

        <!-- CARD 1 (Your existing card) -->
        <div class="carousel-item active">
          <img src="https://images.unsplash.com/photo-1513258496099-48168024aec0?q=80&w=1600&auto=format&fit=crop" 
               alt="Study" class="card-img-top object-cover"/>
          <div class="card-body">
            <div class="d-flex align-items-center gap-3">
              <img src="https://images.unsplash.com/photo-1511367461989-f85a21fda167?q=80&w=200&auto=format&fit=crop" 
                   class="rounded-circle" width="48" height="48" alt="Instructor">
              <div>
                <div class="fw-bold">Featured: Study Habits That Stick</div>
                <small class="opacity-75">Watch a 3-minute intro below</small>
              </div>
            </div>
          </div>
        </div>

        <!-- CARD 2 -->
        <div class="carousel-item">
          <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1600&auto=format&fit=crop" 
               alt="Tips" class="card-img-top object-cover"/>
          <div class="card-body">
            <h5 class="fw-bold">Quick Study Tips</h5>
            <small class="opacity-75">Master the Pomodoro technique for better focus.</small>
          </div>
        </div>

        <!-- CARD 3 -->
        <div class="carousel-item">
          <img src="{{ asset('images/inspiration.jpg') }}"
               alt="Motivation" class="card-img-top object-cover"/>
          <div class="card-body">
            <h5 class="fw-bold">Daily Motivation</h5>
            <small class="opacity-75">Consistency beats intensity—study a little every day.</small>
          </div>
        </div>

        <!-- CARD 4 -->
        <div class="carousel-item">
          <img src="{{ asset('images/website.png') }}"
               alt="About Us" class="card-img-top object-cover"/>
          <div class="card-body">
            <h5 class="fw-bold">About Our Website</h5>
            <small class="opacity-75">A platform built to make studying smarter and simpler.</small>
          </div>
        </div>

        <!-- CARD 5 -->
        <div class="carousel-item">
          <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=1600&auto=format&fit=crop" 
               alt="Organization" class="card-img-top object-cover"/>
          <div class="card-body">
            <h5 class="fw-bold">Organize Your Notes</h5>
            <small class="opacity-75">Keep everything in one place with our smart tools.</small>
          </div>
        </div>

        <!-- CARD 6 -->
        <div class="carousel-item">
          <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=1600&auto=format&fit=crop" 
               alt="Techniques" class="card-img-top object-cover"/>
          <div class="card-body">
            <h5 class="fw-bold">Memory Boosters</h5>
            <small class="opacity-75">Use active recall and spaced repetition to retain more.</small>
          </div>
        </div>

        <!-- CARD 7 -->
        <div class="carousel-item">
          <img src="https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?q=80&w=1600&auto=format&fit=crop" 
               alt="Tools" class="card-img-top object-cover"/>
          <div class="card-body">
            <h5 class="fw-bold">Smart Study Tools</h5>
            <small class="opacity-75">From flashcards to planners, we’ve got you covered.</small>
          </div>
        </div>

        <!-- CARD 8 -->
        <div class="carousel-item">
          <img src="{{ asset('images/inspired.png') }}"
               alt="Inspiration" class="card-img-top object-cover"/>
          <div class="card-body">
            <h5 class="fw-bold">Stay Inspired</h5>
            <small class="opacity-75">Small steps today lead to big achievements tomorrow.</small>
          </div>
        </div>

      </div>

      <!-- Controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#studyCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon rounded-circle bg-dark p-3"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#studyCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon rounded-circle bg-dark p-3"></span>
      </button>
    </div>
  </div>
</div>

      </div>
    </div>
  </header>

<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-papd1I3HzNf9tQG1kQJYoiD5GjB1plcqLx1JnO+Uw2Ufs2C6Dz2f5w6yzyC5t7lqX0jJ4C8Lzq0Zs/3mBzG+DQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- ======================= SUBJECTS ======================= -->
<section id="subjects-header" class="py-5 subjects-header-section">

  <!-- Floating icons -->
  <div class="floating-icons-wrapper">
    <div class="floating-icons">
      <!-- 20 unique subject icons -->
      <i class="fa-solid fa-atom" title="Science"></i>
      <i class="fa-solid fa-book" title="English"></i>
      <i class="fa-solid fa-square-root-alt" title="Mathematics"></i>
      <i class="fa-solid fa-desktop" title="ICT"></i>
      <i class="fa-solid fa-flask" title="Chemistry"></i>
      <i class="fa-solid fa-microscope" title="Biology"></i>
      <i class="fa-solid fa-calculator" title="Math"></i>
      <i class="fa-solid fa-globe" title="Geography"></i>
      <i class="fa-solid fa-brain" title="Psychology"></i>
      <i class="fa-solid fa-language" title="Languages"></i>
      <i class="fa-solid fa-music" title="Music"></i>
      <i class="fa-solid fa-paint-brush" title="Arts"></i>
      <i class="fa-solid fa-book-reader" title="Literature"></i>
      <i class="fa-solid fa-code" title="Programming"></i>
      <i class="fa-solid fa-robot" title="Robotics"></i>
      <i class="fa-solid fa-atom" title="Physics"></i>
      <i class="fa-solid fa-flask" title="Science Lab"></i>
      <i class="fa-solid fa-chalkboard-teacher" title="Teaching"></i>
      <i class="fa-solid fa-drafting-compass" title="Engineering"></i>
      <i class="fa-solid fa-graduation-cap" title="Education"></i>
      <!-- Duplicate for seamless loop -->
      <i class="fa-solid fa-atom"></i>
      <i class="fa-solid fa-book"></i>
      <i class="fa-solid fa-square-root-alt"></i>
      <i class="fa-solid fa-desktop"></i>
      <i class="fa-solid fa-flask"></i>
      <i class="fa-solid fa-microscope"></i>
      <i class="fa-solid fa-calculator"></i>
      <i class="fa-solid fa-globe"></i>
      <i class="fa-solid fa-brain"></i>
      <i class="fa-solid fa-language"></i>
      <i class="fa-solid fa-music"></i>
      <i class="fa-solid fa-paint-brush"></i>
      <i class="fa-solid fa-book-reader"></i>
      <i class="fa-solid fa-code"></i>
      <i class="fa-solid fa-robot"></i>
      <i class="fa-solid fa-atom"></i>
      <i class="fa-solid fa-flask"></i>
      <i class="fa-solid fa-chalkboard-teacher"></i>
      <i class="fa-solid fa-drafting-compass"></i>
      <i class="fa-solid fa-graduation-cap"></i>
    </div>
  </div>

  <div class="container">
    <div class="glass-header p-4 rounded-4 mx-auto">
      <h2 class="fw-bold text-center mb-3 highlight-title">Explore Subjects</h2>
  
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
          <div class="input-group">
            <input type="text" class="form-control rounded-start-pill search-input" placeholder="Search subjects..." aria-label="Search subjects">
            <button class="btn btn-dark rounded-end-pill search-btn" type="button">Search</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  

  
</section>

<style>


.highlight-title {
  background: url('{{ asset('images/yellow.png') }}') no-repeat center;
  background-size: 80% 500%;
  background-position: 40px center; /* move 10px to the left */

}

@media (max-width: 576px) {
  .highlight-title {
    background-size: 80% 500%; /* slightly wider on mobile if needed */
  }
}


.subjects-header-section {
  position: relative;
  overflow: hidden;
  background: linear-gradient(120deg,
    rgba(37, 99, 235, 0.15),
    rgba(29, 78, 216, 0.15),
    rgba(20, 184, 166, 0.15)
  );
  background-size: 400% 400%;
  animation: waterFlow 18s ease infinite;
}

@keyframes waterFlow {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.glass-header {
  max-width: 800px;
  backdrop-filter: blur(5px);
  -webkit-backdrop-filter: blur(5px);
  background: rgba(241, 245, 249, 0.15);
  border-radius: 20px;
  border: 1px solid rgba(255, 255, 255, 0.25);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  position: relative;
  z-index: 1;
}

.search-input {
  border: 2px solid rgba(29, 78, 216, 0.3);
  padding: 10px 15px;
  background: rgba(255, 255, 255, 0.7);
  border-radius: 50px 0 0 50px;
  transition: all 0.3s ease;
}

.search-input:focus {
  box-shadow: 0 0 14px rgba(20, 184, 166, 0.5);
  transform: scale(1.02);
}

.search-btn {
  border-radius: 0 50px 50px 0;
  padding: 10px 20px;
  background: var(--brand-700);
  color: white;
  transition: background 0.3s ease, transform 0.2s ease;
}

.search-btn:hover {
  background: var(--brand-800);
  transform: scale(1.05);
}

/* Floating icons wrapper */
.floating-icons-wrapper {
  position: absolute;
  top: 35%;
  left: 0;
  width: 200%;
  display: flex;
  z-index: 0;
  pointer-events: none;
}

/* Floating icons */
.floating-icons {
  display: flex;
  gap: 80px;
  font-size: 3.5rem;
  color: #111; /* dark soft black */
  white-space: nowrap;
  animation: floatRightLoop 50s linear infinite;
}

@keyframes floatRightLoop {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); } /* seamless loop */
}
</style>

  

    
  <section id="subjects" class="py-5">
    <div class="container">
      <h2 class="fw-bold text-center text-white mb-5">Explore Subjects</h2>
      <div class="row g-4">
        <!-- Subject Card -->
        <div class="col-md-4">
          <div class="card shadow-lg rounded-xl overflow-hidden">
            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height:200px;">
              <img src="{{ asset('images/learning.jpg') }}" alt="[Image Placeholder]">

            </div>
            <div class="card-body text-center">
              <h5 class="card-title fw-bold">Introduction to Learning</h5>
              <p class="card-text">A 3-minute guide to quick learning, easy tips and tricks.</p>
              <a href="#" class="btn btn-dark w-100 rounded-pill">Explore</a>
            </div>
          </div>
        </div>

        <!-- Duplicate for more subjects -->
        <div class="col-md-4">
          <div class="card shadow-lg rounded-xl overflow-hidden">
            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height:200px;">
              <img src="{{ asset('images/science.jpg') }}" alt="[Image Placeholder]">
            </div>
            <div class="card-body text-center">
              <h5 class="card-title fw-bold">Science</h5>
              <p class="card-text">Understand the wonders of physics, chemistry, and biology.</p>
              <a href="#" class="btn btn-dark w-100 rounded-pill">Explore</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-lg rounded-xl overflow-hidden">
              <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height:200px;">
                <img src="{{ asset('images/math.jpg') }}" alt="[Image Placeholder]">
              </div>
              <div class="card-body text-center">
                <h5 class="card-title fw-bold">Mathematics</h5>
                <p class="card-text">Unlock the world of numbers, logic, and problem-solving with practical applications.</p>
                <a href="#" class="btn btn-dark w-100 rounded-pill">Explore</a>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card shadow-lg rounded-xl overflow-hidden">
              <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height:200px;">
                <img src="{{ asset('images/english.png') }}" alt="[Image Placeholder]">
              </div>
              <div class="card-body text-center">
                <h5 class="card-title fw-bold">English</h5>
                <p class="card-text">Master communication through literature, writing, and critical thinking.</p>
                <a href="#" class="btn btn-dark w-100 rounded-pill">Explore</a>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card shadow-lg rounded-xl overflow-hidden">
              <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height:200px;">
                <img src="{{ asset('images/filipino.png') }}" alt="[Image Placeholder]">              </div>
              <div class="card-body text-center">
                <h5 class="card-title fw-bold">Filipino</h5>
                <p class="card-text">Celebrate language and culture while improving reading, writing, and speaking skills.</p>
                <a href="#" class="btn btn-dark w-100 rounded-pill">Explore</a>
              </div>
            </div>
          </div>

        <div class="col-md-4">
          <div class="card shadow-lg rounded-xl overflow-hidden">
            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height:200px;">
              <img src="{{ asset('images/history.png') }}" alt="[Image Placeholder]">
            </div>
            <div class="card-body text-center">
              <h5 class="card-title fw-bold">History</h5>
              <p class="card-text">Dive into key historical events, dates, and significant figures.</p>
              <a href="#" class="btn btn-dark w-100 rounded-pill">Explore</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Resources Section -->
  <section id="resources" class="py-5 bg-light text-dark">
    <div class="container">
      <h2 class="fw-bold text-center mb-4">Helpful Resources</h2>
      <div class="row">
        <div class="col-md-6">
          <div class="p-4 shadow-sm rounded bg-white">
            <h5>Watch Educational Videos</h5>
            <div class="ratio ratio-16x9">
              <iframe src="" title="YouTube video" allowfullscreen></iframe>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-4 shadow-sm rounded bg-white">
            <h5>Download Study Materials</h5>
            <p>Access free PDFs, flashcards, and study guides to boost your learning.</p>
            <button class="btn btn-primary rounded-pill">Get Materials</button>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ======================= FEATURED MATERIALS ======================= -->
  <section id="materials" class="py-16 bg-white">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-extrabold text-3xl md:text-4xl mb-2 text-brand-700">Featured Materials</h2>
        <p class="text-slate-600">Fresh handouts, PDFs, PPTs & review sheets — sorted and easy to grab.</p>
      </div>

      <div class="row g-4">
        <!-- 6 resource cards -->
        <div class="col-12 col-md-6 col-lg-4">
          <div class="resource-card card h-100 reveal">
            <div class="card-body d-flex align-items-start gap-3">
              <i class="bi bi-filetype-pdf fs-1 text-brand-600"></i>
              <div class="flex-1">
                <h6 class="fw-bold mb-1">Calculus Quick Review</h6>
                <small class="text-slate-600">PDF • 12 pages • Updated</small>
              </div>
              <a href="#" class="btn btn-outline-brand btn-sm">Download</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <div class="resource-card card h-100 reveal">
            <div class="card-body d-flex align-items-start gap-3">
              <i class="bi bi-filetype-ppt fs-1 text-brand-600"></i>
              <div class="flex-1">
                <h6 class="fw-bold mb-1">Chemistry Lab Safety</h6>
                <small class="text-slate-600">PPT • Slides</small>
              </div>
              <a href="#" class="btn btn-outline-brand btn-sm">Download</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <div class="resource-card card h-100 reveal">
            <div class="card-body d-flex align-items-start gap-3">
              <i class="bi bi-filetype-doc fs-1 text-brand-600"></i>
              <div class="flex-1">
                <h6 class="fw-bold mb-1">Grammar Essentials</h6>
                <small class="text-slate-600">DOCX • Practice Sheets</small>
              </div>
              <a href="#" class="btn btn-outline-brand btn-sm">Download</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <div class="resource-card card h-100 reveal">
            <div class="card-body d-flex align-items-start gap-3">
              <i class="bi bi-file-earmark-text fs-1 text-brand-600"></i>
              <div class="flex-1">
                <h6 class="fw-bold mb-1">Philippine History Key Dates</h6>
                <small class="text-slate-600">PDF • Cheat Sheet</small>
              </div>
              <a href="#" class="btn btn-outline-brand btn-sm">Download</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <div class="resource-card card h-100 reveal">
            <div class="card-body d-flex align-items-start gap-3">
              <i class="bi bi-filetype-csv fs-1 text-brand-600"></i>
              <div class="flex-1">
                <h6 class="fw-bold mb-1">Periodic Table — Reference</h6>
                <small class="text-slate-600">CSV + PDF</small>
              </div>
              <a href="#" class="btn btn-outline-brand btn-sm">Download</a>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <div class="resource-card card h-100 reveal">
            <div class="card-body d-flex align-items-start gap-3">
              <i class="bi bi-collection-play fs-1 text-brand-600"></i>
              <div class="flex-1">
                <h6 class="fw-bold mb-1">Exam Prep Flashcards</h6>
                <small class="text-slate-600">Interactive • Web</small>
              </div>
              <a href="#" class="btn btn-outline-brand btn-sm">Open</a>
            </div>
          </div>
        </div>

      </div>

      <div class="text-center mt-4">
        <a href="#" class="btn btn-accent rounded-pill px-4">View All Materials</a>
      </div>
    </div>
  </section>


<!-- ======================= HOW IT WORKS ======================= -->
<section id="hiw" class="py-16" style="background-color:#f1f5f9;">
  <div class="container">
    <!-- Section header -->
    <div class="text-center mb-5">
      <h2 class="fw-extrabold mb-2" style="color:#1d4ed8; font-size:2rem;">How It Works</h2>
      <p style="color:#475569;">Three simple steps to study smarter.</p>
    </div>

    <div class="row g-4">
      <!-- Step 1 -->
      <div class="col-md-4">
        <div class="hiw-card h-100 p-4 text-center reveal">
          <div class="hiw-icon"><i class="bi bi-grid"></i></div>
          <h5 class="fw-bold mt-3">Pick a Subject</h5>
          <p class="text-slate-600 mb-0">Browse categories and choose what you need today.</p>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="col-md-4">
        <div class="hiw-card h-100 p-4 text-center reveal">
          <div class="hiw-icon"><i class="bi bi-bookmark-check"></i></div>
          <h5 class="fw-bold mt-3">Save Materials</h5>
          <p class="text-slate-600 mb-0">Collect PDFs, slides, and notes into your dashboard.</p>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="col-md-4">
        <div class="hiw-card h-100 p-4 text-center reveal">
          <div class="hiw-icon"><i class="bi bi-bar-chart-steps"></i></div>
          <h5 class="fw-bold mt-3">Track Progress</h5>
          <p class="text-slate-600 mb-0">See completion rates and get gentle reminders.</p>
        </div>
      </div>
    </div>
  </div>
</section>


  <!-- ======================= EMBEDDED VIDEOS ======================= -->
<section id="video" class="py-10 bg-white">
  <div class="container">
    
    <!-- Section header -->
    <div class="text-center mb-5">
      <h2 class="fw-extrabold text-brand-700 mb-2 fs-3 fs-md-2">Watch & Learn</h2>
      <p class="text-slate-600 mb-0">Short educational videos to kickstart your session.</p>
    </div>

    <!-- Video grid -->
    <div class="row g-4">
      <div class="col-12 col-lg-6">
        <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-lg reveal">
          <iframe src="https://www.youtube.com/embed/5MgBikgcWnY" title="Learning How to Learn" allowfullscreen></iframe>
        </div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-lg reveal">
          <iframe src="https://www.youtube.com/embed/mNBmG24djoY" title="Pomodoro for Studying" allowfullscreen></iframe>
        </div>
      </div>
    </div>

  </div>
</section>


<!-- ======================= TESTIMONIALS ======================= -->
<section id="testimonials" class="py-16 bg-slate-50">
  <div class="container">

    <!-- Heading -->
    <div class="text-center mb-5">
      <h2 class="fw-extrabold text-3xl md:text-4xl mb-2 text-brand-700">What Learners Say</h2>
      <p class="text-slate-600">Real feedback from students using Smart Study Hub.</p>
    </div>

    <!-- Carousel -->
    <div id="tSlider" class="carousel slide reveal" data-bs-ride="carousel">
      
      <!-- Indicators -->
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#tSlider" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#tSlider" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#tSlider" data-bs-slide-to="2"></button>
      </div>

      <!-- Slides -->
      <div class="carousel-inner">
        <div class="carousel-item active p-4 p-md-5 bg-white rounded-3 shadow-lg">
          <blockquote class="blockquote text-center m-0">
            <p class="mb-3">“I finally have all my reviewers in one place. The reminders keep me consistent.”</p>
            <footer class="blockquote-footer">Jane • BSIT</footer>
          </blockquote>
        </div>

        <div class="carousel-item p-4 p-md-5 bg-white rounded-3 shadow-lg">
          <blockquote class="blockquote text-center m-0">
            <p class="mb-3">“Clean layout, calm colors, and I love the progress tracking.”</p>
            <footer class="blockquote-footer">Mark • STEM</footer>
          </blockquote>
        </div>

        <div class="carousel-item p-4 p-md-5 bg-white rounded-3 shadow-lg">
          <blockquote class="blockquote text-center m-0">
            <p class="mb-3">“Materials are updated and easy to download. Huge time-saver!”</p>
            <footer class="blockquote-footer">Aira • HUMSS</footer>
          </blockquote>
        </div>
      </div>
    </div>

  </div>
</section>


<!-- ======================= FINAL CTA ======================= -->
<section id="cta" class="py-10 bg-white">
  <div class="container">
    <div class="cta-banner d-flex flex-column flex-lg-row align-items-center justify-content-between gap-3 p-4 p-md-5 rounded-4 shadow-xl reveal">
      
      <!-- Text content -->
      <div class="mb-3 mb-lg-0">
        <h3 class="fw-extrabold text-brand-800 mb-2">Ready to learn smarter?</h3>
        <p class="text-slate-600 mb-0">Create your free profile, save materials, and track your study streak.</p>
      </div>

      <!-- Buttons -->
      <div class="d-flex gap-3 flex-wrap">
        <a href="#" class="btn btn-accent px-4 rounded-pill">Create Account</a>
        <a href="#subjects" class="btn btn-outline-brand px-4 rounded-pill">Explore Subjects</a>
      </div>
      
    </div>
  </div>
</section>


<!-- ======================= FOOTER ======================= -->
<footer class="footer-custom pt-10 pb-6">
  <div class="container">
    <div class="row g-4">

      <!-- Branding -->
      <div class="col-md-6">
        <div class="d-flex align-items-center gap-2 mb-3">
          <div class="logo-circle small-circle d-flex align-items-center justify-content-center">SSH</div>
          <span class="fw-bold fs-5">Smart Study Hub</span>
        </div>
        <p class="text-footer-secondary mb-3">
          Organize materials, learn consistently, and see your progress — all in one calm, focused space.
        </p>
      </div>

      <!-- Explore Links -->
      <div class="col-6 col-md-3">
        <h6 class="fw-bold mb-2 text-footer-primary">Explore</h6>
        <ul class="list-unstyled text-footer-secondary">
          <li><a href="#subjects" class="footer-link">Subjects</a></li>
          <li><a href="#materials" class="footer-link">Materials</a></li>
          <li><a href="#video" class="footer-link">Videos</a></li>
        </ul>
      </div>

      <!-- Links -->
      <div class="col-6 col-md-3">
        <h6 class="fw-bold mb-2 text-footer-primary">Links</h6>
        <ul class="list-unstyled text-footer-secondary">
          <li><a href="{{ route('about') }}" class="footer-link">About</a></li>
          <li><a href="{{ route('contact') }}" class="footer-link">Contact</a></li>
        </ul>
      </div>
      <!-- Contact -->
      <div class="col-6 col-md-3">
        <h6 class="fw-bold mb-2 text-footer-primary">Contact</h6>
        <ul class="list-unstyled text-footer-secondary">
          <li>Email: hello@smartstudyhub.edu</li>
          <li>Support Center</li>
        </ul>
      </div>

    </div>

    <hr class="footer-border my-4" />

    <div class="text-center text-footer-muted small">&copy; 2025 Smart Study Hub. All rights reserved.</div>
  </div>
</footer>


  <!-- Bootstrap Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Minimal JS for effects -->
  <script>
    // Navbar background on scroll
    const nav = document.getElementById('topNav');
    const toggleNav = () => {
      if (window.scrollY > 10) nav.classList.add('scrolled');
      else nav.classList.remove('scrolled');
    };
    toggleNav(); window.addEventListener('scroll', toggleNav);

    // Reveal on scroll
    const io = new IntersectionObserver(entries => {
      entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('show'); });
    }, {threshold: 0.15});
    document.querySelectorAll('.reveal').forEach(el => io.observe(el));

    // Animated counters
    const counters = document.querySelectorAll('.counter');
    const animateCounter = (el)=>{
      const target = +el.dataset.target, step = Math.max(1, Math.floor(target/120));
      let n=0; const t = setInterval(()=>{
        n += step; if(n>=target){n=target; clearInterval(t);}
        el.textContent = n.toLocaleString();
      }, 16);
    };
    counters.forEach(animateCounter);
  </script>
</body>
</html>
