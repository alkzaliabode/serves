


<footer class="app-footer glassmorphism-footer shadow-lg">
    <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap py-3">
        <div class="footer-copyright-text animated-footer-item">
            <strong>جميع حقوق النشر محفوظة &copy; <?php echo e(date('Y')); ?> 
                <a href="#" class="company-link glow-on-hover">الشعبة الخدمية</a>.
            </strong> 
            <span class="d-none d-md-inline-block ms-1">جميع الحقوق محفوظة.</span>
        </div>
        <div class="float-end d-none d-sm-inline-block footer-version-info animated-footer-item">
            <b>الإصدار</b> <span class="version-number">1.0.0</span>
        </div>
    </div>
</footer>

<?php $__env->startPush('styles'); ?>
<style>
    /* Global variables for consistent styling */
    :root {
        --footer-bg-color: rgba(255, 255, 255, 0.05); /* Light transparent background */
        --footer-border-color: rgba(255, 255, 255, 0.1); /* Subtle border */
        --footer-shadow-color: 0 -4px 20px rgba(0, 0, 0, 0.1); /* Soft shadow, top effect */
        --footer-text-color: #ffffff; /* White text for contrast */
        --link-color: #00eaff; /* Cyan for links */
        --glow-color: rgba(0, 234, 255, 0.6); /* Stronger glow */
        --transition-speed: 0.3s;
    }

    /* Glassmorphism Footer styling */
    .glassmorphism-footer {
        background: var(--footer-bg-color) !important;
        backdrop-filter: blur(8px) !important; /* Stronger blur */
        -webkit-backdrop-filter: blur(8px) !important; /* For Safari */
        border-top: 1px solid var(--footer-border-color) !important;
        box-shadow: var(--footer-shadow-color) !important;
        padding: 1rem 1.5rem; /* Adjust padding */
        position: relative; /* For animations */
        z-index: 1040; /* Ensure it's above most content */
        transition: all var(--transition-speed) ease-in-out;
        margin-top: auto; /* Push footer to bottom */
    }

    .glassmorphism-footer .container-fluid {
        min-height: 50px; /* Ensure a minimum height */
    }

    .glassmorphism-footer strong,
    .glassmorphism-footer b,
    .glassmorphism-footer .version-number {
        color: var(--footer-text-color) !important;
        font-weight: 500;
        text-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }

    /* Animated entry for footer items */
    .animated-footer-item {
        opacity: 0;
        transform: translateY(20px);
    }

    .footer-copyright-text {
        display: flex; /* Use flex to align text and link */
        align-items: center;
        flex-wrap: wrap; /* Allow wrapping on small screens */
    }

    .footer-copyright-text .company-link {
        color: var(--link-color) !important;
        text-decoration: none;
        margin: 0 5px; /* Space around the link */
        transition: all var(--transition-speed) ease-in-out;
        position: relative;
        z-index: 1; /* For glow effect */
    }

    /* Glow on hover effect for company link */
    .footer-copyright-text .company-link.glow-on-hover:hover {
        color: var(--link-color) !important;
        text-shadow: 0 0 8px var(--glow-color), 0 0 15px rgba(0, 234, 255, 0.4);
        transform: translateY(-2px) scale(1.02); /* Slight lift and scale */
    }

    .footer-version-info {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.7) !important;
    }

    /* Responsive adjustments */
    @media (max-width: 767px) {
        .glassmorphism-footer .container-fluid {
            flex-direction: column; /* Stack items vertically */
            text-align: center;
        }
        .footer-copyright-text,
        .footer-version-info {
            width: 100%; /* Full width for stacked items */
            margin-bottom: 0.5rem;
            justify-content: center; /* Center content */
        }
        .footer-version-info {
            margin-bottom: 0; /* No margin for the last item */
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // GSAP Timeline for Footer entrance animation
        const footerTimeline = gsap.timeline({ defaults: { ease: "power3.out" } });

        footerTimeline
            .from(".animated-footer-item", {
                y: 30,
                opacity: 0,
                stagger: 0.2, // Stagger effect for each footer item
                duration: 0.8
            })
            .from(".glassmorphism-footer", {
                backdropFilter: "blur(0px)",
                duration: 0.5
            }, "<0.2"); // Apply blur effect slightly after items start moving

        // Company Link Hover Animation
        const companyLink = document.querySelector('.company-link');
        if (companyLink) {
            companyLink.addEventListener('mouseenter', () => {
                gsap.to(companyLink, {
                    scale: 1.05,
                    y: -2,
                    duration: 0.3,
                    ease: "power1.out"
                });
            });
            companyLink.addEventListener('mouseleave', () => {
                gsap.to(companyLink, {
                    scale: 1,
                    y: 0,
                    duration: 0.3,
                    ease: "power1.out"
                });
            });
        }
    });
</script>
<?php $__env->stopPush(); ?><?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/layouts/partials/_footer.blade.php ENDPATH**/ ?>