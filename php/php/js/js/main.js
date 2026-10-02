// DOM Ready Function
document.addEventListener('DOMContentLoaded', function() {
    
    // Smooth Scroll for Anchor Links
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
    
    // Mobile Menu Toggle
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            this.querySelector('i').classList.toggle('fa-bars');
            this.querySelector('i').classList.toggle('fa-times');
        });
    }
    
    // Close mobile menu when clicking on a link
    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', function() {
            if (navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                mobileToggle.querySelector('i').classList.add('fa-bars');
                mobileToggle.querySelector('i').classList.remove('fa-times');
            }
        });
    });
    
    // Dropdown toggle for mobile
    document.querySelectorAll('.dropdown > a').forEach(dropdownLink => {
        dropdownLink.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                const dropdownMenu = this.nextElementSibling;
                dropdownMenu.classList.toggle('active');
            }
        });
    });
    
    // Newsletter Form Submission
    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            // Simple validation
            if (email && validateEmail(email)) {
                // Here you would typically send to server via AJAX
                alert('Thank you for subscribing to our newsletter!');
                this.reset();
            } else {
                alert('Please enter a valid email address');
            }
        });
    }
    
    // Email validation function
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    // Form validation for contact form
    const contactForm = document.querySelector('.contact-form form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = this.querySelector('input[name="name"]').value.trim();
            const email = this.querySelector('input[name="email"]').value.trim();
            const subject = this.querySelector('input[name="subject"]').value.trim();
            const message = this.querySelector('textarea[name="message"]').value.trim();
            
            let isValid = true;
            let errorMessage = '';
            
            if (!name) {
                isValid = false;
                errorMessage += 'Please enter your name\n';
            }
            
            if (!email || !validateEmail(email)) {
                isValid = false;
                errorMessage += 'Please enter a valid email address\n';
            }
            
            if (!subject) {
                isValid = false;
                errorMessage += 'Please enter a subject\n';
            }
            
            if (!message || message.length < 10) {
                isValid = false;
                errorMessage += 'Please enter a message (minimum 10 characters)\n';
            }
            
            if (!isValid) {
                alert(errorMessage);
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('.submit-btn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Sending...';
            submitBtn.disabled = true;
            
            // Simulate form submission (replace with actual AJAX call)
            setTimeout(() => {
                // For demo purposes - show success message
                const successMsg = document.createElement('div');
                successMsg.className = 'success-message';
                successMsg.textContent = 'Thank you for your message! We will get back to you soon.';
                this.parentElement.insertBefore(successMsg, this);
                
                successMsg.style.display = 'block';
                this.reset();
                
                // Hide success message after 5 seconds
                setTimeout(() => {
                    successMsg.style.display = 'none';
                }, 5000);
                
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }, 1500);
        });
    }
    
    // Add active class to current page in navigation
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-menu > li > a');
    
    navLinks.forEach(link => {
        const linkPath = link.getAttribute('href');
        if (linkPath && currentPath.includes(linkPath)) {
            link.parentElement.classList.add('active');
        }
    });
    
    // Back to top button
    const backToTopBtn = document.createElement('button');
    backToTopBtn.className = 'back-to-top';
    backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopBtn.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #1e3a8a, #0f172a);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        z-index: 999;
        transition: all 0.3s;
    `;
    
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    document.body.appendChild(backToTopBtn);
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.style.display = 'block';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });
    
    // Animate elements on scroll
    const animateOnScroll = () => {
        const elements = document.querySelectorAll('.news-card, .service-card, .team-member, .link-card');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3;
            
            if (elementPosition < screenPosition) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    };
    
    // Initialize animations
    document.querySelectorAll('.news-card, .service-card, .team-member, .link-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    });
    
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Run on page load
    
});