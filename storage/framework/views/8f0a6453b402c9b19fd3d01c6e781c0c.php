


<nav class="app-header navbar navbar-expand glassmorphism-navbar shadow-lg">
    <div class="container-fluid">
        
        <ul class="navbar-nav">
            <li class="nav-item">
                
                <a class="nav-link animated-nav-item" id="sidebarToggle" data-lte-toggle="sidebar" href="javascript:void(0)" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                
                <a href="<?php echo e(route('home')); ?>" class="nav-link animated-nav-item glow-on-hover">الرئيسية</a>
            </li>
            
        </ul>

        
        
        <ul class="navbar-nav ms-auto">
            
            <li class="nav-item dropdown animated-nav-item">
                <a class="nav-link notification-bell" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="far fa-bell"></i>
                    <span class="navbar-badge badge bg-warning navbar-badge-animated">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end animated-dropdown">
                    <span class="dropdown-header">15 إشعار</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope me-2"></i> 4 رسائل جديدة
                        <span class="float-end text-muted text-sm">3 دقائق</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users me-2"></i> 8 طلبات صداقة
                        <span class="float-end text-muted text-sm">12 ساعة</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file me-2"></i> 3 تقارير جديدة
                        <span class="float-end text-muted text-sm">يومان</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo e(route('notifications.index')); ?>" class="dropdown-item dropdown-footer">عرض كل الإشعارات</a>
                </div>
            </li>

            
            <li class="nav-item dropdown user-menu animated-nav-item">
                <a href="#" class="nav-link user-profile-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo e(Auth::user()->profile_photo_url ?? 'https://placehold.co/160x160/cccccc/ffffff?text=U'); ?>" class="user-image img-circle shadow" alt="User Image">
                    <span class="d-none d-md-inline text-white-shadow"><?php echo e(Auth::user()->name ?? 'الضيف'); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end animated-dropdown">
                    
                    <li class="user-header bg-primary">
                        <img src="<?php echo e(Auth::user()->profile_photo_url ?? 'https://placehold.co/160x160/cccccc/ffffff?text=U'); ?>" class="rounded-circle shadow" alt="User Image">
                        <p class="text-white-shadow">
                            <?php echo e(Auth::user()->name ?? 'الضيف'); ?> - <?php echo e(Auth::user()->email ?? ''); ?>

                            <small>عضو منذ <?php echo e((Auth::user()->created_at ?? now())->format('M. Y')); ?></small>
                        </p>
                    </li>
                    
                    <li class="user-footer">
                        <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-default btn-flat profile-btn glow-on-hover">الملف الشخصي</a>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="float-end">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-default btn-flat logout-btn glow-on-hover">تسجيل الخروج</button>
                        </form>
                    </li>
                </ul>
            </li>

            
            <li class="nav-item animated-nav-item">
                <a class="nav-link fullscreen-toggle" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>

<?php $__env->startPush('styles'); ?>
<style>
    /* Global variables for consistent styling */
    :root {
        --navbar-bg-color: rgba(255, 255, 255, 0.08); /* Light transparent background */
        --navbar-border-color: rgba(255, 255, 255, 0.2); /* Subtle border */
        --navbar-shadow-color: 0 4px 30px rgba(0, 0, 0, 0.1); /* Soft shadow */
        --navbar-text-color: #ffffff; /* White text for contrast */
        --navbar-icon-color: rgba(255, 255, 255, 0.8); /* Slightly transparent white for icons */
        --hover-effect-color: #00eaff; /* Cyan/aqua for hover */
        --glow-color: rgba(0, 234, 255, 0.6); /* Stronger glow */
        --transition-speed: 0.3s;
    }

    /* Glassmorphism Navbar styling */
    .glassmorphism-navbar {
        background: var(--navbar-bg-color) !important;
        backdrop-filter: blur(10px) !important; /* Stronger blur */
        -webkit-backdrop-filter: blur(10px) !important; /* For Safari */
        border-bottom: 1px solid var(--navbar-border-color) !important;
        box-shadow: var(--navbar-shadow-color) !important;
        padding: 0.5rem 1.5rem; /* Adjust padding */
        position: sticky;
        top: 0;
        z-index: 1050; /* Ensure it's above most content */
        transition: all var(--transition-speed) ease-in-out;
    }

    .glassmorphism-navbar .nav-link {
        color: var(--navbar-text-color) !important;
        font-weight: 500;
        padding: 0.75rem 1rem; /* More padding for links */
        border-radius: 8px; /* Slightly rounded corners */
        transition: all var(--transition-speed) ease-in-out;
        position: relative;
        overflow: hidden; /* For glow effect */
    }

    .glassmorphism-navbar .nav-link:hover {
        color: var(--hover-effect-color) !important;
        background-color: rgba(255, 255, 255, 0.15); /* Lighter hover background */
        transform: translateY(-2px); /* Slight lift */
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); /* Add shadow on hover */
    }

    .glassmorphism-navbar .nav-link i {
        color: var(--navbar-icon-color) !important;
        font-size: 1.25rem; /* Larger icons */
        transition: all var(--transition-speed) ease-in-out;
    }

    .glassmorphism-navbar .nav-link:hover i {
        color: var(--hover-effect-color) !important;
        transform: scale(1.1) rotate(5deg); /* Icon slight scale and rotate */
    }

    /* Animated entry for navbar items */
    .animated-nav-item {
        opacity: 0;
        transform: translateY(-20px);
    }

    /* Glow on hover effect for text links and buttons */
    .glow-on-hover {
        position: relative;
        z-index: 1;
        transition: color var(--transition-speed), text-shadow var(--transition-speed);
    }

    .glow-on-hover:hover {
        color: var(--hover-effect-color) !important;
        text-shadow: 0 0 8px var(--glow-color), 0 0 15px rgba(0, 234, 255, 0.4);
    }

    /* Notification Bell specific styling */
    .notification-bell .navbar-badge {
        font-size: 0.7em;
        padding: 0.3em 0.6em;
        border-radius: 50%;
        position: absolute;
        top: 5px;
        right: 5px;
        animation: pulseBadge 1.5s infinite; /* Pulsing animation for badge */
        box-shadow: 0 0 10px rgba(255, 193, 7, 0.7);
    }

    /* User Profile Toggle */
    .user-profile-toggle {
        display: flex;
        align-items: center;
        gap: 10px; /* Space between image and name */
    }

    .user-profile-toggle .user-image {
        width: 35px;
        height: 35px;
        object-fit: cover;
        border: 2px solid var(--navbar-border-color); /* Subtle border for image */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        transition: transform var(--transition-speed) ease-in-out;
    }

    .user-profile-toggle:hover .user-image {
        transform: scale(1.05); /* Slight scale on hover */
    }

    .user-profile-toggle .text-white-shadow {
        color: var(--navbar-text-color) !important;
        text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
    }

    /* Dropdown Menus (Notifications & User) */
    .animated-dropdown {
        background: rgba(30, 40, 50, 0.95) !important; /* Darker, slightly transparent background */
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        border-radius: 10px;
        opacity: 0; /* Initial state for animation */
        transform: translateY(10px); /* Initial state for animation */
        transition: opacity 0.3s ease-out, transform 0.3s ease-out;
    }

    /* Animation for dropdown when shown */
    .dropdown-menu.show.animated-dropdown {
        opacity: 1;
        transform: translateY(0);
    }

    .animated-dropdown .dropdown-header {
        background-color: rgba(0, 0, 0, 0.1);
        color: var(--hover-effect-color);
        font-weight: bold;
        border-top-left-radius: 9px;
        border-top-right-radius: 9px;
    }

    .animated-dropdown .dropdown-item {
        color: var(--navbar-text-color) !important;
        transition: background-color var(--transition-speed), color var(--transition-speed);
    }

    .animated-dropdown .dropdown-item:hover {
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: var(--hover-effect-color) !important;
        text-shadow: 0 0 5px var(--glow-color);
    }

    .animated-dropdown .dropdown-divider {
        border-color: rgba(255, 255, 255, 0.05);
    }

    .animated-dropdown .dropdown-footer {
        background-color: rgba(0, 0, 0, 0.15);
        color: var(--hover-effect-color) !important;
        font-weight: bold;
        border-bottom-left-radius: 9px;
        border-bottom-right-radius: 9px;
        transition: background-color var(--transition-speed), color var(--transition-speed);
    }

    .animated-dropdown .dropdown-footer:hover {
        background-color: var(--hover-effect-color) !important;
        color: #1a1a1a !important; /* Darker text on hover */
    }

    /* User Header within dropdown */
    .user-header {
        background: linear-gradient(135deg, #0d47a1, #1976d2) !important; /* Consistent gradient */
        text-align: center;
        padding: 1rem;
        border-top-left-radius: 9px;
        border-top-right-radius: 9px;
    }

    .user-header img {
        width: 90px;
        height: 90px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        margin-bottom: 0.5rem;
        object-fit: cover;
    }

    .user-header p {
        color: white;
        text-shadow: 0 0 8px rgba(0, 0, 0, 0.7);
        margin-bottom: 0;
        font-size: 1.1rem;
    }

    .user-header p small {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.8rem;
    }

    /* User Footer buttons */
    .user-footer {
        background-color: rgba(0, 0, 0, 0.05); /* Slightly transparent */
        padding: 0.75rem 1rem;
        border-bottom-left-radius: 9px;
        border-bottom-right-radius: 9px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .user-footer .btn-flat {
        background-color: rgba(255, 255, 255, 0.1);
        color: var(--navbar-text-color) !important;
        border: 1px solid var(--navbar-border-color);
        transition: all var(--transition-speed) ease-in-out;
        border-radius: 5px;
        padding: 0.5rem 1rem;
    }

    .user-footer .btn-flat:hover {
        background-color: var(--hover-effect-color) !important;
        color: #1a1a1a !important; /* Darker text on hover */
        border-color: var(--hover-effect-color);
        box-shadow: 0 0 10px var(--glow-color);
        transform: translateY(-2px);
    }

    /* Fullscreen Toggle Button */
    .fullscreen-toggle i {
        color: var(--navbar-icon-color) !important;
        font-size: 1.3rem;
    }

    /* Keyframe Animations */
    @keyframes pulseBadge {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // GSAP Timeline for Navbar entrance animation
        const navbarTimeline = gsap.timeline({ defaults: { ease: "power3.out" } });

        navbarTimeline
            .from(".animated-nav-item", {
                y: -50,
                opacity: 0,
                stagger: 0.1, // Stagger effect for each nav item
                duration: 0.8
            })
            .from(".glassmorphism-navbar", {
                backdropFilter: "blur(0px)",
                duration: 0.5
            }, "<0.2"); // Apply blur effect slightly after items start moving


        // Sidebar Toggle Button Animation
        const sidebarToggle = document.getElementById('sidebarToggle');
        gsap.to(sidebarToggle, {
            rotation: 360,
            duration: 10,
            repeat: -1, // Infinite repeat
            ease: "none" // Linear ease for continuous rotation
        });
        gsap.to(sidebarToggle.querySelector('i'), {
            color: 'var(--hover-effect-color)',
            filter: 'drop-shadow(0 0 10px var(--glow-color))',
            repeat: -1,
            yoyo: true,
            duration: 2,
            ease: "power1.inOut"
        });


        // Fullscreen Toggle Animation
        const fullscreenToggle = document.querySelector('.fullscreen-toggle');
        const maximizeIcon = fullscreenToggle.querySelector('[data-lte-icon="maximize"]');
        const minimizeIcon = fullscreenToggle.querySelector('[data-lte-icon="minimize"]');

        fullscreenToggle.addEventListener('click', function() {
            gsap.timeline()
                .to(maximizeIcon, { rotation: 180, scale: 0.5, opacity: 0, duration: 0.3, ease: "back.in" })
                .set(maximizeIcon, { display: 'none' })
                .set(minimizeIcon, { display: 'inline-block' })
                .fromTo(minimizeIcon,
                    { rotation: -180, scale: 0.5, opacity: 0 },
                    { rotation: 0, scale: 1, opacity: 1, duration: 0.3, ease: "back.out" }
                );
        });

        // Revert animation when exiting fullscreen manually (e.g., via Esc key)
        document.addEventListener('fullscreenchange', function() {
            if (!document.fullscreenElement) {
                gsap.timeline()
                    .to(minimizeIcon, { rotation: 180, scale: 0.5, opacity: 0, duration: 0.3, ease: "back.in" })
                    .set(minimizeIcon, { display: 'none' })
                    .set(maximizeIcon, { display: 'inline-block' })
                    .fromTo(maximizeIcon,
                        { rotation: -180, scale: 0.5, opacity: 0 },
                        { rotation: 0, scale: 1, opacity: 1, duration: 0.3, ease: "back.out" }
                    );
            }
        });

        // Animate dropdowns on show
        const dropdowns = document.querySelectorAll('.dropdown-menu.animated-dropdown');
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('show.bs.dropdown', function() {
                gsap.fromTo(this,
                    { opacity: 0, y: 10, scale: 0.95 },
                    { opacity: 1, y: 0, scale: 1, duration: 0.3, ease: "back.out(1.7)" }
                );
            });
            dropdown.addEventListener('hide.bs.dropdown', function() {
                gsap.to(this,
                    { opacity: 0, y: 10, scale: 0.95, duration: 0.2, ease: "power2.in" }
                );
            });
        });

        // User dropdown specific animation for header/footer
        const userDropdown = document.querySelector('.user-menu .dropdown-menu');
        if (userDropdown) {
            userDropdown.addEventListener('show.bs.dropdown', function() {
                const userHeader = this.querySelector('.user-header');
                const userFooter = this.querySelector('.user-footer');
                const dropdownItems = this.querySelectorAll('.dropdown-item');

                gsap.from(userHeader, { y: -20, opacity: 0, duration: 0.4, ease: "power2.out" });
                gsap.from(userFooter.children, { y: 20, opacity: 0, stagger: 0.1, duration: 0.4, ease: "power2.out", delay: 0.2 });
                gsap.from(dropdownItems, { x: -20, opacity: 0, stagger: 0.05, duration: 0.3, ease: "power2.out", delay: 0.1 });
            });
        }

    });
</script>
<?php $__env->stopPush(); ?><?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/layouts/partials/_navbar.blade.php ENDPATH**/ ?>