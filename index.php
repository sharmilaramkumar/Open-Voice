<?php
session_start();

// Redirect to dashboard if user is already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Voice - Women’s Cell Suggestion Box</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #7c3aed, #d8b4fe, #f9a8d4, #ffffff); /* Purple to lavender to pink to white */
            background-size: 400% 400%;
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            animation: voiceWave 18s ease infinite;
        }

        @keyframes voiceWave {
            0% { background-position: 0% 0%; filter: brightness(100%); }
            50% { background-position: 100% 100%; filter: brightness(125%); }
            100% { background-position: 0% 0%; filter: brightness(100%); }
        }

        body::before {
            content: '';
            position: absolute;
            top: -60%;
            left: -60%;
            width: 220%;
            height: 220%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.25), rgba(0, 0, 0, 0.4));
            animation: rippleEcho 14s ease infinite;
            z-index: -1;
        }

        @keyframes rippleEcho {
            0% { transform: rotate(0deg) scale(1); opacity: 0.6; }
            50% { transform: rotate(180deg) scale(1.15); opacity: 1; }
            100% { transform: rotate(360deg) scale(1); opacity: 0.6; }
        }

        /* Decorative Elements for Purpose */
        .voice-icon {
            position: absolute;
            width: 80px;
            height: 80px;
            background: url('https://img.icons8.com/ios-filled/50/ffffff/voice.png') no-repeat center;
            background-size: contain;
            animation: floatSpeak 10s ease infinite;
            z-index: 0;
        }

        .voice-icon:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
        .voice-icon:nth-child(2) { bottom: 15%; right: 15%; animation-delay: 2s; }
        .voice-icon:nth-child(3) { top: 50%; left: 20%; animation-delay: 4s; }

        @keyframes floatSpeak {
            0% { transform: translate(0, 0) scale(1); opacity: 0.5; }
            50% { transform: translate(20px, -20px) scale(1.2); opacity: 1; }
            100% { transform: translate(0, 0) scale(1); opacity: 0.5; }
        }

        /* Container with Compact Grid */
        .landing-container {
            max-width: 1000px;
            padding: 60px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            position: relative;
            z-index: 1;
            opacity: 0;
            transform: perspective(1200px) rotateX(-20deg) scale(0.9);
            animation: empowerLift 1.6s ease-out forwards;
        }

        @keyframes empowerLift {
            0% { opacity: 0; transform: perspective(1200px) rotateX(-20deg) scale(0.9); }
            70% { opacity: 1; transform: perspective(1200px) rotateX(5deg) scale(1.05); }
            100% { opacity: 1; transform: perspective(1200px) rotateX(0deg) scale(1); }
        }

        /* Left Column: Intro */
        .intro {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 25px;
            background: rgba(216, 180, 254, 0.1); /* Light lavender tint */
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .intro::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(216, 180, 254, 0.2), transparent);
            animation: pulseHeart 10s ease infinite;
            z-index: -1;
        }

        @keyframes pulseHeart {
            0% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.8; }
            100% { transform: scale(1); opacity: 0.5; }
        }

        /* Logo */
        .logo {
            width: 120px;
            height: 120px;
            margin-bottom: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d8b4fe, #7c3aed); /* Lavender to purple */
            box-shadow: 0 10px 25px rgba(216, 180, 254, 0.6);
            opacity: 0;
            animation: voiceBloom 1.4s ease forwards 0.2s;
            overflow: hidden;
            position: relative;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .logo:hover {
            transform: scale(1.15) rotate(15deg);
            box-shadow: 0 15px 35px rgba(216, 180, 254, 0.8);
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        @keyframes voiceBloom {
            0% { opacity: 0; transform: scale(0.4) rotate(-360deg); }
            60% { opacity: 1; transform: scale(1.2) rotate(10deg); }
            100% { opacity: 1; transform: scale(1) rotate(0deg); }
        }

        /* Welcome Message */
        h1 {
            font-size: 2.5rem;
            color: #7c3aed; /* Vibrant purple */
            font-weight: 800;
            margin-bottom: 15px;
            text-shadow: 0 0 10px rgba(124, 58, 237, 0.5);
            opacity: 0;
            animation: speakOut 1.2s ease forwards 0.4s;
        }

        p {
            font-size: 1.2rem;
            color: #4c1d95; /* Deep purple */
            line-height: 1.6;
            margin-bottom: 30px;
            opacity: 0;
            animation: echoRise 1.2s ease forwards 0.6s;
        }

        @keyframes speakOut {
            0% { opacity: 0; transform: translateY(-50px) scale(0.6); }
            60% { opacity: 1; transform: translateY(10px) scale(1.15); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes echoRise {
            0% { opacity: 0; transform: translateY(30px) scale(0.9); }
            50% { opacity: 0.7; transform: translateY(-10px) scale(1.05); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Buttons */
        .buttons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .login-button, .register-button {
            padding: 14px 40px;
            font-size: 1.1rem;
            font-weight: 700;
            color: #ffffff;
            border: none;
            border-radius: 35px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            opacity: 0;
        }

        .login-button {
            background: linear-gradient(135deg, #d8b4fe, #7c3aed); /* Lavender to purple */
            box-shadow: 0 6px 20px rgba(216, 180, 254, 0.6);
            animation: liftBtn 1.4s ease forwards 0.8s;
        }

        .register-button {
            background: linear-gradient(135deg, #f9a8d4, #db2777); /* Pink to deep pink */
            box-shadow: 0 6px 20px rgba(249, 168, 212, 0.6);
            animation: liftBtn 1.4s ease forwards 1s;
        }

        .login-button:hover, .register-button:hover {
            transform: scale(1.15) translateY(-6px);
            box-shadow: 0 12px 30px rgba(216, 180, 254, 0.8);
        }

        .login-button::before, .register-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            transition: left 0.7s ease;
        }

        .login-button:hover::before, .register-button:hover::before {
            left: 100%;
        }

        .register-button p {
            font-size: 0.8rem;
            margin: 0;
            opacity: 0.9;
            animation: none;
        }

        @keyframes liftBtn {
            0% { opacity: 0; transform: translateY(40px) rotate(-10deg); }
            60% { opacity: 1; transform: translateY(-10px) rotate(5deg); }
            100% { opacity: 1; transform: translateY(0) rotate(0deg); }
        }

        /* Right Column: Features */
        .features {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 30px;
            background: rgba(249, 168, 212, 0.08); /* Light pink tint */
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .features::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(249, 168, 212, 0.15), transparent);
            animation: strengthPulse 9s ease infinite;
            z-index: -1;
        }

        @keyframes strengthPulse {
            0% { transform: scale(1); opacity: 0.4; }
            50% { transform: scale(1.15); opacity: 0.9; }
            100% { transform: scale(1); opacity: 0.4; }
        }

        .features h2 {
            font-size: 1.8rem;
            color: #7c3aed; /* Vibrant purple */
            font-weight: 800;
            margin-bottom: 25px;
            text-shadow: 0 0 10px rgba(124, 58, 237, 0.5);
            opacity: 0;
            animation: empowerGlow 1.2s ease forwards 1.2s;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            font-size: 1rem;
            color: #4c1d95; /* Deep purple */
        }

        .feature-list li {
            margin: 15px 0;
            padding: 14px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            opacity: 0;
            animation: voiceRipple 1s ease forwards;
            animation-delay: calc(0.25s * var(--i));
            position: relative;
            overflow: hidden;
        }

        .feature-list li:nth-child(1) { --i: 1; }
        .feature-list li:nth-child(2) { --i: 2; }
        .feature-list li:nth-child(3) { --i: 3; }
        .feature-list li:nth-child(4) { --i: 4; }

        .feature-list li:hover {
            transform: scale(1.08) translateX(10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .feature-list li::before {
            content: '✨';
            position: absolute;
            left: -30px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
            opacity: 0;
            transition: opacity 0.3s ease, left 0.3s ease;
        }

        .feature-list li:hover::before {
            opacity: 1;
            left: -20px;
        }

        @keyframes empowerGlow {
            0% { opacity: 0; transform: scale(0.7); }
            50% { opacity: 0.9; transform: scale(1.15); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes voiceRipple {
            0% { opacity: 0; transform: translateX(-40px) rotateY(-90deg); }
            60% { opacity: 0.8; transform: translateX(5px) rotateY(10deg); }
            100% { opacity: 1; transform: translateX(0) rotateY(0deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .landing-container {
                grid-template-columns: 1fr;
                max-width: 90%;
                padding: 40px;
            }
            .intro, .features { padding: 20px; }
            .logo { width: 100px; height: 100px; }
            h1 { font-size: 2rem; }
            p { font-size: 1.1rem; }
            .login-button, .register-button { padding: 12px 35px; font-size: 1rem; }
            .features h2 { font-size: 1.6rem; }
            .feature-list li { font-size: 0.95rem; }
            .voice-icon { width: 60px; height: 60px; }
        }

        @media (max-width: 480px) {
            .landing-container {
                padding: 30px;
            }
            .intro, .features { padding: 15px; }
            .logo { width: 80px; height: 80px; }
            h1 { font-size: 1.8rem; }
            p { font-size: 1rem; }
            .login-button, .register-button { padding: 10px 30px; font-size: 0.9rem; }
            .features h2 { font-size: 1.4rem; }
            .feature-list li { font-size: 0.85rem; }
            .voice-icon { width: 50px; height: 50px; }
        }
    </style>
</head>
<body>
    <!-- Decorative Voice Icons -->
    <div class="voice-icon"></div>
    <div class="voice-icon"></div>
    <div class="voice-icon"></div>

    <div class="landing-container">
        <!-- Left Column: Intro -->
        <div class="intro">
            <div class="logo"><img src="logo.png" alt="Open Voice Logo"></div>
            <h1>Open Voice</h1>
            <p>Your anonymous platform to empower women’s voices in college communities.</p>
            <div class="buttons">
                <button class="login-button" onclick="window.location.href='login.php'">Share Your Voice</button>
                <button class="register-button" onclick="window.location.href='register.php'">Lead the Change <p>(Admin Access)</p></button>
            </div>
        </div>

        <!-- Right Column: Features -->
        <div class="features">
            <h2>Why Open Voice?</h2>
            <ul class="feature-list">
                <li>Safe & Anonymous Sharing</li>
                <li>Track Your Suggestions</li>
                <li>Community-Driven Support</li>
                <li>Actionable Insights</li>
            </ul>
        </div>
    </div>
</body>
</html>
