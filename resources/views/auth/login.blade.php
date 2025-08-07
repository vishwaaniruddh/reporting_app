<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - eSurvTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fa;
            --accent-color: #2e59d9;
        }
        
        body {
            background-color: var(--secondary-color);
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            border: none;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            transform-style: preserve-3d;
            transition: all 0.5s ease;
            z-index: 10;
        }
        
        .card-title {
            color: var(--primary-color);
            font-weight: 700;
            letter-spacing: 1px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .card-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            border-radius: 3px;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: var(--accent-color);
            transform: translateY(-2px);
        }
        
        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .shape {
            position: absolute;
            opacity: 0.15;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary-color), #86c1ff);
        }
        
        .password-container {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s;
        }
        
        .toggle-password:hover {
            color: var(--primary-color);
        }
        
        .input-highlight {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2px;
            width: 0;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            transition: width 0.4s ease;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #eee;
        }
        
        .alert {
            border-radius: 8px;
        }
        
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Floating background shapes -->
        <div class="floating-shapes" id="floatingShapes"></div>
        
        <!-- Animated particles -->
        <canvas class="particles" id="particles"></canvas>
        
        <div class="card login-card shadow-lg" id="loginCard">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">eSurvTrack</h3>
                <p class="text-center text-muted mb-4">Welcome back! Please login to your account.</p>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success mb-4" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <div class="fw-bold">Whoops! Something went wrong.</div>
                        <ul class="mt-2 mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <div class="mb-4 input-wrapper">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                        <div class="input-highlight" id="emailHighlight"></div>
                    </div>

                    <div class="mb-4 input-wrapper password-container">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                        <i class="toggle-password fas fa-eye" id="togglePassword"></i>
                        <div class="input-highlight" id="passwordHighlight"></div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg" id="loginButton">
                            <span id="buttonText">Log in</span>
                            <i class="fas fa-spinner fa-spin" id="loadingIcon" style="display: none;"></i>
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <a href="#" class="text-decoration-none">Forgot password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize floating shapes
            createFloatingShapes();
            
            // Initialize particles
            initParticles();
            
            // Card entrance animation
            anime({
                targets: '#loginCard',
                translateY: [-50, 0],
                opacity: [0, 1],
                duration: 800,
                easing: 'easeOutExpo'
            });
            
            // Title animation
            anime({
                targets: '.card-title',
                scale: [1.1, 1],
                opacity: [0, 1],
                duration: 600,
                delay: 200,
                easing: 'easeOutElastic'
            });
            
            // Input focus animations
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                const highlightId = input.id + 'Highlight';
                const highlight = document.getElementById(highlightId);
                
                input.addEventListener('focus', () => {
                    highlight.style.width = '100%';
                    anime({
                        targets: input,
                        borderColor: '#4e73df',
                        duration: 300
                    });
                });
                
                input.addEventListener('blur', () => {
                    if (!input.value) {
                        highlight.style.width = '0%';
                    }
                    anime({
                        targets: input,
                        borderColor: '#ddd',
                        duration: 300
                    });
                });
            });
            
            // Password toggle
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
                
                // Animation for toggle
                anime({
                    targets: togglePassword,
                    scale: [1, 1.2, 1],
                    duration: 300,
                    easing: 'easeInOutQuad'
                });
            });
            
            // Form submission animation
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const buttonText = document.getElementById('buttonText');
            const loadingIcon = document.getElementById('loadingIcon');
            
            loginForm.addEventListener('submit', function(e) {
                // Prevent actual submission for this demo
                // e.preventDefault();
                
                // Show loading state
                buttonText.textContent = 'Authenticating...';
                loadingIcon.style.display = 'inline-block';
                loginButton.disabled = true;
                
                anime({
                    targets: loginButton,
                    scale: [1, 0.95, 1],
                    backgroundColor: ['#4e73df', '#3a5fce'],
                    duration: 800,
                    easing: 'easeInOutQuad'
                });
                
                // Simulate API call delay
                setTimeout(() => {
                    buttonText.textContent = 'Success!';
                    anime({
                        targets: loginButton,
                        backgroundColor: ['#3a5fce', '#28a745'],
                        duration: 500
                    });
                }, 1500);
            });
            
            // Hover effect for login button
            loginButton.addEventListener('mouseenter', () => {
                anime({
                    targets: loginButton,
                    scale: 1.02,
                    duration: 200
                });
            });
            
            loginButton.addEventListener('mouseleave', () => {
                anime({
                    targets: loginButton,
                    scale: 1,
                    duration: 200
                });
            });
            
            // Background shapes animation
            function animateShapes() {
                const shapes = document.querySelectorAll('.shape');
                shapes.forEach((shape, index) => {
                    const delay = index * 100;
                    const duration = 15000 + Math.random() * 10000;
                    const direction = Math.random() > 0.5 ? 1 : -1;
                    
                    anime({
                        targets: shape,
                        translateX: direction * (100 + Math.random() * 200),
                        translateY: direction * (50 + Math.random() * 100),
                        rotate: direction * 360,
                        duration: duration,
                        delay: delay,
                        easing: 'linear',
                        loop: true,
                        direction: 'alternate'
                    });
                });
            }
            
            function createFloatingShapes() {
                const container = document.getElementById('floatingShapes');
                const colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'];
                
                for (let i = 0; i < 15; i++) {
                    const shape = document.createElement('div');
                    shape.className = 'shape';
                    
                    // Random properties
                    const size = 50 + Math.random() * 150;
                    const color = colors[Math.floor(Math.random() * colors.length)];
                    const opacity = 0.05 + Math.random() * 0.1;
                    const left = Math.random() * 100;
                    const top = Math.random() * 100;
                    
                    shape.style.width = `${size}px`;
                    shape.style.height = `${size}px`;
                    shape.style.background = color;
                    shape.style.opacity = opacity;
                    shape.style.left = `${left}%`;
                    shape.style.top = `${top}%`;
                    
                    container.appendChild(shape);
                }
                
                animateShapes();
            }
            
            // Particle animation
            function initParticles() {
                const canvas = document.getElementById('particles');
                const ctx = canvas.getContext('2d');
                
                // Set canvas size
                canvas.width = canvas.offsetWidth;
                canvas.height = canvas.offsetHeight;
                
                // Particles array
                const particles = [];
                const particleCount = Math.floor(canvas.width * canvas.height / 10000);
                
                // Particle constructor
                function Particle() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 3 + 1;
                    this.speedX = Math.random() * 1 - 0.5;
                    this.speedY = Math.random() * 1 - 0.5;
                    this.color = `rgba(78, 115, 223, ${Math.random() * 0.2 + 0.05})`;
                }
                
                // Initialize particles
                for (let i = 0; i < particleCount; i++) {
                    particles.push(new Particle());
                }
                
                // Animation loop
                function animate() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    
                    // Update and draw particles
                    for (let i = 0; i < particles.length; i++) {
                        const p = particles[i];
                        
                        // Update position
                        p.x += p.speedX;
                        p.y += p.speedY;
                        
                        // Bounce off edges
                        if (p.x < 0 || p.x > canvas.width) p.speedX *= -1;
                        if (p.y < 0 || p.y > canvas.height) p.speedY *= -1;
                        
                        // Draw particle
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                        ctx.fillStyle = p.color;
                        ctx.fill();
                        
                        // Draw connections
                        for (let j = i + 1; j < particles.length; j++) {
                            const p2 = particles[j];
                            const dx = p.x - p2.x;
                            const dy = p.y - p2.y;
                            const distance = Math.sqrt(dx * dx + dy * dy);
                            
                            if (distance < 100) {
                                ctx.beginPath();
                                ctx.strokeStyle = `rgba(78, 115, 223, ${1 - distance/100})`;
                                ctx.lineWidth = 0.5;
                                ctx.moveTo(p.x, p.y);
                                ctx.lineTo(p2.x, p2.y);
                                ctx.stroke();
                            }
                        }
                    }
                    
                    requestAnimationFrame(animate);
                }
                
                // Handle resize
                window.addEventListener('resize', function() {
                    canvas.width = canvas.offsetWidth;
                    canvas.height = canvas.offsetHeight;
                });
                
                // Start animation
                animate();
            }
        });
    </script>
</body>
</html>