{{-- resources/views/layouts/partials/_header.blade.php --}}
{{-- هذا الملف يحتوي على رأس المحتوى (Page Header) الذي يعرض عنوان الصفحة ومسار التنقل (Breadcrumbs). --}}

<div class="app-content-header glassmorphism-header shadow-md">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <h3 class="mb-0 page-title-animated glow-on-hover">@yield('page_title', 'الصفحة')</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end breadcrumb-animated">
                    @yield('breadcrumb')
                </ol>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Global variables for consistent styling */
    :root {
        --header-bg-color: rgba(255, 255, 255, 0.05); /* Light transparent background */
        --header-border-color: rgba(255, 255, 255, 0.1); /* Subtle border */
        --header-shadow-color: 0 4px 20px rgba(0, 0, 0, 0.1); /* Soft shadow */
        --header-text-color: #ffffff; /* White text for contrast */
        --breadcrumb-item-color: rgba(255, 255, 255, 0.7);
        --breadcrumb-active-color: #00eaff; /* Cyan for active breadcrumb */
        --glow-color: rgba(0, 234, 255, 0.6); /* Stronger glow */
        --transition-speed: 0.3s;
    }

    /* Glassmorphism Header styling */
    .glassmorphism-header {
        background: var(--header-bg-color) !important;
        backdrop-filter: blur(8px) !important; /* Stronger blur */
        -webkit-backdrop-filter: blur(8px) !important; /* For Safari */
        border-bottom: 1px solid var(--header-border-color) !important;
        box-shadow: var(--header-shadow-color) !important;
        padding: 1.25rem 1.5rem; /* Adjust padding */
        margin-bottom: 1.5rem; /* Space below the header */
        border-radius: 10px; /* Rounded corners */
        transition: all var(--transition-speed) ease-in-out;
    }

    .glassmorphism-header .page-title-animated {
        color: var(--header-text-color) !important;
        font-size: 2rem; /* Larger title */
        font-weight: 700;
        text-shadow: 0 0 10px rgba(0, 0, 0, 0.4); /* Text shadow for depth */
        transition: all var(--transition-speed) ease-in-out;
        cursor: pointer; /* Indicate interactivity */
        position: relative; /* For glow effect */
        opacity: 0; /* Initial state for animation */
        transform: translateY(-20px); /* Initial state for animation */
    }

    /* Glow on hover effect for page title */
    .glassmorphism-header .page-title-animated.glow-on-hover:hover {
        color: var(--breadcrumb-active-color) !important;
        text-shadow: 0 0 8px var(--glow-color), 0 0 15px rgba(0, 234, 255, 0.4);
    }

    /* Breadcrumbs styling */
    .breadcrumb-animated {
        background: transparent !important;
        margin-bottom: 0;
        padding: 0;
        opacity: 0; /* Initial state for animation */
        transform: translateY(20px); /* Initial state for animation */
    }

    .breadcrumb-animated .breadcrumb-item {
        color: var(--breadcrumb-item-color) !important;
        font-size: 0.95rem;
        transition: all var(--transition-speed) ease-in-out;
    }

    .breadcrumb-animated .breadcrumb-item.active {
        color: var(--breadcrumb-active-color) !important;
        font-weight: bold;
        text-shadow: 0 0 5px rgba(0, 234, 255, 0.5);
    }

    .breadcrumb-animated .breadcrumb-item + .breadcrumb-item::before {
        color: var(--breadcrumb-item-color) !important; /* Separator color */
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    .breadcrumb-animated .breadcrumb-item:hover {
        color: var(--breadcrumb-active-color) !important;
        transform: scale(1.05); /* Slight scale on hover */
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .glassmorphism-header {
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .glassmorphism-header .row {
            flex-direction: column; /* Stack title and breadcrumbs */
            text-align: center;
        }
        .glassmorphism-header .col-sm-6 {
            width: 100%;
        }
        .glassmorphism-header .page-title-animated {
            font-size: 1.75rem;
            margin-bottom: 1rem; /* Space between title and breadcrumbs */
        }
        .breadcrumb-animated {
            float: none !important; /* Remove float for centering */
            justify-content: center; /* Center breadcrumbs */
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // GSAP Timeline for Header entrance animation
        const headerTimeline = gsap.timeline({ defaults: { ease: "power3.out" } });

        headerTimeline
            .from(".page-title-animated", {
                y: -50,
                opacity: 0,
                duration: 0.8
            })
            .from(".breadcrumb-animated", {
                y: 20,
                opacity: 0,
                duration: 0.7
            }, "<0.2") // Starts 0.2 seconds after the title animation
            .from(".glassmorphism-header", {
                backdropFilter: "blur(0px)",
                duration: 0.5
            }, "<"); // Apply blur effect at the same time as breadcrumbs

        // Page Title Hover Animation
        const pageTitle = document.querySelector('.page-title-animated');
        if (pageTitle) {
            pageTitle.addEventListener('mouseenter', () => {
                gsap.to(pageTitle, {
                    scale: 1.02,
                    x: 5,
                    duration: 0.3,
                    ease: "power1.out"
                });
            });
            pageTitle.addEventListener('mouseleave', () => {
                gsap.to(pageTitle, {
                    scale: 1,
                    x: 0,
                    duration: 0.3,
                    ease: "power1.out"
                });
            });
        }

        // Breadcrumb item hover animation
        const breadcrumbItems = document.querySelectorAll('.breadcrumb-animated .breadcrumb-item');
        breadcrumbItems.forEach(item => {
            if (!item.classList.contains('active')) { // Apply hover only to non-active items
                item.addEventListener('mouseenter', () => {
                    gsap.to(item, {
                        x: 3,
                        duration: 0.2,
                        ease: "power1.out",
                        color: 'var(--breadcrumb-active-color)'
                    });
                });
                item.addEventListener('mouseleave', () => {
                    gsap.to(item, {
                        x: 0,
                        duration: 0.2,
                        ease: "power1.out",
                        color: 'var(--breadcrumb-item-color)'
                    });
                });
            }
        });

    });
</script>
@endpush