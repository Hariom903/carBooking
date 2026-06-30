// ============ PRELOADER ============
window.addEventListener('load', () => {
    const preloader = document.getElementById('preloader');
    preloader.classList.add('hidden');
    setTimeout(() => { preloader.style.display = 'none'; }, 600);
});

// ============ NAVBAR SCROLL EFFECT ============
const navbar = document.getElementById('navbar');
const backToTop = document.getElementById('backToTop');

window.addEventListener('scroll', () => {
    if (window.scrollY > 60) {
        navbar.classList.add('scrolled');
        backToTop.classList.add('show');
    } else {
        navbar.classList.remove('scrolled');
        backToTop.classList.remove('show');
    }
});

// ============ MOBILE MENU ============
const hamburger = document.getElementById('hamburger');
const navLinks = document.getElementById('navLinks');

function toggleMenu() {
    hamburger.classList.toggle('active');
    navLinks.classList.toggle('active');
}

function closeMenu() {
    hamburger.classList.remove('active');
    navLinks.classList.remove('active');
}

// Close menu on outside click
document.addEventListener('click', (e) => {
    if (!navLinks.contains(e.target) && !hamburger.contains(e.target) && navLinks.classList.contains(
        'active')) {
        closeMenu();
    }
});

// ============ BACK TO TOP ============
backToTop.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// ============ HERO PARTICLES ============
const particlesContainer = document.getElementById('heroParticles');
for (let i = 0; i < 35; i++) {
    const particle = document.createElement('div');
    particle.classList.add('particle');
    const size = Math.random() * 4 + 2;
    particle.style.width = size + 'px';
    particle.style.height = size + 'px';
    particle.style.left = Math.random() * 100 + '%';
    particle.style.animationDuration = Math.random() * 15 + 10 + 's';
    particle.style.animationDelay = Math.random() * 12 + 's';
    particle.style.opacity = Math.random() * 0.5 + 0.1;
    particlesContainer.appendChild(particle);
}

// ============ SWIPER TESTIMONIALS ============
const swiper = new Swiper('#testimonialSwiper', {
    slidesPerView: 1,
    spaceBetween: 24,
    loop: true,
    autoplay: {
        delay: 4000,
        disableOnInteraction: false,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    breakpoints: {
        640: { slidesPerView: 1 },
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 },
    },
});

// ============ FORM HANDLING ============
// Hero Booking Form
document.getElementById('heroBookingForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const name = document.getElementById('heroName').value;
    const carType = document.getElementById('carType').value;
    const pickupDate = document.getElementById('pickupDate').value;
    const returnDate = document.getElementById('returnDate').value;
    const location = document.getElementById('location').value;

    if (!name || !carType || !pickupDate || !returnDate || !location) {
        alert('Please fill in all fields to complete your booking.');
        return;
    }
    if (new Date(returnDate) <= new Date(pickupDate)) {
        alert('Return date must be after pick-up date.');
        return;
    }
    alert(
        `✅ Thank you, ${name}!\n\nYour ${carType.toUpperCase()} is reserved from ${pickupDate} to ${returnDate} in ${location.toUpperCase()}.\n\nWe'll send a confirmation email shortly.\n\n— DriveLux Team`);
    this.reset();
});

// Contact Form
document.getElementById('contactForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const inputs = this.querySelectorAll('input, textarea, select');
    let allFilled = true;
    inputs.forEach(input => {
        if (input.hasAttribute('required') && !input.value.trim()) {
            allFilled = false;
        }
    });
    if (!allFilled) {
        alert('Please fill in all required fields.');
        return;
    }
    alert(
        '✅ Message sent successfully!\n\nOur team will get back to you within 2 hours.\n\n— DriveLux Support');
    this.reset();
});

// Newsletter Form
document.getElementById('newsletterForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const email = this.querySelector('input[type="email"]').value;
    if (email) {
        alert(`🎉 Subscribed successfully!\n\nWelcome aboard, ${email}.\nExpect exclusive deals in your inbox soon!`);
        this.reset();
    }
});

// ============ SMOOTH SCROLL FOR ALL ANCHOR LINKS ============
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        const target = document.querySelector(targetId);
        if (target) {
            e.preventDefault();
            const offset = 75;
            const position = target.getBoundingClientRect().top + window.pageYOffset - offset;
            window.scrollTo({ top: position, behavior: 'smooth' });
        }
    });
});

// ============ ANIMATION ON SCROLL ============
const observerOptions = { threshold: 0.15, rootMargin: '0px 0px -40px 0px' };
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll(
    '.service-card, .testimonial-card, .about-content, .contact-info-card, .section-header').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

// ============ SET MIN DATE FOR DATE INPUTS ============
const today = new Date().toISOString().split('T')[0];
document.getElementById('pickupDate').setAttribute('min', today);
document.getElementById('returnDate').setAttribute('min', today);
document.getElementById('pickupDate').addEventListener('change', function () {
    document.getElementById('returnDate').setAttribute('min', this.value);
    if (document.getElementById('returnDate').value < this.value) {
        document.getElementById('returnDate').value = this.value;
    }
});

console.log('%c🚗 DriveLux %cPremium Car Booking %cReady',
    'font-size:24px;color:#c9a84c;font-weight:bold;',
    'font-size:16px;color:#fff;',
    'font-size:14px;color:#4ade80;');
console.log('%cAll systems go. Happy driving! 🏁', 'color:#c9a84c;font-size:13px;');