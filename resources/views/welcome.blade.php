<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <!--Styles ,bootstrap and others-->
        <link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.min.css')}}" />
      <!-- font awesome style -->
    
      <link href="{{asset('/font-awesome/css/all.min.css')}}" rel="stylesheet" />
      <!-- Custom styles for this template-->
        <!-- responsive style -->
      
        <!-- Fonts -->
        <!--<link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />-->

   
            <style>
               
               body {
            font-family: 'Segoe UI', sans-serif;
            background-color:rgb(194, 194, 194);
            color: #333;
        }
        .navbar {
            background-color: #0c1b13;
        }
        .navbar-brand {
            color: #fff;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .nav-link {
            color: #d4d4d4;
        }
        .hero {
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.7)), url('./img/justartback.png');
            background-size: cover;
            background-position: center;
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        .section-title {
            text-align: center;
            margin-top: 60px;
            margin-bottom: 40px;
        }
        #about, #services, #contact {
            background-color: #fff;
            padding: 60px 0;
            border-radius: 20px;
            margin-bottom: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: scale(1.03);
        }
        footer {
            background-color: #0c1b13;
            color: #fff;
            padding: 20px 0;
        }
        .icon {
            font-size: 2rem;
            color: #007f5f;
        }
    
            </style>
    
    </head>
    <body >
        <header class="">
        <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Justarttech</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#services" id="navbarDropdown" role="button" data-bs-toggle="dropdown">Our Services</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{route('login')}}">Business Management System</a></li>
                            <li><a class="dropdown-item" href="#">Future Solution Placeholder</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
    </header>
            
    <section class="hero">
        <div class="container">
            <h1>Empowering Businesses with Smart Digital Tools</h1>
            <p>Run, manage, and grow your business from the palm of your hand.</p>
            <a href="#services" class="btn btn-success btn-lg mt-4">Explore Our Solutions</a>
        </div>
    </section>

    <section id="about" class="container mt-2">
        <div class="section-title">
            <h2 class="text-success">About Justartech</h2>
            <p>Justarttech is your reliable partner in digital innovation. We blend traditional business knowledge with advanced digital tools — including AI and automation — to create smart, secure, and scalable systems tailored for real-world needs.</p>
        </div>
    </section>

    <section id="services" class="container p-2">
        <div class="section-title">
            <h2 class="text-success">Our Services</h2>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card p-4">
                    <div class="card-body text-center">
                        <i class="fas fa-store icon"></i>
                        <h5 class="card-title mt-3">Business Management</h5>
                        <p class="card-text">Track sales, manage products, and monitor business performance in real-time — all from your smartphone.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4">
                    <div class="card-body text-center">
                        <i class="fas fa-brain icon"></i>
                        <h5 class="card-title mt-3">AI Integration</h5>
                        <p class="card-text">Our systems are designed to scale and integrate with artificial intelligence, making your operations smarter and more efficient.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4">
                    <div class="card-body text-center">
                        <i class="fas fa-network-wired icon"></i>
                        <h5 class="card-title mt-3">Future Ecosystem</h5>
                        <p class="card-text">From agriculture to education, our SaaS platform will continue evolving with modules for every industry.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="container p-2">
        <div class="section-title">
            <h2 class="text-success">Contact Us</h2>
            <p>Have questions, want a demo, or interested in collaboration? Reach out now.</p>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h5 class="text-success">Email</h5>
                <p><i class="fas fa-envelope"></i> justarttech@gmail.com</p>
                <h5 class="text-success">Phone</h5>
                <p><i class="fas fa-phone"></i> +254 712 345 678</p>
                <h5 class="text-success">Location</h5>
                <p><i class="fas fa-map-marker-alt"></i> Nairobi, Kenya</p>
            </div>
            <div class="col-md-6">
                <form class="row g-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Your Name" required>
                    </div>
                    <div class="col-md-6">
                        <input type="email" class="form-control" placeholder="Your Email" required>
                    </div>
                    <div class="col-12">
                        <textarea class="form-control" rows="4" placeholder="Your Message"></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <footer class="text-center">
        <div class="container">
            <p>&copy; 2025 Justarttech. All rights reserved.</p>
        </div>
    </footer>


<script src="{{asset('/js/bootstrap.bundle.min.js')}}"></script>
    </body>
</html>
