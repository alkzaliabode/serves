<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- CSRF token for Laravel form submission --}}

    <title>تسجيل الدخول - لوحة التحكم</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts for Arabic (Enhanced for more aesthetic fonts) -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Amiri:wght@400;700&family=Lateef&display=swap" rel="stylesheet" />

    <style>
        /* General Body Styling */
        body {
            font-family: 'Cairo', 'Noto Sans Arabic', 'Amiri', 'Lateef', sans-serif;
            /* ✅ خلفية متوهجة مستوحاة من الصورة */
            background: radial-gradient(circle at 20% 80%, #6a11cb 0%, transparent 40%),
                        radial-gradient(circle at 80% 20%, #2575fc 0%, transparent 40%),
                        linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d); /* Fallback/base gradient */
            background-size: 200% 200%; /* Larger background to allow movement */
            animation: backgroundPan 30s linear infinite alternate; /* Slow, continuous pan animation */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #fff;
            overflow: hidden; /* Prevent scrollbars */
            position: relative;
        }

        @keyframes backgroundPan {
            0% {
                background-position: 0% 0%;
            }
            100% {
                background-position: 100% 100%;
            }
        }

        /* Subtle Background Animation (More vibrant and glowing) - Particles/Abstract Shapes */
        body::before, body::after {
            content: '';
            position: absolute;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            opacity: 0;
            animation: floatGlow 20s infinite ease-in-out;
            z-index: -1;
            filter: blur(40px); /* Stronger blur for a dreamy glow effect */
        }

        body::before {
            width: 300px; height: 300px;
            top: 10%; left: 15%;
            animation-delay: 0s;
        }

        body::after {
            width: 400px; height: 400px;
            bottom: 10%; right: 20%;
            animation-delay: 10s;
        }

        @keyframes floatGlow {
            0% {
                transform: translate(0, 0) scale(0.8);
                opacity: 0.5;
            }
            25% {
                transform: translate(20px, -30px) scale(1);
                opacity: 0.7;
            }
            50% {
                transform: translate(0, 0) scale(0.9);
                opacity: 0.5;
            }
            75% {
                transform: translate(-20px, 30px) scale(1.1);
                opacity: 0.7;
            }
            100% {
                transform: translate(0, 0) scale(0.8);
                opacity: 0.5;
            }
        }

        /* Login Container Styling (Frosted glass with glowing border) */
        .login-container {
            background-color: rgba(255, 255, 255, 0.05); /* ✅ أكثر شفافية لمظهر زجاجي حقيقي */
            border-radius: 25px; /* ✅ حواف أكثر استدارة */
            padding: 50px; /* ✅ مساحة داخلية أكبر */
            box-shadow: 0 20px 70px rgba(0, 0, 0, 0.7); /* ✅ ظل أقوى وأكثر عمقًا */
            width: 100%;
            max-width: 550px; /* ✅ عرض أكبر قليلاً */
            text-align: center;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.15); /* ✅ حدود أكثر دقة وشفافية */
            backdrop-filter: blur(25px); /* ✅ تأثير ضبابي أقوى للزجاج */
            -webkit-backdrop-filter: blur(25px);
            animation: fadeInScale 1s ease-out forwards; /* ✅ مدة أطول للظهور */
            transition: all 0.8s cubic-bezier(0.25, 0.8, 0.25, 1); /* ✅ انتقال سلس وفاخر */
            overflow: hidden; /* Ensure inner glow doesn't spill */
        }

        /* Continuous subtle glow for the container */
        .login-container::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            /* ✅ تدرج ألوان متوهج أكثر حيوية */
            background: linear-gradient(45deg, #ff00c8, #00c8ff, #8a2be2, #ff00ff);
            background-size: 400% 400%;
            border-radius: 28px; /* Slightly larger than container for glow */
            z-index: -1;
            filter: blur(20px); /* ✅ توهج ضبابي أقوى */
            opacity: 0.8; /* ✅ عتامة أعلى للتوهج */
            animation: glowingBorder 15s linear infinite alternate; /* ✅ حركة أبطأ وأكثر فخامة */
        }

        @keyframes glowingBorder {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Hover effect for the container */
        .login-container:hover {
            transform: translateY(-8px); /* ✅ رفع أكثر بروزًا */
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.9); /* ✅ ظل أقوى عند التحويم */
            border-color: rgba(255, 255, 255, 0.3); /* ✅ حدود أكثر وضوحًا */
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .login-container h2 {
            margin-bottom: 40px; /* ✅ مسافة سفلية أكبر */
            font-size: 3.5rem; /* ✅ عنوان أكبر وأكثر فخامة */
            color: #f0f0f0; /* لون أبيض ناعم */
            text-shadow: 4px 4px 10px rgba(0, 0, 0, 0.9); /* ✅ ظل نص أقوى وأكثر عمقًا */
            font-family: 'Amiri', serif;
            letter-spacing: 2px; /* ✅ تباعد أحرف أكبر */
            animation: textGlow 3s ease-in-out infinite alternate; /* ✅ توهج نص العنوان */
        }

        @keyframes textGlow {
            0% {
                text-shadow: 4px 4px 10px rgba(0, 0, 0, 0.9), 0 0 10px #00f0ff;
            }
            50% {
                text-shadow: 4px 4px 15px rgba(0, 0, 0, 0.9), 0 0 20px #ff00c8;
            }
            100% {
                text-shadow: 4px 4px 10px rgba(0, 0, 0, 0.9), 0 0 10px #00f0ff;
            }
        }

        /* Input Group Styling for Floating Labels */
        .form-floating {
            position: relative;
            margin-bottom: 35px; /* ✅ مسافة سفلية أكبر */
        }

        .form-floating > .form-control,
        .form-floating > .form-control-plaintext,
        .form-floating > .form-select {
            padding: 1.4rem 1.4rem; /* ✅ مساحة داخلية أكبر للحقول */
            background-color: rgba(255, 255, 255, 0.07); /* ✅ شفافية أعلى لخلفية الحقل */
            border: 1px solid rgba(255, 255, 255, 0.25); /* ✅ حدود أكثر دقة */
            color: #ffffff; /* ✅ نص أبيض نقي */
            border-radius: 12px; /* ✅ حواف أكثر استدارة */
            transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1); /* ✅ انتقال سلس */
            font-size: 1.3rem; /* ✅ حجم خط أكبر */
            height: auto;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7); /* ✅ ظل نص أقوى */
        }

        .form-floating > label {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            padding: 1.4rem 1.4rem; /* ✅ مطابقة مساحة الحقل */
            pointer-events: none;
            border: 1px solid transparent;
            transform-origin: 0 0;
            transition: opacity .4s ease-in-out, transform .4s ease-in-out, color 0.4s ease; /* ✅ انتقال سلس للـ label */
            color: rgba(255, 255, 255, 0.7); /* ✅ لون أولي أكثر وضوحًا للـ label */
            font-size: 1.3rem;
            font-weight: 600; /* ✅ أكثر سمكًا */
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8); /* ✅ ظل نص أقوى للـ label */
        }

        .form-floating > .form-control:focus,
        .form-floating > .form-control:not(:placeholder-shown) {
            background-color: rgba(255, 255, 255, 0.1); /* ✅ خلفية أكثر عتامة عند التركيز */
            border-color: #00f0ff; /* ✅ حدود سماوية متوهجة */
            box-shadow: 0 0 0 0.4rem rgba(0, 240, 255, 0.4); /* ✅ توهج سماوي أقوى */
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            transform: scale(0.75) translateY(-2.2rem) translateX(0.3rem); /* ✅ رفع أعلى للـ label */
            opacity: 1;
            color: #00f0ff; /* ✅ لون سماوي متوهج للـ label عند التركيز */
            font-weight: 800; /* ✅ أكثر سمكًا */
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.9), 0 0 10px #00f0ff; /* ✅ ظل نص متوهج */
        }

        /* Autofill styles to prevent unwanted background */
        .form-control:-webkit-autofill,
        .form-control:-webkit-autofill:hover,
        .form-control:-webkit-autofill:focus,
        .form-control:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0px 1000px rgba(255, 255, 255, 0.07) inset !important; /* ✅ خلفية تعبئة تلقائية شفافة */
            -webkit-text-fill-color: #fff !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        /* Checkbox Styling */
        .form-check-input {
            width: 1.4em; /* ✅ حجم أكبر */
            height: 1.4em;
            border-radius: 0.4em; /* ✅ حواف أكثر استدارة */
            background-color: rgba(255, 255, 255, 0.15); /* ✅ خلفية شفافة */
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.5s ease; /* ✅ انتقال سلس */
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4); /* ✅ ظل أقوى */
        }
        .form-check-input:checked {
            background-color: #00f0ff; /* ✅ لون سماوي متوهج عند التحديد */
            border-color: #00f0ff;
            box-shadow: 0 0 0 0.4rem rgba(0, 240, 255, 0.4); /* ✅ توهج سماوي أقوى */
        }
        .form-check-label {
            color: #f0f0f0;
            font-size: 1.2rem; /* ✅ حجم خط أكبر */
            margin-right: 1rem; /* ✅ مسافة أكبر */
            cursor: pointer;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6); /* ✅ ظل نص أقوى */
        }

        /* Button Styling */
        .btn-custom {
            /* ✅ تدرج ألوان متوهج مستوحى من الصورة */
            background: linear-gradient(45deg, #6a11cb, #2575fc, #ff00c8);
            background-size: 200% 200%; /* Larger background to allow animation */
            animation: buttonGradient 5s ease-in-out infinite alternate; /* Animated gradient */
            border: none;
            padding: 18px 40px; /* ✅ مساحة داخلية أكبر */
            border-radius: 15px; /* ✅ حواف أكثر استدارة */
            font-size: 1.5rem; /* ✅ حجم خط أكبر */
            font-weight: 900; /* ✅ سمك خط فائق */
            color: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6); /* ✅ ظل أقوى */
            transition: all 0.6s cubic-bezier(0.25, 0.8, 0.25, 1); /* ✅ انتقال سلس وفاخر */
            letter-spacing: 1.5px; /* ✅ تباعد أحرف أكبر */
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.8); /* ✅ ظل نص أقوى */
        }

        @keyframes buttonGradient {
            0% {
                background-position: 0% 50%;
            }
            100% {
                background-position: 100% 50%;
            }
        }

        .btn-custom:hover {
            background: linear-gradient(45deg, #8a2be2, #4f46e5, #ff69b4); /* ✅ تدرج ألوان مختلف عند التحويم */
            background-size: 200% 200%;
            animation: buttonGradientHover 3s ease-in-out infinite alternate; /* ✅ حركة تدرج مختلفة عند التحويم */
            transform: translateY(-10px) scale(1.08); /* ✅ رفع وتكبير أكثر بروزًا */
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.9); /* ✅ ظل أقوى بكثير */
        }

        @keyframes buttonGradientHover {
            0% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .btn-custom:active {
            transform: translateY(-3px); /* ✅ ضغط خفيف عند النقر */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.7);
        }

        /* Button glow effect on hover */
        .btn-custom::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.5); /* ✅ توهج أبيض أكثر سطوعًا */
            border-radius: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
            transition: width 0.8s ease, height 0.8s ease, opacity 0.8s ease; /* ✅ توهج أبطأ وأكثر انتشارًا */
        }

        .btn-custom:hover::before {
            width: 300%; /* ✅ توهج أكبر */
            height: 300%;
            opacity: 1;
        }

        /* Error Messages */
        .invalid-feedback {
            color: #ffaaaa; /* ✅ لون أحمر أفتح وأكثر نعومة */
            font-size: 1rem; /* ✅ حجم خط أكبر */
            text-align: right;
            margin-top: 10px; /* ✅ مسافة أكبر */
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6); /* ✅ ظل نص أقوى */
        }

        /* Session Status */
        .session-status {
            background-color: rgba(255, 255, 255, 0.2); /* ✅ أكثر عتامة قليلاً */
            border: 1px solid rgba(255, 255, 255, 0.4); /* ✅ حدود أكثر وضوحًا */
            color: #fff;
            padding: 15px; /* ✅ مساحة داخلية أكبر */
            border-radius: 12px; /* ✅ حواف أكثر استدارة */
            margin-bottom: 30px; /* ✅ مسافة أكبر */
            font-size: 1.1rem; /* ✅ حجم خط أكبر */
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6); /* ✅ ظل نص أقوى */
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .login-container {
                padding: 30px 20px;
                max-width: 95%;
            }
            .login-container h2 {
                font-size: 2.8rem;
            }
            .btn-custom {
                font-size: 1.2rem;
                padding: 15px 30px;
            }
            .form-floating > .form-control,
            .form-floating > label {
                font-size: 1.1rem;
                padding: 1.2rem 1.2rem;
            }
            .form-floating > .form-control:focus ~ label,
            .form-floating > .form-control:not(:placeholder-shown) ~ label {
                transform: scale(0.75) translateY(-1.8rem) translateX(0.2rem);
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>تسجيل الدخول</h2>

        <!-- Session Status -->
        @if (session('status'))
            <div class="session-status">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-floating mb-4">
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder=" "> {{-- Placeholder is important for floating labels --}}
                <label for="email">البريد الإلكتروني</label>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-floating mb-4">
                <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password" placeholder=" "> {{-- Placeholder is important for floating labels --}}
                <label for="password">كلمة المرور</label>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="form-check text-end mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label" for="remember_me">
                    تذكرني
                </label>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-custom">
                    تسجيل الدخول
                </button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS Bundle (Popper included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to check if an input has content
            function checkInput(input) {
                if (input.value.length > 0) {
                    input.classList.add('has-content');
                } else {
                    input.classList.remove('has-content');
                }
            }

            // Apply initial check for inputs that might have old values (e.g., after validation error)
            document.querySelectorAll('.form-floating .form-control').forEach(function(input) {
                checkInput(input); // Check on page load
                input.addEventListener('input', function() {
                    checkInput(this); // Check on input change
                });
            });

            // Handle focus/blur for floating labels (Bootstrap's default behavior)
            // This script is mostly for ensuring compatibility and handling initial states.
            // Bootstrap's form-floating handles the core animation.

            // Optional: Add more dynamic effects here if needed, e.g., for particles or more complex animations
        });
    </script>
</body>
</html>
