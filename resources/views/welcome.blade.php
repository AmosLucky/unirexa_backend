<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unirexa - The Social & Marketplace App for Nigerian Students</title>
    <style>
        * {
            margin: 0;
            padding: 0;
          
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 20px;
            text-align: center;
            min-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-content {
            max-width: 800px;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            font-weight: 800;
            animation: fadeInUp 1s ease;
        }

        .hero p {
            font-size: 1.4rem;
            margin-bottom: 40px;
            opacity: 0.95;
            animation: fadeInUp 1s ease 0.2s both;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease 0.4s both;
        }

        .btn {
            padding: 16px 40px;
            font-size: 1.1rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: white;
            color: #667eea;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #667eea;
        }

        /* Section Styles */
        section {
            padding: 80px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 50px;
            color: #333;
            font-weight: 700;
        }

        /* What is Unirexa */
        .what-is {
            background: #f8f9fa;
        }

        .what-is-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            font-size: 1.2rem;
            line-height: 1.8;
            color: #555;
        }

        /* Features */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .feature-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #667eea;
        }

        .feature-card p {
            color: #666;
            line-height: 1.7;
        }

        /* Why Unirexa */
        .why-unirexa {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .why-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .why-card {
            text-align: center;
            padding: 30px;
        }

        .why-card h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .why-card p {
            opacity: 0.9;
        }

        /* How It Works */
        .how-it-works {
            background: #f8f9fa;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .step {
            text-align: center;
            padding: 30px;
        }

        .step-number {
            background: #667eea;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: bold;
            margin: 0 auto 20px;
        }

        .step h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .step p {
            color: #666;
        }

        /* Screenshots */
        .screenshots {
            text-align: center;
        }

        .screenshots-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .screenshot-placeholder {
            background: linear-gradient(135deg, #e0e7ff 0%, #d1d5ff 100%);
            border-radius: 20px;
            padding: 60px 30px;
            aspect-ratio: 9/16;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Final CTA */
        .final-cta {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
        }

        .final-cta h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .final-cta p {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.95;
        }

        /* Footer */
        footer {
            background: #1a1a2e;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-links {
            margin: 20px 0;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .footer-links a:hover {
            opacity: 1;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .features-grid, .why-grid, .steps, .screenshots-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Unirexa</h1>
            <p>The Social & Marketplace App for Nigerian Students. Connect. Trade. Engage. Earn.</p>
            <div class="cta-buttons">
                <a href="#" class="btn btn-primary">Download App</a>
                <a href="#" class="btn btn-secondary">Join the Campus Community</a>
            </div>
        </div>
    </section>

    <!-- What is Unirexa -->
    <section class="what-is">
        <h2 class="section-title">What is Unirexa?</h2>
        <div class="what-is-content">
            <p>Unirexa is the all-in-one platform built specifically for Nigerian university students. We're bringing together social networking, campus marketplace, and student community into one powerful app. Whether you want to connect with classmates, sell your old textbooks, find accommodation, or just vibe with fellow students—Unirexa has you covered.</p>
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <h2 class="section-title">Everything You Need in One App</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <h3>Social Networking</h3>
                <p>Create posts, share photos and stories, like and comment on content, and stay connected with your campus community in real-time.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🛒</div>
                <h3>Student Marketplace</h3>
                <p>Buy and sell items directly with students on your campus. From textbooks and gadgets to clothes and hostel rentals—all in one place.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <h3>Rex / Unrex System</h3>
                <p>Our unique follow system. Rex other students to stay updated with their posts. Your followers are your Rexers. Build your campus network your way.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💬</div>
                <h3>Real-Time Messaging</h3>
                <p>Chat instantly with buyers, sellers, and friends. No more switching between apps—everything happens right here.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💰</div>
                <h3>Wallet & Coins</h3>
                <p>Earn coins through engagement, track your transactions, and get rewarded for being active on campus. Your hustle, your rewards.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔔</div>
                <h3>Smart Notifications</h3>
                <p>Never miss a beat. Get notified when someone Rexes you, comments on your posts, or lists something you might need.</p>
            </div>
        </div>
    </section>

    <!-- Why Unirexa -->
    <section class="why-unirexa">
        <h2 class="section-title">Why Unirexa for Students?</h2>
        <div class="why-grid">
            <div class="why-card">
                <h3>🎓 Built for Students</h3>
                <p>Designed specifically for Nigerian university life. Not just another social app.</p>
            </div>
            <div class="why-card">
                <h3>🔒 Safe Campus Trading</h3>
                <p>Trade with verified students on your campus. See phone numbers and connect directly.</p>
            </div>
            <div class="why-card">
                <h3>🤝 Easy Connections</h3>
                <p>Find your people, grow your network, and discover student opportunities effortlessly.</p>
            </div>
            <div class="why-card">
                <h3>⚡ All-in-One Platform</h3>
                <p>No more juggling multiple apps. Social, marketplace, messaging—all here.</p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works">
        <h2 class="section-title">How It Works</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Sign Up</h3>
                <p>Create your student profile in seconds</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Connect & Rex</h3>
                <p>Start rexing students and create posts</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Buy or Sell</h3>
                <p>List items or browse the campus marketplace</p>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <h3>Chat & Engage</h3>
                <p>Message sellers, comment, and earn rewards</p>
            </div>
        </div>
    </section>

    <!-- Screenshots -->
    <section class="screenshots">
        <h2 class="section-title">See Unirexa in Action</h2>
        <div class="screenshots-grid">
            <div class="screenshot-placeholder">Social Feed</div>
            <div class="screenshot-placeholder">Marketplace</div>
            <div class="screenshot-placeholder">Messaging</div>
            <div class="screenshot-placeholder">Profile & Wallet</div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="final-cta">
        <h2>Ready to Level Up Your Campus Life?</h2>
        <p>Join thousands of Nigerian students already on Unirexa</p>
        <div class="cta-buttons">
            <a href="#" class="btn btn-primary">Download on iOS</a>
            <a href="#" class="btn btn-primary">Download on Android</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <h3>Unirexa</h3>
            <div class="footer-links">
                <a href="#">About</a>
                <a href="#">Contact</a>
                <a href="privacy_policy">Privacy Policy</a>
                <a href="terms_of_service">Terms of Service</a>
            </div>
            <p>&copy; 2026 Unirexa. All rights reserved.</p>
            <p style="margin-top: 20px; opacity: 0.7;">Built with Flutter • Powered by Laravel • Real-time with Firebase</p>
        </div>
    </footer>

    <script>
        // Smooth scroll for links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.8s ease forwards';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card, .why-card, .step, .screenshot-placeholder').forEach(el => {
            el.style.opacity = '0';
            observer.observe(el);
        });
    </script>
</body>
</html>