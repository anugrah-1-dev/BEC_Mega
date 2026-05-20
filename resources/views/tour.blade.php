<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VirtualAR</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Model Viewer for GLB 3D objects -->
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <style>
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family: 'Outfit', sans-serif;
        }
        #pano {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* Onboarding Styles */
        #audio-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
            display: flex; /* ACTIVE - shows the onboarding overlay */
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .bg-slider-track {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: #000;
            overflow: hidden;
        }
        .bg-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: auto 100vh; /* Scale to viewport height, keep aspect ratio */
            background-position: 0 center;
            background-repeat: repeat-x; /* Allow the 360 photo to loop horizontally */
            animation: pan360 40s linear infinite; /* Infinite continuous loop */
        }
        .bg-slide.overlay {
            display: none;
        }
        @keyframes pan360 {
            0% { background-position: 0 center; }
            100% { background-position: -200vh center; } /* 200vh exactly matches a 2:1 equirectangular 360 image width */
        }

        .modal-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 24px;
            text-align: center;
            max-width: 500px; 
            width: 90%;
            box-shadow: 0 20px 45px rgba(0,0,0,0.3);
            border: 1px solid rgba(255, 255, 255, 0.5);
            animation: modalFadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.9) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes blinkText {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .modal-box h2 {
            margin-top: 0;
            font-weight: 600;
            font-size: 28px;
            color: #2D2D2D;
            margin-bottom: 20px;
        }
        .modal-box h2.welcome-title {
            background: linear-gradient(90deg, #007bff 0%, #FFD700 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
            animation: blinkText 1.5s ease-in-out infinite;
            font-size: 32px;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .btn-start, .btn-allow, .btn-back {
            background: #007bff;
            color: white;
            padding: 12px 40px;
            font-size: 18px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 10px;
            border: none;
            cursor: pointer;
        }

        .btn-deny {
            background: #6c757d;
            color: white;
            padding: 12px 40px;
            font-size: 18px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 10px;
            border: none;
            cursor: pointer;
        }

        .spinner {
            width: 80px;
            height: 80px;
            border: 8px solid rgba(0, 123, 255, 0.1);
            border-left-color: #007bff;
            border-radius: 50%;
            margin: 30px auto;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-text {
            color: #555;
            font-size: 18px;
            font-weight: 500;
        }

        #modal-entering {
            background: #000;
            width: 100vw;
            height: 100vh;
            max-width: none;
            border-radius: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .entering-text {
            color: #fff;
            font-size: 48px;
            font-weight: 800;
            letter-spacing: 12px;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
            margin-bottom: 20px;
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from { text-shadow: 0 0 10px rgba(255, 255, 255, 0.4); }
            to { text-shadow: 0 0 30px rgba(255, 255, 255, 0.8), 0 0 40px rgba(255, 255, 255, 0.4); }
        }

        .btn-group {
            display: flex;
            gap: 35px; /* Increased gap as requested */
            justify-content: center;
        }

        .entering-dots {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .dot {
            width: 12px;
            height: 12px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            animation: dotWave 1.4s infinite ease-in-out both;
        }
        .dot:nth-child(1) { animation-delay: -0.32s; }
        .dot:nth-child(2) { animation-delay: -0.16s; }
        
        @keyframes dotWave {
            0%, 80%, 100% { transform: scale(0.6); opacity: 0.3; }
            40% { transform: scale(1); opacity: 1; }
        }

        #modal-video {
            background: #000;
            width: 100vw;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1100;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #drone-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-group {
            display: flex;
            gap: 35px;
            justify-content: center;
        }

        .btn-yes, .btn-no {
            padding: 16px 55px; /* Slightly larger as requested */
            font-size: 20px;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        .btn-yes:hover { transform: scale(1.05); }
        .btn-no:hover { transform: scale(1.05); }

        .btn-yes { background: #007BFF; color: white; }
        .btn-no { background: #5A5A5A; color: white; }

        /* Main Tour UI Components */
        #home-btn {
            position: fixed;
            top: 25px;
            left: 25px;
            z-index: 800;
            background: rgba(0,0,0,0.6);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
            transition: all 0.3s;
        }
        #home-btn:hover { background: rgba(0,0,0,0.8); transform: scale(1.1); }
        #home-btn svg { width: 24px; height: 24px; fill: white; } 


        #sidebar-toggle {
            position: fixed;
            top: 25px;
            right: 25px;
            z-index: 800;
            background: rgba(0,0,0,0.6);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
        }

        #sidebar-rooms {
            position: fixed;
            top: 0;
            right: -350px;
            width: 320px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            z-index: 1500;
            box-shadow: -5px 0 25px rgba(0,0,0,0.2);
            transition: right 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
        }
        #sidebar-rooms.active { right: 0; }

        .sidebar-header {
            padding: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .sidebar-header h3 {
            margin: 0;
            font-size: 22px;
            color: #003366;
            font-weight: 700;
        }
        .btn-close-sidebar {
            background: #f1f1f1;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
        }

        .room-list {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
        }
        .room-item {
            padding: 18px 25px;
            margin-bottom: 10px;
            background: white;
            border: 1px solid #eee;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            color: #444;
            transition: all 0.2s;
        }
        .room-item:hover { background: #f8f9fa; border-color: #ddd; }
        .room-item.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        }

        /* Sidebar Footer Buttons */
        .sidebar-footer {
            padding: 20px;
            background: #000;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .btn-sidebar-opt {
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-sidebar-opt svg { width: 18px; height: 18px; }
        .btn-sidebar-close {
            background: white;
            color: #003399;
        }
        .btn-sidebar-home {
            background: #003399;
            color: white;
        }
        .btn-sidebar-opt:hover { opacity: 0.9; transform: translateY(-2px); }

        #room-info-card {
            position: fixed;
            bottom: 165px; /* Normalized bottom position */
            left: 25px;
            z-index: 800;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            min-width: 280px;
        }
        #room-info-card h4 {
            margin: 0 0 8px 0;
            font-size: 24px;
            color: #003366;
            font-weight: 700;
        }
        #room-info-card p {
            margin: 0;
            font-size: 15px;
            color: #555;
            font-weight: 400;
        }

        #footer-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 110px; /* Normalized height */
            background: white;
            z-index: 900;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr; /* Logo | Center Button | Right Button */
            align-items: center;
            padding: 0 40px 0 100px;
            box-shadow: 0 -5px 25px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }
        .footer-logo {
            display: flex;
            justify-content: flex-start;
        }
        .footer-logo img {
            height: 95px; /* Normalized BEC logo size */
            width: auto;
        }
        .footer-center {
            display: flex;
            justify-content: center;
        }
        .footer-right {
            display: flex;
            justify-content: flex-end;
        }
        .btn-footer {
            padding: 18px 30px; /* Reduced padding for normalized footer */
            border-radius: 8px;
            border: none;
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            color: white;
            background: #003399;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0, 51, 153, 0.2);
        }
        .btn-footer:hover { transform: translateY(-3px); background: #002d8a; box-shadow: 0 8px 25px rgba(0, 51, 153, 0.3); }
        .btn-footer i { font-size: 20px; }
        .btn-footer.primary { background: #0044cc; } /* Slightly different shade for primary action if needed */

        /* ===== MOBILE RESPONSIVE FOOTER ===== */
        @media (max-width: 768px) {
            #footer-bar {
                height: auto;
                padding: 10px 12px;
                grid-template-columns: auto 1fr 1fr;
                gap: 8px;
            }
            .footer-logo img {
                height: 48px;
            }
            .footer-center, .footer-right {
                justify-content: center;
            }
            .btn-footer {
                padding: 10px 12px;
                font-size: 11px;
                gap: 6px;
                border-radius: 6px;
                white-space: nowrap;
            }
            .btn-footer svg {
                width: 16px !important;
                height: 16px !important;
                flex-shrink: 0;
            }
            #room-info-card {
                bottom: 120px;
                left: 12px;
                padding: 12px 16px;
                min-width: 0;
                max-width: calc(100vw - 24px);
                border-radius: 10px;
            }
            #room-info-card h4 {
                font-size: 16px;
                margin-bottom: 4px;
            }
            #room-info-card p {
                font-size: 12px;
                max-width: 100% !important;
            }
        }
        @media (max-width: 480px) {
            #footer-bar {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: auto auto;
                padding: 8px 10px;
                gap: 6px;
            }
            .footer-logo {
                grid-column: 1 / -1;
                justify-content: center;
            }
            .footer-center, .footer-right {
                justify-content: center;
            }
            .btn-footer {
                padding: 9px 10px;
                font-size: 10px;
                gap: 5px;
                width: 100%;
                justify-content: center;
            }
            #room-info-card {
                bottom: 140px;
                padding: 10px 14px;
            }
            #room-info-card h4 {
                font-size: 14px;
            }
            #room-info-card p {
                font-size: 11px;
            }
        }

        /* Hotspots Styling */
        .hotspot {
            cursor: pointer;
            z-index: 10;
        }
        .hotspot-icon {
            width: 50px;
            height: 50px;
            background: #007bff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 51, 153, 0.4);
            transition: all 0.3s ease;
            border: 3px solid white;
        }
        .hotspot:hover .hotspot-icon {
            transform: scale(1.2);
            background: #0056b3;
        }
        .hotspot-label {
            position: absolute;
            bottom: -35px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }
        .hotspot:hover .hotspot-label {
            opacity: 1;
        }

        /* ===== 3D Model Hotspot (Ruang 2) ===== */
        .hotspot-3d-wrapper {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            z-index: 20;
        }
        .hotspot-3d-label-top {
            background: linear-gradient(135deg, #003399 0%, #0055cc 100%);
            color: white;
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            font-weight: 700;
            padding: 8px 20px;
            border-radius: 20px;
            margin-bottom: 8px;
            white-space: nowrap;
            box-shadow: 0 4px 14px rgba(0,51,153,0.5);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            animation: floatLabel 2.5s ease-in-out infinite;
        }
        @keyframes floatLabel {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .model-viewer-hotspot {
            width: 1000px;
            height: 1000px;
            border-radius: 20px;
            overflow: visible;
            transition: transform 0.3s ease;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        .model-viewer-hotspot:hover {
            transform: scale(1.02);
        }
        model-viewer {
            width: 100%;
            height: 100%;
            background: transparent;
            --poster-color: transparent;
        }

        /* ===== Hide video duration for second video ===== */
        #tutorial-video-el.hide-duration::-webkit-media-controls-time-remaining-display,
        #tutorial-video-el.hide-duration::-webkit-media-controls-current-time-display,
        #tutorial-video-el.hide-duration::-webkit-media-controls-timeline,
        #tutorial-video-el.hide-duration::-webkit-media-controls-volume-slider,
        #tutorial-video-el.hide-duration::-webkit-media-controls-mute-button {
            display: none !important;
        }
        #tutorial-video-el.hide-duration::-webkit-media-controls-panel {
            justify-content: center;
            align-items: center;
            height: 60px;
            position: relative;
            bottom: 0;
        }
        #tutorial-video-el.hide-duration::-webkit-media-controls-play-button,
        #tutorial-video-el.hide-duration::-webkit-media-controls-fullscreen-button {
            display: flex !important;
            position: relative;
            margin: 0 15px;
        }
        /* Memindahkan kontrol ke bawah */
        #tutorial-video-el.hide-duration::-webkit-media-controls {
            display: flex !important;
            justify-content: center;
            align-items: flex-end;
            padding-bottom: 20px;
        }

        /* ===== Step Navigation ===== */
        .step-nav-overlay {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .step-nav-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 15px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .step-nav-btn:hover {
            background: #0056b3;
        }

        /* ===== Tutorial Video Modal ===== */
        #tutorial-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        #tutorial-modal-overlay.active {
            display: flex;
        }
        #tutorial-modal-box {
            background: #fff;
            border-radius: 20px;
            width: 95%;
            max-width: 1300px;
            height: 85vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 40px 100px rgba(0,0,0,0.5);
            animation: tModalIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .tmodal-main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }
        .tmodal-video-area {
            flex: 1.8;
            display: flex;
            flex-direction: column;
            background: #000;
            position: relative;
        }
        .tmodal-comment-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
            border-left: 1px solid #e2e8f0;
        }
        .tmodal-main-content {
            display: flex;
            flex: 1;
            overflow: hidden;
            background: #fff;
        }
        .tmodal-video-side {
            flex: 1.6;
            display: flex;
            flex-direction: column;
            background: #000;
            position: relative;
        }
        .tmodal-comment-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            border-left: 1px solid #eee;
            background: #f8fafc;
        }
        @keyframes tModalIn {
            from { opacity: 0; transform: scale(0.9) translateY(20px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .tmodal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 22px 14px;
            border-bottom: 1px solid #eee;
        }
        .tmodal-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #1a1a2e;
        }
        .tmodal-close-x {
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            font-size: 22px;
            line-height: 1;
            padding: 2px 6px;
            border-radius: 6px;
            transition: background 0.2s;
        }
        .tmodal-close-x:hover { background: #f0f0f0; }
        .tmodal-video-wrap {
            position: relative;
            background: #000;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #tutorial-video-el {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }
        #tmodal-play-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            transition: all 0.3s ease;
        }

        /* ===== Registration Modal Styles ===== */
        #registration-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(12px);
            z-index: 3000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        #registration-modal-overlay.active { display: flex; }

        #registration-modal-box {
            background: #fff;
            border-radius: 28px;
            width: 100%;
            max-width: 680px;
            position: relative;
            box-shadow: 0 35px 80px rgba(0,0,0,0.5);
            overflow: hidden;
            animation: modalPopIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes modalPopIn {
            from { opacity: 0; transform: scale(0.9) translateY(40px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        .reg-modal-close {
            position: absolute;
            top: 25px;
            right: 25px;
            width: 40px;
            height: 40px;
            background: #f4f4f9;
            border: none;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #444;
            transition: all 0.2s;
            z-index: 10;
        }
        .reg-modal-close:hover { background: #e0e0ed; transform: rotate(90deg); }

        .reg-modal-content {
            padding: 50px 40px;
            text-align: center;
        }

        .reg-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 30px;
        }
        .reg-brand img { height: 60px; }
        .reg-brand .brand-text {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }

        .reg-title {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 12px 0;
        }
        .reg-subtitle {
            font-size: 16px;
            color: #64748b;
            max-width: 480px;
            margin: 0 auto 40px auto;
            line-height: 1.6;
        }

        .reg-options-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 35px;
        }

        .reg-option-card {
            background: #f8fafc;
            border: 2px solid #f1f5f9;
            padding: 30px 20px;
            border-radius: 20px;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .reg-option-card:hover {
            border-color: #6366f1;
            background: #fff;
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(99, 102, 241, 0.1);
        }

        .reg-option-icon {
            width: 64px;
            height: 64px;
            background: #e0e7ff;
            color: #6366f1;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .reg-option-icon svg { width: 32px; height: 32px; }
        .reg-option-icon.secondary { background: #fef08a; color: #a16207; }

        .reg-option-card h3 {
            font-size: 20px;
            font-weight: 700;
            color: #334155;
            margin: 0 0 10px 0;
        }
        .reg-option-card p {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .btn-reg-action {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
        }
        .btn-reg-action.primary {
            background: #003399; /* Navy matching original BEC style in screenshots */
            color: #fff;
            box-shadow: 0 10px 20px rgba(0, 51, 153, 0.25);
        }
        .btn-reg-action.primary:hover {
            background: #002d8a;
            transform: scale(1.02);
        }
        .btn-reg-action.outline {
            border: 2px solid #003399;
            color: #003399;
            background: transparent;
        }
        .btn-reg-action.outline:hover {
            background: #f0f7ff;
        }

        .reg-footer-links {
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }
        .reg-footer-links p {
            font-size: 14px;
            color: #64748b;
        }
        .reg-footer-links a {
            color: #6366f1;
            font-weight: 700;
            text-decoration: none;
        }
        .reg-footer-links a:hover { text-decoration: underline; }

        @media (max-width: 600px) {
            .reg-options-grid { grid-template-columns: 1fr; }
            .reg-modal-content { padding: 40px 20px; }
        }
        #tmodal-play-overlay:hover {
            filter: brightness(1.1);
        }
        #tmodal-play-overlay[data-speaking="true"] {
            cursor: wait;
        }
        .tmodal-progress-bar {
            height: 4px;
            background: #222;
            position: relative;
            flex-shrink: 0;
        }
        #tmodal-progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #007bff, #00c6ff);
            border-radius: 2px;
            transition: width 0.4s linear;
        }
        .tmodal-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 22px;
            border-top: 1px solid #f0f0f0;
            flex-shrink: 0;
            flex-wrap: wrap;
            gap: 10px;
        }
        .tmodal-stats {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .tmodal-stat {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #555;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            user-select: none;
            transition: color 0.2s;
        }
        .tmodal-stat:hover { color: #007bff; }
        .tmodal-stat.liked { color: #e53935; }
        .tmodal-stat svg { width: 20px; height: 20px; }
        .tmodal-stat-comment { cursor: pointer; }
        #btn-close-tutorial {
            background: #1a1a2e;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 11px 22px;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }
        #btn-close-tutorial:hover { background: #007bff; transform: translateY(-2px); }
        /* Comment Section */
        #tmodal-comment-section {
            display: flex !important; /* Always show in 2-column layout */
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }
        #tmodal-comment-section.open { display: flex; }
        #tmodal-comment-list {
            overflow-y: auto;
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            background: transparent;
        }
        .tcomment-item {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            animation: commentFade 0.3s ease;
        }
        @keyframes commentFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .tcomment-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e1e4e8;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #555;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
            border: 1px solid #ddd;
        }
        .tcomment-avatar.admin-av {
            background: #1a1a2e;
            color: white;
        }
        .tcomment-body { 
            flex: 1; 
            background: white; 
            padding: 16px 20px; 
            border-radius: 18px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9;
        }
        .tcomment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        .tcomment-name {
            font-weight: 800;
            font-size: 15px;
            color: #1e293b;
        }
        .tcomment-text {
            margin-top: 6px;
            font-size: 15px;
            color: #334155;
            line-height: 1.5;
            word-break: break-word;
            white-space: pre-wrap;
        }
        .tcomment-admin-badge {
            background: #4f46e5;
            color: white;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 99px;
            margin-left: 8px;
            font-weight: 700;
            text-transform: uppercase;
        }
        }
        .tcomment-time {
            font-size: 12px;
            color: #999;
        }
        .tcomment-admin-badge {
            background: #007bff;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 6px;
            text-transform: uppercase;
        }
        .tcomment-text {
            font-size: 14px;
            color: #4A4A4A;
            line-height: 1.5;
        }
        .tmodal-comment-input {
            display: flex;
            gap: 12px;
            padding: 15px 22px;
            background: white;
            border-top: 1px solid #eee;
        }

        /* ===== Registration Modal ===== */
        /* ===== Registration / Database Modal ===== */
        #registration-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            z-index: 2500;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
        #registration-modal-overlay.active { display: flex; }
        .registration-box {
            background: #fff;
            border-radius: 24px;
            width: 95vw;
            max-width: 1400px;
            position: relative;
            height: 95vh;
            max-height: 95vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 40px 100px rgba(0,0,0,0.6);
            animation: rModalIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes rModalIn {
            from { opacity: 0; transform: scale(0.95) translateY(30px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* Register Modal CSS */
        .register-card {
            background: white; border-radius: 24px;
            width: 100%; max-width: 560px;
            padding: 48px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
            position: relative;
        }
        .register-close-x {
            background: none; border: none; cursor: pointer; color: #666; font-size: 22px; line-height: 1; padding: 2px 6px; border-radius: 6px; position: absolute; right: 20px; top: 20px; transition: 0.2s;
        }
        .register-close-x:hover { background: #f0f0f0; }

        .card-logo { display:flex; align-items:center; gap:14px; margin-bottom:32px; }
        .card-logo img { height:48px; width:auto; }
        .card-logo .bec-name { font-size:0.7rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; color:#64748b; }
        .card-header { margin-bottom:32px; text-align: left; }
        .card-header h1 { font-size:1.6rem; font-weight:900; color:#0f172a; margin:0;}
        .card-header p  { font-size:0.9rem; color:#64748b; margin-top:8px; line-height:1.6; }
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:0 20px; }
        .form-group { margin-bottom:20px; }
        .form-group.full { grid-column:span 2; }
        .form-label { display:block; font-size:0.78rem; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.07em; margin-bottom:8px; text-align: left; }
        .form-control { width:100%; padding:13px 16px; border:1.5px solid #e2e8f0; border-radius:10px; font-family:'Inter',sans-serif; font-size:0.9rem; color:#0f172a; background:#f8fafc; transition:all 0.2s; box-sizing: border-box; }
        .form-control:focus { outline:none; border-color:#4f46e5; background:white; box-shadow:0 0 0 4px rgba(79,70,229,0.12); }
        .form-error { font-size:0.78rem; color:#ef4444; margin-top:5px; text-align: left;}
        .btn-submit { width:100%; padding:15px; background:linear-gradient(135deg,#4f46e5,#7c3aed); color:white; border:none; border-radius:10px; font-family:'Inter',sans-serif; font-size:1rem; font-weight:800; cursor:pointer; transition:all 0.3s; box-shadow:0 8px 20px rgba(79,70,229,0.35); }
        .btn-submit:hover { transform:translateY(-2px); }
        .form-footer { text-align:center; margin-top:24px; font-size:0.875rem; color:#64748b; }
        .form-footer a { color:#4f46e5; font-weight:700; text-decoration:none; }
        .step-pills { display:flex; gap:8px; margin-bottom:32px; flex-wrap:wrap; }
        .pill { padding:6px 14px; border-radius:99px; font-size:0.72rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; }
        .pill-1 { background:rgba(79,70,229,0.1); color:#4f46e5; }
        .pill-2 { background:#f1f5f9; color:#94a3b8; }

        /* Dashboard Sidebar */
        .db-sidebar {
            width: 260px;
            background: #1a1a2e;
            color: white;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255,255,255,0.1);
        }
        .db-sidebar-header {
            padding: 30px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .db-sidebar-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .db-menu {
            flex: 1;
            padding: 20px 15px;
            overflow-y: auto;
        }
        .db-menu-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 20px;
            border-radius: 12px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
            transition: all 0.2s;
            cursor: pointer;
        }
        .db-menu-item:hover {
            background: rgba(255,255,255,0.05);
            color: white;
        }
        .db-menu-item.active {
            background: #007bff;
            color: white;
            box-shadow: 0 4px 15px rgba(0,123,255,0.3);
        }
        .db-menu-item svg {
            width: 20px;
            height: 20px;
            opacity: 0.9;
        }

        /* Dashboard Content */
        .db-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f8faff;
            overflow: hidden;
        }
        .db-header {
            padding: 20px 40px;
            background: white;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .db-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }

        .db-body {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
            background: #f8fafc;
        }

        /* Premium Registration & Portal CSS */
        .registration-box {
            background: #ffffff !important;
            border-radius: 30px !important;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15) !important;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #registration-modal-overlay.active #registration-modal-box {
            transform: scale(1);
            opacity: 1;
        }

        /* Expanded Mode for Dashboards */
        .registration-box.expanded {
            max-width: 1400px !important;
            width: 95vw !important;
            height: 95vh !important;
            max-height: 95vh !important;
        }

        /* Direct Registration Mode - Form only, no sidebar/dashboard */
        .registration-box.direct-reg {
            max-width: 680px !important;
            width: 95vw !important;
            height: auto !important;
            max-height: 90vh !important;
        }
        .registration-box.direct-reg .db-sidebar { display: none !important; }
        .registration-box.direct-reg .header-welcome { display: none !important; }
        .registration-box.direct-reg .db-header {
            justify-content: space-between !important;
        }
        .registration-box.direct-reg .db-header::before {
            content: 'Formulir Pendaftaran BEC';
            font-size: 1rem;
            font-weight: 700;
            color: white;
        }
        .registration-box.direct-reg #portal-interface {
            height: auto !important;
            flex-direction: column !important;
        }
        .registration-box.direct-reg .db-content {
            overflow-y: auto !important;
            max-height: calc(90vh - 60px) !important;
        }

        /* Sidebar Styling */
        .db-sidebar {
            width: 260px;
            background: #ffffff;
            color: #1e293b;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e2e8f0;
            padding: 0;
            flex-shrink: 0;
        }

        .db-sidebar-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 10px;
        }

        .db-sidebar-header h2 {
            font-size: 1.2rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }

        .db-sidebar-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 0 25px 20px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 15px;
        }

        .db-sidebar-profile img {
            width: 44px; height: 44px; border-radius: 50%; object-fit: cover;
        }

        .db-sidebar-profile div {
            display: flex; flex-direction: column;
        }

        .db-sidebar-profile .sp-role {
            font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;
        }

        .db-sidebar-profile .sp-name {
            font-size: 0.95rem; font-weight: 700; color: #1e293b;
        }

        .db-menu {
            padding: 0 15px;
        }

        .db-menu-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 18px;
            margin-bottom: 5px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            color: #475569;
            font-weight: 600;
            font-size: 15px;
        }

        .db-menu-item:hover {
            background: #f1f5f9;
        }

        .db-menu-item.active {
            background: #363d72;
            color: white;
            box-shadow: none;
        }
        
        .db-menu-item svg {
            width: 20px;
            height: 20px;
        }

        /* Content Area Styling */
        .db-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
            overflow: hidden;
        }

        .db-header {
            padding: 15px 30px;
            background: #363d72;
            color: white;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            z-index: 10;
        }
        
        .db-header .header-welcome {
            font-size: 0.95rem;
            margin-right: 20px;
            font-weight: 600;
        }
        
        .db-header .close-btn {
            background: #e76464;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: 0.2s;
        }
        
        .db-header .close-btn:hover {
            background: #ef4444;
        }

        /* Premium Dashboard Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
            gap: 20px;
            border: 1px solid #f1f5f9;
            transition: transform 0.2s;
        }

        .stat-card:hover { transform: translateY(-3px); }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-card h3 { font-size: 1.5rem; font-weight: 800; margin: 4px 0 0 0; color: #1e293b; }
        .stat-card small { color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; }

        /* Program Cards aligned to the design */
        .program-card {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: white;
            display: flex;
            flex-direction: column;
        }
        .pc-top { background: #363d72; color: white; padding: 25px; }
        .pc-kode { font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; opacity: 0.9; }
        .pc-price { font-size: 2rem; font-weight: 800; margin: 0 0 10px 0; }
        .pc-name { font-size: 0.9rem; font-weight: 700; text-transform: uppercase; margin-bottom: 5px; }
        .pc-desc { font-size: 0.9rem; opacity: 0.9; margin-bottom: 8px;}
        .pc-admin { font-size: 0.85rem; font-weight: 600; }
        
        .pc-bottom { padding: 25px; background: white; flex: 1; }
        .pc-feature { display: flex; align-items: flex-start; gap: 12px; font-size: 0.85rem; color: #475569; margin-bottom: 15px; line-height: 1.4; font-weight: 600; }
        .pc-check { background: #10b981; color: white; border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; font-size: 10px; flex-shrink: 0; margin-top: 2px; }

        /* Tables & Lists */
        .db-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .db-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .db-table th {
            background: #f8fafc;
            padding: 16px 24px;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e2e8f0;
        }

        .db-table td {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .db-table tr:hover td { background-color: #fcfdfe; }

        /* Step Progress for Students */
        .student-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 50px;
            position: relative;
            padding: 0 40px;
        }

        .student-steps::before {
            content: '';
            position: absolute;
            top: 24px; left: 60px; right: 60px;
            height: 3px; background: #e2e8f0;
            z-index: 1;
        }

        .s-step {
            position: relative; z-index: 2;
            display: flex; flex-direction: column; align-items: center; gap: 12px;
            width: 120px;
        }

        .s-step-circle {
            width: 48px; height: 48px; border-radius: 50%;
            background: white; border: 3px solid #e2e8f0;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; color: #94a3b8; transition: all 0.3s;
        }

        .s-step.active .s-step-circle {
            background: #3b82f6; border-color: #3b82f6; color: white;
            box-shadow: 0 0 0 8px rgba(59, 130, 246, 0.15);
        }

        .s-step.completed .s-step-circle {
            background: #10b981; border-color: #10b981; color: white;
        }

        .s-step-label { font-size: 0.875rem; font-weight: 700; color: #64748b; }
        .s-step.active .s-step-label { color: #3b82f6; }

        /* Role Portal Styling */
        .portal-login-box {
            max-width: 450px; width: 100%;
            background: white; padding: 50px; border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .portal-brand img { height: 60px; margin-bottom: 30px; }

        .btn-portal-primary {
            background: #3b82f6; color: white; border: none;
            padding: 18px; border-radius: 16px; font-weight: 800;
            width: 100%; cursor: pointer; transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-portal-primary:hover { background: #2563eb; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2); }

        .tab-pane { display: none; }
        .tab-pane.active { display: block; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .badge {
            padding: 6px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 800;
        }
        .badge-blue { background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
        .badge-green { background: #ecfdf5; color: #059669; border: 1px solid #d1fae5; }

        @media (max-width: 1024px) {
            .db-sidebar { width: 80px; padding: 30px 10px; }
            .db-sidebar-header h2, .db-menu-item span { display: none; }
            .db-menu-item { justify-content: center; padding: 15px; }
        }


        #tmodal-comment-field {
            flex: 1;
            padding: 12px 18px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background: #444; /* Darker as per image */
            color: white;
            font-size: 14px;
            outline: none;
        }
        #tmodal-comment-field::placeholder { color: #bbb; }
        #tmodal-comment-submit {
            background: #ccc;
            color: #666;
            border: none;
            border-radius: 8px;
            padding: 0 25px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        #tmodal-comment-submit.active {
            background: #007bff;
            color: white;
        }

        /* ===== AR Modal Styles ===== */
        #ar-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            z-index: 3500;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        #ar-modal-overlay.active { display: flex; }
        #ar-modal-box {
            background: transparent; /* Changed from #fff */
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: modalPopIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .ar-modal-header {
            padding: 30px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: transparent;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
        }
        .ar-modal-header h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #fff; /* White for transparency */
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }
        .ar-modal-close {
            background: rgba(255,255,255,0.2);
            border: none;
            color: #fff;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            transition: all 0.2s;
            backdrop-filter: blur(5px);
        }
        .ar-modal-close:hover { background: #e2e8f0; color: #0f172a; transform: rotate(90deg); }
        .ar-frame-wrap {
            flex: 1;
            width: 100%;
            height: 100%;
            background: transparent;
        }
        #ar-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        .btn-footer.ar-btn {
            background: linear-gradient(135deg, #007bff, #00c6ff);
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
            display: none; /* Hidden by default */
        }
        .btn-footer.ar-btn:hover {
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.6);
        }

        /* Hotspot AR Button Styles */
        .hotspot-ar-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            pointer-events: auto;
        }
        .hotspot-ar-wrap:hover {
            transform: scale(1.1);
        }
        .hotspot-ar-btn {
            background: linear-gradient(135deg, #007bff, #00c6ff);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 10px 25px rgba(0, 123, 255, 0.5);
            display: flex;
            align-items: center;
            gap: 10px;
            border: 3px solid white;
            white-space: nowrap;
        }
        .hotspot-ar-btn svg {
            width: 24px;
            height: 24px;
        }
        .hotspot-ar-ripple {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50px;
            background: rgba(0, 123, 255, 0.4);
            z-index: -1;
            animation: arRipple 2s infinite;
        }
        @keyframes arRipple {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        /* ===== Scanning Overlay ===== */
        #ar-scanning-overlay {
            position: absolute;
            inset: 0;
            background: #000;
            z-index: 50;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        #ar-scanning-video {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.6;
        }
        .scanning-content {
            position: relative;
            z-index: 60;
            text-align: center;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        .scanning-circle {
            width: 120px;
            height: 120px;
            border: 4px dashed rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: rotateScanning 4s linear infinite;
        }
        .scanning-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulseScanning 2s infinite;
        }
        @keyframes rotateScanning {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes pulseScanning {
            0% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 0.5; }
        }
        .scanning-text {
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }
        .scanning-subtext {
            font-size: 14px;
            opacity: 0.8;
            max-width: 250px;
            line-height: 1.5;
        }
        .scanning-floor-animation {
            width: 100px;
            height: 60px;
            border: 2px solid white;
            border-top: none;
            position: relative;
            margin-top: 10px;
        }
        .scanning-line {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: #007bff;
            box-shadow: 0 0 15px #007bff;
            animation: scanLineMove 2s ease-in-out infinite;
        }
        @keyframes scanLineMove {
            0%, 100% { top: 0; }
            50% { top: 100%; }
        }
    </style>
</head>
<body>
    <div id="pano"></div>
    @include('components.audio-player')

    <!-- Onboarding Elements (Kept for future use, but hidden temporarily) -->
    <div id="audio-modal-overlay">
        <div class="bg-slider-track">
            <div class="bg-slide" style="background-image: url('{{ asset('assets/foto360/5e.jpg') }}');"></div>
            <div class="bg-slide overlay" style="background-image: url('{{ asset('assets/foto360/5e.jpg') }}');"></div>
        </div>

        <div id="modal-audio" class="modal-box">
            <h2>Aktifkan Audio?</h2>
            <p>Apakah Anda ingin mengaktifkan suara saat menjelajahi BEC-AR?</p>
            <div class="btn-group">
                <button id="btn-yes" class="btn btn-yes">YES</button>
                <button id="btn-no" class="btn btn-no">NO</button>
            </div>
        </div>

        <div id="modal-welcome" class="modal-box" style="display: none;">
            <h2 class="welcome-title">Selamat Datang di BEC-AR</h2>
            <p>"Jelajahi ruangan dan Bayangkan muncul nyata di hadapan Anda."</p>
            <button id="btn-start" class="btn btn-start">START</button>
        </div>

        <div id="modal-camera" class="modal-box" style="display: none;">
            <h2>Izinkan Kamera</h2>
            <p>BEC-AR membutuhkan akses kamera untuk menjalankan AR.</p>
            <div class="btn-group">
                <button id="btn-allow" class="btn btn-allow">Izinkan Kamera</button>
                <button id="btn-deny" class="btn btn-deny">Tidak Izinkan</button>
            </div>
        </div>

        <div id="modal-loading" class="modal-box" style="display: none;">
            <p class="loading-text">Mohon tunggu sebentar</p>
            <div class="spinner"></div>
        </div>

        <div id="modal-denied" class="modal-box" style="display: none;">
            <h2>Akses Kamera Ditolak</h2>
            <p>Anda tidak mengizinkan akses kamera. BEC-AR tidak dapat dijalankan tanpa kamera.</p>
            <button id="btn-back" class="btn btn-back">Kembali ke Beranda</button>
        </div>

        <div id="modal-entering" style="display: none;">
            <p class="entering-text">MEMASUKI AREA</p>
            <div class="entering-dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>

        <div id="modal-video" style="display: none;">
            <video id="drone-video" preload="auto">
                <source src="{{ asset('assets/video/Drone terbaru.mp4') }}" type="video/mp4">
            </video>
        </div>
    </div>

    <!-- Local Audio Removed in favor of Global Audio Player -->

    <!-- Main Tour UI (Hidden Until Onboarding Complete) -->
    <div id="tour-ui" style="display: none;"> <!-- Hidden until tour starts -->
        <button id="home-btn">
            <svg viewBox="0 0 24 24"><path d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" /></svg>
        </button>

        <button id="sidebar-toggle" onclick="document.getElementById('sidebar-rooms').classList.toggle('active')">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line></svg>
        </button>

        <!-- Global Audio Control will handle mute toggling -->

        <div id="sidebar-rooms" class="active">
            <div class="sidebar-header">
                <h3>Daftar Ruangan</h3>
                <button class="btn-close-sidebar" id="close-sidebar" onclick="document.getElementById('sidebar-rooms').classList.remove('active')">✕</button>
            </div>
            <div class="room-list" id="room-list-container">
                <div class="room-item active" data-id="ruang-1" onclick="switchRoom('ruang-1')">Ruang 1</div>
                <div class="room-item" data-id="ruang-2" onclick="switchRoom('ruang-2')">Ruang 2</div>
                <div class="room-item" data-id="ruang-3" onclick="switchRoom('ruang-3')">Ruang 3</div>
                <div class="room-item" data-id="ruang-4a" onclick="switchRoom('ruang-4a')">Ruang 4A</div>
                <div class="room-item" data-id="ruang-4b" onclick="switchRoom('ruang-4b')">Ruang 4B</div>
                <div class="room-item" data-id="ruang-5" onclick="switchRoom('ruang-5')">Ruang 5</div>
                <div class="room-item" data-id="ruang-5a" onclick="switchRoom('ruang-5a')">Ruang 5A</div>
                <div class="room-item" data-id="ruang-5b" onclick="switchRoom('ruang-5b')">Ruang 5B</div>
                <div class="room-item" data-id="ruang-5c" onclick="switchRoom('ruang-5c')">Ruang 5C</div>
                <div class="room-item" data-id="ruang-5d" onclick="switchRoom('ruang-5d')">Ruang 5D</div>
                <div class="room-item" data-id="ruang-5e" onclick="switchRoom('ruang-5e')">Ruang 5E</div>
                <div class="room-item" data-id="ruang-5f" onclick="switchRoom('ruang-5f')">Ruang 5F</div>
                <div class="room-item" data-id="ruang-5g" onclick="switchRoom('ruang-5g')">Ruang 5G</div>
                <div class="room-item" data-id="ruang-6" onclick="switchRoom('ruang-6')">Ruang 6</div>
                <div class="room-item" data-id="ruang-6a" onclick="switchRoom('ruang-6a')">Ruang 6A</div>
                <div class="room-item" data-id="vip-dalam" onclick="switchRoom('vip-dalam')">VIP Dalam</div>
                <div class="room-item" data-id="ruang-7" onclick="switchRoom('ruang-7')">Ruang 7</div>
                <div class="room-item" data-id="ruang-7a" onclick="switchRoom('ruang-7a')">Ruang 7A</div>
                <div class="room-item" data-id="ruang-7b" onclick="switchRoom('ruang-7b')">Ruang 7B</div>
                <div class="room-item" data-id="ruang-8" onclick="switchRoom('ruang-8')">Ruang 8</div>
                <div class="room-item" data-id="ruang-9" onclick="switchRoom('ruang-9')">Ruang 9</div>
                <div class="room-item" data-id="ruang-11" onclick="switchRoom('ruang-11')">Ruang 11</div>
                <div class="room-item" data-id="ruang-11a" onclick="switchRoom('ruang-11a')">Ruang 11A</div>
                <div class="room-item" data-id="ruang-11b" onclick="switchRoom('ruang-11b')">Ruang 11B</div>
                <div class="room-item" data-id="ruang-12" onclick="switchRoom('ruang-12')">Ruang 12</div>
                <div class="room-item" data-id="ruang-12a" onclick="switchRoom('ruang-12a')">Ruang 12A</div>
                <div class="room-item" data-id="ruang-13" onclick="switchRoom('ruang-13')">Ruang 13</div>
                <div class="room-item" data-id="ruang-14" onclick="switchRoom('ruang-14')">Ruang 14</div>
                <div class="room-item" data-id="camp-reguler" onclick="switchRoom('camp-reguler')">Camp Reguler</div>
                <div class="room-item" data-id="luar-homestay" onclick="switchRoom('luar-homestay')">Luar Homestay</div>
                <div class="room-item" data-id="dalam-homestay" onclick="switchRoom('dalam-homestay')">Dalam Homestay</div>
                <div class="room-item" data-id="luar-vip-putra" onclick="switchRoom('luar-vip-putra')">Luar VIP Putra</div>
                <div class="room-item" data-id="luar-vip-putri" onclick="switchRoom('luar-vip-putri')">Luar VIP Putri</div>
            </div>
            <div class="sidebar-footer">
                <button class="btn-sidebar-opt btn-sidebar-close" id="btn-close-sidebar-bottom" onclick="document.getElementById('sidebar-rooms').classList.remove('active')">
                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>
                    Tutup Daftar Ruangan
                </button>
                <button class="btn-sidebar-opt btn-sidebar-home" id="btn-back-home" onclick="window.location.href='/'">
                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" /></svg>
                    Kembali ke Beranda
                </button>
            </div>
        </div>

        <div id="room-info-card">
            <h4 id="current-room-name">Ruang 1</h4>
            <p style="max-width: 280px; line-height: 1.5; margin-bottom: 12px;">Klik tombol hotspot atau daftar ruangan<br>untuk berpindah ruangan</p>

        </div>

        <div id="footer-bar">
            <div class="footer-logo">
                <img src="{{ asset('assets/logo_BEC.png') }}" alt="BEC Logo">
            </div>
            <div class="footer-center">
                <button class="btn-footer" id="btn-video-tutorial">
                    <svg style="width:22px;height:22px" viewBox="0 0 24 24"><path fill="currentColor" d="M10,15.5V9.5L14.5,12.5L10,15.5M12,2C6.48,2 2,6.48 2,12C2,17.52 6.48,22 12,22C17.52,22 22,17.52 22,12C22,6.48 17.52,2 12,2Z" /></svg>
                    VIDEO TUTORIAL
                </button>
            </div>
            <div class="footer-right">
                <a href="#" onclick="openDirectRegistration(); return false;" class="btn-footer" id="btn-open-registration" style="text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <svg style="width:22px;height:22px" viewBox="0 0 24 24"><path fill="currentColor" d="M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z" /></svg>
                    DAFTAR SEKARANG (POS)
                </a>
            </div>
        </div>
    </div>

    <!-- Tutorial Video Modal -->
    <div id="tutorial-modal-overlay">
        <div id="tutorial-modal-box">
            <div class="tmodal-header">
                <h3>Tutorial Video</h3>
                <button class="tmodal-close-x" id="tmodal-close-x" title="Tutup">&#10005;</button>
            </div>
            <div class="tmodal-main">
                <!-- Left: Video & Stats -->
                <div class="tmodal-video-area">
                    <div class="tmodal-video-wrap" style="flex: 1; position: relative; display: flex; align-items: center; justify-content: center; background: #000;">
                        <video id="tutorial-video-el" preload="auto" poster="{{ asset('assets/video/intro video.jpeg') }}" controls style="width: 100%; height: 100%; object-fit: contain;">
                            <source id="video-source-main" src="{{ asset('assets/video/video tutorial final.mp4') }}" type="video/mp4">
                        </video>
                        <div id="tmodal-play-overlay" style="position: absolute; inset: 0; background-image: url('{{ asset('assets/video/intro video.jpeg') }}'); background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                             <div style="width: 80px; height: 80px; background: rgba(79, 70, 229, 0.9); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 0 30px rgba(79, 70, 229, 0.5);">
                                <svg style="width: 40px; height: 40px; margin-left: 5px;" viewBox="0 0 24 24"><path fill="currentColor" d="M8,5.14V19.14L19,12.14L8,5.14Z" /></svg>
                             </div>
                        </div>
                    </div>
                    <div class="tmodal-footer" style="background: #fff; padding: 20px 30px; border-top: 1px solid #eee;">
                        <div class="tmodal-stats">
                            <div class="tmodal-stat" id="tmodal-stat-views">
                                <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9Z"/></svg>
                                <span id="tmodal-view-count">0</span> x Ditonton
                            </div>
                            <div class="tmodal-stat" id="tmodal-stat-like">
                                <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z"/></svg>
                                <span id="tmodal-like-count">0</span> Suka
                            </div>
                        </div>
                        <button id="btn-close-tutorial" style="background: #f1f5f9; color: #475569; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; cursor: pointer;">Tutup Tutorial</button>
                    </div>
                </div>

                <!-- Right: Comment Column (Memanjang) -->
                <div class="tmodal-comment-area" id="tmodal-comment-section">
                    <div style="padding: 20px 25px; background: #fff; border-bottom: 1px solid #eee;">
                        <h4 style="margin:0; font-size: 18px; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 10px;">
                            <svg style="width:22px;height:22px;color:#4f46e5" viewBox="0 0 24 24"><path fill="currentColor" d="M9,22A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V4C2,2.89 2.9,2 4,2H20A2,2 0 0,1 22,4V16A2,2 0 0,1 20,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22V22H9Z"/></svg>
                            Komentar (<span id="tmodal-comment-count-header">0</span>)
                        </h4>
                    </div>
                    <div id="tmodal-comment-list" style="flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 15px;">
                        <div id="tcomment-empty-state" style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding: 60px 20px; color:#94a3b8; text-align:center; gap:12px;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                            <div style="font-size:0.95rem; font-weight:600; color:#64748b;">Belum ada pertanyaan</div>
                        </div>
                    </div>
                    <div class="tcomment-input-area" style="padding: 20px; background: #fff; border-top: 1px solid #eee;">
                        <label for="tmodal-input-comment" class="sr-only">Tulis pertanyaan Anda</label>
                        <textarea id="tmodal-input-comment" placeholder="Tulis pertanyaan Anda..." aria-label="Tulis pertanyaan Anda" style="width: 100%; height: 90px; padding: 15px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-family: 'Inter', sans-serif; font-size: 0.95rem; outline: none; resize: none; margin-bottom: 12px;"></textarea>
                        <button id="tmodal-send-comment" onclick="window.submitComment()" style="width: 100%; background: #4f46e5; color: white; border: none; border-radius: 10px; padding: 12px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);">Kirim Pertanyaan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AR Modal -->
    <div id="ar-modal-overlay">
        <div id="ar-modal-box" style="position: relative;">
            <div class="ar-modal-header" style="z-index: 100; padding: 25px 40px; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(to bottom, rgba(0,0,0,0.5), transparent); position: absolute; top: 0; left: 0; width: 100%; box-sizing: border-box;">
                <h3 id="ar-modal-title" style="margin:0; font-size: 24px; font-weight: 800; color: white; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">AR BEC - Bangunan 3D</h3>
                <div style="display: flex; gap: 15px; align-items: center;">
                    <button onclick="window.closeAllModals()" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); color: white; border: 1.5px solid rgba(255,255,255,0.3); border-radius: 14px; padding: 12px 24px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 10px; box-shadow: 0 8px 32px rgba(0,0,0,0.2); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); pointer-events: auto;" onmouseover="this.style.background='rgba(255,255,255,0.25)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateY(0)';" id="btn-back-from-ar">
                        <svg style="width:20px;height:20px" viewBox="0 0 24 24"><path fill="currentColor" d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z" /></svg>
                        Kembali ke Virtual Tour
                    </button>
                    <button class="ar-modal-close" id="ar-modal-close" onclick="window.closeAllModals()" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(12px); color: white; border: 1.5px solid rgba(255,255,255,0.3); width: 44px; height: 44px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 20px; transition: all 0.3s; pointer-events: auto;" onmouseover="this.style.background='rgba(255,255,255,0.25)'; this.style.transform='rotate(90deg)';" onmouseout="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='rotate(0)';" title="Tutup">✕</button>
                </div>
            </div>

            <!-- Scanning Overlay -->
            <div id="ar-scanning-overlay">
                <video id="ar-scanning-video" autoplay playsinline muted></video>
                <div class="scanning-content">
                    <div class="scanning-circle">
                        <div class="scanning-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="white"><path d="M12,2C6.48,2 2,6.48 2,12C2,17.52 6.48,22 12,22C17.52,22 22,17.52 22,12C22,6.48 17.52,2 12,2M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M13,7H11V13L16.25,16.15L17.25,14.5L13,12V7Z"/></svg>
                        </div>
                    </div>
                    <div class="scanning-text">Mencari Lantai...</div>
                    <div class="scanning-subtext">Arahkan kamera ke lantai yang datar untuk menempatkan gedung 3D</div>
                    <div class="scanning-floor-animation">
                        <div class="scanning-line"></div>
                    </div>
                </div>
            </div>

            <div class="ar-frame-wrap" style="height: 100vh; width: 100vw; position: relative;">
                <model-viewer id="ar-modal-viewer" src="{{ asset('AR/model/bangunan3d.glb') }}" 
                    ios-src="{{ asset('AR/model/bangunan3d.usdz') }}"
                    ar ar-modes="webxr quick-look scene-viewer" 
                    camera-controls auto-rotate shadow-intensity="1" 
                    exposure="1" environment-image="neutral"
                    loading="eager"
                    style="width: 100%; height: 100%; background: transparent;">
                    
                    <button slot="ar-button" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; border: none; border-radius: 14px; padding: 16px 32px; position: absolute; bottom: 50px; left: 50%; transform: translateX(-50%); font-weight: 800; box-shadow: 0 15px 35px rgba(79, 70, 229, 0.4); font-size: 1.1rem; cursor: pointer; transition: all 0.3s; pointer-events: auto;" onmouseover="this.style.transform='translateX(-50%) translateY(-5px)'; this.style.boxShadow='0 20px 45px rgba(79, 70, 229, 0.5)';" onmouseout="this.style.transform='translateX(-50%) translateY(0)';" id="btn-ar-launch">
                        ✨ LIHAT DI RUANGAN ANDA (AR)
                    </button>
                </model-viewer>
            </div>
        </div>
    </div>

    <!-- Native DOM Register Popup Modal -->
    <div id="register-dom-overlay" style="display:none; position:fixed; inset:0; z-index:99999; background:rgba(0,0,0,0.8); align-items:center; justify-content:center; backdrop-filter:blur(5px); overflow-y: auto; padding: 20px;">
        <div class="register-card">
            <button class="register-close-x" onclick="document.getElementById('register-dom-overlay').style.display='none';" title="Tutup">&#10005;</button>

            <div class="card-logo">
                <img src="{{ asset('assets/logo_BEC.png') }}" alt="BEC">
                <div>
                    <div style="font-size:1rem; font-weight:900; color:#0f172a;">Brilliant English Course</div>
                </div>
            </div>

            <div class="step-pills">
                <span class="pill pill-1">① Buat Akun</span>
                <span class="pill pill-2">② Isi Data</span>
                <span class="pill pill-2">③ Pilih Kursus</span>
            </div>

            <div class="card-header">
                <h1>Daftar Akun Pendaftar</h1>
                <p>Langkah pertama pendaftaran BEC. Setelah membuat akun, lengkapi data diri dan pilih program kursus Anda.</p>
            </div>

            @if ($errors->any())
                <div style="background:#fee2e2;color:#991b1b;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-size:0.875rem;border:1px solid #fca5a5; text-align: left;">
                    @foreach ($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group full">
                        <label class="form-label" for="reg-name">Nama Lengkap</label>
                        <input type="text" id="reg-name" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Sesuai kartu identitas" autocomplete="name">
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group full">
                        <label class="form-label" for="reg-email">Alamat Email</label>
                        <input type="email" id="reg-email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="aktif@email.com" autocomplete="email">
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="reg-password">Password</label>
                        <input type="password" id="reg-password" name="password" class="form-control" required placeholder="Min. 8 karakter" autocomplete="new-password">
                        @error('password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="reg-password-confirm">Konfirmasi Password</label>
                        <input type="password" id="reg-password-confirm" name="password_confirmation" class="form-control" required placeholder="Ulangi password" autocomplete="new-password">
                    </div>
                </div>

                <button type="submit" class="btn-submit">BUAT AKUN &amp; LANJUTKAN →</button>
            </form>

            <div class="form-footer" style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <span>Sudah punya akun? <a href="javascript:void(0)" onclick="closeRegisterDOM()">Kembali ke tour</a></span>
                <a href="javascript:void(0)" onclick="closeRegisterDOM()" class="btn-outline" style="margin-top:0; width:auto; padding:10px 14px;">
                    Kembali ke Virtual Tour
                </a>
            </div>
        </div>
    </div>

    <!-- Hidden WebXR model-viewer for direct markerless AR launch -->
    <model-viewer id="direct-ar-viewer" src="{{ asset('AR/model/bangunan3d.glb') }}" ar ar-modes="webxr scene-viewer quick-look" ar-placement="floor" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; opacity: 0.01; pointer-events: none; z-index: -100;"></model-viewer>

    <!-- BEC Registration / Admission Portal Modal -->
    <div id="registration-modal-overlay">
        <div class="registration-box" id="registration-modal-box">
            
            <!-- Unified Login Portal (Initial Screen) -->
            <div id="portal-entry" style="display: flex; align-items: center; justify-content: center; height: 100%; background: #f1f5f9; padding: 40px;">
                <div class="portal-login-box">
                    <button class="close-reg-x" style="position: absolute; right: 25px; top: 25px;" onclick="closeRegModal()">&#10005;</button>
                    
                    <div class="portal-brand">
                        <img src="{{ asset('assets/logo_BEC.png') }}" alt="BEC Logo">
                        <h2 style="font-size: 1.75rem; font-weight: 800; color: #1e293b; margin-top: 10px;">Portal Pendaftaran</h2>
                        <p style="color: #64748b; margin-bottom: 40px;">Silakan masuk untuk melanjutkan pendaftaran Anda.</p>
                    </div>

                    <div style="text-align: left;">
                        <div style="margin-bottom: 20px;">
                            <label for="portal-user" style="display: block; font-size: 0.875rem; font-weight: 700; color: #475569; margin-bottom: 8px;">Username atau Email</label>
                            <input type="text" id="portal-user" placeholder="Masukkan username anda" autocomplete="username" style="width: 100%; padding: 14px 18px; border-radius: 12px; border: 1px solid #e2e8f0; outline: none; transition: 0.2s;">
                        </div>
                        <div style="margin-bottom: 30px;">
                            <label for="portal-pass" style="display: block; font-size: 0.875rem; font-weight: 700; color: #475569; margin-bottom: 8px;">Password</label>
                            <input type="password" id="portal-pass" placeholder="••••••••" autocomplete="current-password" style="width: 100%; padding: 14px 18px; border-radius: 12px; border: 1px solid #e2e8f0; outline: none; transition: 0.2s;">
                        </div>
                        <button class="btn-portal-primary" onclick="attemptLogin()">MASUK KE PORTAL</button>
                    </div>

                    <div style="margin-top: 30px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
                        <p style="font-size: 0.875rem; color: #64748b;">Belum memiliki akun? <a href="javascript:void(0)" onclick="closeRegModal(); openRegisterIframe();" style="color: #3b82f6; font-weight: 700; text-decoration: none;">Daftar Sekarang →</a></p>
                        <div style="margin-top: 15px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <span style="font-size: 0.75rem; color: #94a3b8;">Akses Admin?</span>
                            <button onclick="document.getElementById('portal-pass').value='admin123'; attemptLogin();" style="background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 6px; padding: 4px 10px; font-size: 0.7rem; font-weight: 700; color: #64748b; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">LOGIN ADMIN</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Portal Interface (The Main Dashboard) -->
            <div id="portal-interface" style="display: none; width: 100%; height: 100%;">
                <!-- Sidebar -->
                <div class="db-sidebar">
                    <div class="db-sidebar-header">
                        <img src="{{ asset('assets/logo_BEC.png') }}" style="height: 38px;">
                    </div>
                    
                    <div class="db-sidebar-profile">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background: #e2e8f0; display:flex; align-items:center; justify-content:center; color: #64748b;">
                            <svg viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" /></svg>
                        </div>
                        <div>
                            <span class="sp-role" id="sidebar-role-label">Divisi Frontliner</span>
                            <span class="sp-name">Frontliner</span>
                        </div>
                    </div>
                    
                    <div class="db-menu" id="sidebar-menu-container" style="flex: 1;">
                        <!-- JS Dynamic Menu Items -->
                    </div>

                    <div style="padding: 20px 15px;">
                        <button onclick="exitPortal()" style="width: 100%; display: flex; align-items: center; justify-content:center; gap: 8px; padding: 12px; border-radius: 8px; background: #fef2f2; color: #ef4444; border: none; cursor: pointer; font-weight: 700; transition: 0.2s;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                            <svg viewBox="0 0 24 24" style="width:18px;height:18px;"><path fill="currentColor" d="M16,17V14H9V10H16V7L21,12L16,17M14,2A2,2 0 0,1 16,4V6H14V4H5V20H14V18H16V20A2,2 0 0,1 14,22H5A2,2 0 0,1 3,20V4A2,2 0 0,1 5,2H14Z" /></svg>
                            Ganti Akun
                        </button>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="db-content">
                    <div class="db-header">
                        <div class="header-welcome">Selamat datang di <span id="header-welcome-role">Frontliner Management</span></div>
                        <button class="close-btn" onclick="closeRegModal()">
                            <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M16 17v-3H9v-4h7V7l5 5-5 5M14 2a2 2 0 0 1 2 2v2h-2V4H5v16h9v-2h2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9z"></path></svg>
                        </button>
                    </div>
                    
                    <div class="db-body">
                        <!-- Tab: Welcome (Overview) -->
                        <div id="tab-welcome" class="tab-pane active">
                            <!-- Student Overview Inner -->
                            <div id="student-overview" style="display: none;">
                                <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 24px; padding: 40px; color: white; margin-bottom: 40px; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);">
                                    <div style="position: relative; z-index: 2;">
                                        <h2 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 12px; letter-spacing: -1px;">Halo, Calon Siswa Brilliant! 👋</h2>
                                        <p style="font-size: 1.125rem; opacity: 0.9; max-width: 600px; line-height: 1.6;">Selamat datang di portal pendaftaran BEC. Mari lengkapi beberapa langkah sederhana untuk memulai perjalanan belajar Anda.</p>
                                        
                                        <div style="display: flex; gap: 40px; margin-top: 30px;">
                                            <div class="stat-mini">
                                                <div style="font-size: 1.5rem; font-weight: 800;">{{ count($students) }}</div>
                                                <div style="font-size: 0.8rem; opacity: 0.8; font-weight: 600;">ALUMNI BEC</div>
                                            </div>
                                            <div style="width: 1px; background: rgba(255,255,255,0.2);"></div>
                                            <div class="stat-mini">
                                                <div style="font-size: 1.5rem; font-weight: 800;">{{ count($courses) }}</div>
                                                <div style="font-size: 0.8rem; opacity: 0.8; font-weight: 600;">PILIHAN PROGRAM</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="position: absolute; right: -50px; bottom: -50px; font-size: 240px; opacity: 0.1; transform: rotate(-15deg);">✨</div>
                                </div>

                                <h4 style="font-size: 1.125rem; font-weight: 800; color: #1e293b; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                                    <span style="color: #3b82f6;">●</span> Progress Pendaftaran
                                </h4>

                                <div class="student-steps" style="margin-bottom: 60px;">
                                    <div class="s-step active">
                                        <div class="s-step-circle">1</div>
                                        <div class="s-step-label">Pilih Program</div>
                                    </div>
                                    <div class="s-step">
                                        <div class="s-step-circle">2</div>
                                        <div class="s-step-label">Data Diri</div>
                                    </div>
                                    <div class="s-step">
                                        <div class="s-step-circle">3</div>
                                        <div class="s-step-label">Bayar</div>
                                    </div>
                                    <div class="s-step">
                                        <div class="s-step-circle">4</div>
                                        <div class="s-step-label">Verifikasi</div>
                                    </div>
                                </div>

                                <div style="display: flex; justify-content: center; gap: 20px;">
                                    <a href="#" onclick="switchRegTab('tab-registration'); return false;" class="btn-portal-primary" style="width: auto; padding: 18px 60px; font-size: 1.125rem; text-decoration:none;">
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <svg style="width:24px;height:24px" viewBox="0 0 24 24"><path fill="currentColor" d="M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z" /></svg>
                                            MASUK KE POS PENDAFTARAN
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- Admin Overview Inner -->
                            <div id="admin-overview" style="display: none;">
                                <div class="stats-grid">
                                    <div class="stat-card">
                                        <div class="stat-icon" style="background: #e0f2fe; color: #0369a1;">👥</div>
                                        <div>
                                            <small>Total Siswa</small>
                                            <h3>{{ $students->count() }}</h3>
                                        </div>
                                    </div>
                                    <div class="stat-card">
                                        <div class="stat-icon" style="background: #fef9c3; color: #a16207;">📚</div>
                                        <div>
                                            <small>Program Aktif</small>
                                            <h3>{{ $courses->count() }}</h3>
                                        </div>
                                    </div>
                                    <div class="stat-card" style="background: white; border-bottom: 4px solid #10b981;">
                                        <div class="stat-icon" style="background: #ecfdf5; color: #059669;">💰</div>
                                        <div>
                                            <small>Pendapatan Berhasil</small>
                                            <h3>Rp {{ number_format($payments->where('status', 'success')->sum('amount'), 0, ',', '.') }}</h3>
                                        </div>
                                    </div>
                                    <div class="stat-card">
                                        <div class="stat-icon" style="background: #f3f4f6; color: #374151;">📅</div>
                                        <div>
                                            <small>Periode Terdaftar</small>
                                            <h3>{{ $periods->count() }}</h3>
                                        </div>
                                    </div>
                                </div>

                                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                                    <div class="db-card" style="padding: 30px; background: white;">
                                        <h4 style="margin:0 0 15px 0; color: #1e293b; font-weight: 800;">Manajemen Basis Data</h4>
                                        <p style="color: #64748b; font-size: 0.95rem; line-height: 1.6;">Selamat bekerja, Admin! Anda sedang berada di panel kontrol utama <strong>adminkampunginggris</strong>. Panel ini terhubung langsung dengan sistem VirtualAR untuk memudahkan verifikasi calon pendaftar yang masuk dari pengalaman VR.</p>
                                        <div style="display: flex; gap: 15px; margin-top: 25px;">
                                            <button class="badge badge-blue" style="cursor: pointer;">Cek Review ({{ $newRegistrations->where('status', 'pending')->count() }})</button>
                                            <a href="{{ route('admin.pos') }}" class="badge badge-yellow" style="cursor: pointer; text-decoration: none; background: #eab308; color: white;">Point of Sale (POS) Admin</a>
                                            <button class="badge badge-green" style="cursor: pointer;">Verifikasi Pembayaran</button>
                                        </div>
                                    </div>
                                    
                                    <div class="db-card" style="padding: 25px; background: #1e293b; color: white;">
                                        <h5 style="margin:0 0 10px 0; color: #38bdf8;">Tips Admin</h5>
                                        <p style="font-size: 0.85rem; opacity: 0.7; margin:0;">Gunakan fitur review untuk melihat kecocokan data calon pendaftar sebelum menekan tombol verifikasi.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Courses -->
                        <div id="tab-courses" class="tab-pane">
                            <div style="margin-bottom: 25px;">
                                <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">Manajemen Program</h2>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px;">
                                @foreach($courses as $course)
                                <div class="program-card">
                                    <div class="pc-top">
                                        <div class="pc-kode">Kode : {{ substr(md5($course->id), 0, 9) }} ⓘ</div>
                                        <h3 class="pc-price">Rp {{ number_format($course->price ?? 0, 0, ',', '.') }}</h3>
                                        <div class="pc-name">{{ strtoupper($course->name) }}</div>
                                        <div class="pc-desc">{{ str_contains(strtolower($course->name), 'short') || str_contains(strtolower($course->name), '2') ? '14 Hari' : '30 Hari' }}</div>
                                        <div class="pc-admin">Admin : Rp 125.000</div>
                                    </div>
                                    <div class="pc-bottom">
                                        <div class="pc-feature"><span class="pc-check">✔</span> Free Voucher Brilliant Health Care</div>
                                        <div class="pc-feature"><span class="pc-check">✔</span> Tempat Tinggal / Camp</div>
                                        <div class="pc-feature"><span class="pc-check">✔</span> Modul, Competence & Gelang</div>
                                        <div class="pc-feature"><span class="pc-check">✔</span> Sertifikat</div>
                                        <div class="pc-feature"><span class="pc-check">✔</span> Bonus Materi Psychotraining & Enterpreneurship</div>
                                        <div class="pc-feature"><span class="pc-check">✔</span> Pendidikan Budi Pekerti Luhur Etika Sopan Santun Budaya Jawa</div>
                                        <div class="pc-feature"><span class="pc-check">✔</span> Program 6 Kelas/Hari X 75 Menit</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Tab: Students (Admin Data) -->
                        <div id="tab-students" class="tab-pane">
                            <!-- New Registrations Section -->
                            <div class="db-card" style="margin-bottom: 40px; border-color: #dbeafe; background: #fdfdff;">
                                <div style="padding: 25px 30px; border-bottom: 1px solid #dbeafe; display: flex; justify-content: space-between; align-items: center;">
                                    <h4 style="margin:0; color: #1e40af; font-weight: 800; display: flex; align-items: center; gap: 12px;">
                                        <span style="font-size: 1.5rem;">🆕</span> Registrasi Portal Baru
                                    </h4>
                                    <span class="badge" style="background: #3b82f6; color: white;">{{ $newRegistrations->where('status', 'pending')->count() }} Menunggu Verifikasi</span>
                                </div>
                                <div style="overflow-x: auto;">
                                    <table class="db-table">
                                        <thead>
                                            <tr>
                                                <th>Nama Pendaftar</th>
                                                <th>Program Pilihan</th>
                                                <th>Waktu Daftar</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($newRegistrations->take(5) as $reg)
                                            <tr>
                                                <td>
                                                    <div style="font-weight: 800; color: #1e293b;">{{ $reg->user->name ?? 'User Unknown' }}</div>
                                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $reg->user->email ?? '-' }}</div>
                                                </td>
                                                <td><span class="badge badge-blue">{{ $reg->course->name ?? 'N/A' }}</span></td>
                                                <td style="color: #64748b; font-size: 0.85rem;">{{ $reg->created_at->format('d/m/Y H:i') }}</td>
                                                <td><span class="badge {{ $reg->status == 'verified' ? 'badge-green' : 'badge-blue' }}">{{ strtoupper($reg->status) }}</span></td>
                                                <td><button class="badge" style="background: #1e293b; color: white; cursor: pointer; border: none;">Detail →</button></td>
                                            </tr>
                                            @empty
                                            <tr><td colspan="5" style="text-align:center; padding: 40px; color: #94a3b8;">Belum ada pendaftar baru yang masuk.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Master Student Data Section (Legacy) -->
                            <div class="db-card">
                                <div style="padding: 25px 30px; border-bottom: 1px solid #e2e8f0; background: #fff;">
                                    <h4 style="margin:0; color: #1e293b; font-weight: 800;">Master Data Siswa (Legacy DB)</h4>
                                </div>
                                <div style="overflow-x: auto;">
                                    <table class="db-table">
                                        <thead>
                                            <tr>
                                                <th>Nama & Kontak</th>
                                                <th>Kursus</th>
                                                <th>Email</th>
                                                <th>Tanggal Registrasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($students->take(10) as $student)
                                            <tr>
                                                <td>
                                                    <div style="font-weight: 700; color: #1e293b;">{{ $student->fullname }}</div>
                                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $student->contact_number ?? '-' }}</div>
                                                </td>
                                                <td><span class="badge badge-green">{{ $student->course->name ?? 'N/A' }}</span></td>
                                                <td style="color: #64748b;">{{ $student->email ?? '-' }}</td>
                                                <td style="color: #64748b; font-size: 0.85rem;">{{ $student->created_at->format('d M Y') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Payments -->
                        <div id="tab-payments" class="tab-pane">
                            <div class="stats-grid" style="margin-bottom: 30px;">
                                <div class="stat-card" style="background: linear-gradient(to right, #ffffff, #f0fdf4);">
                                    <div class="stat-icon" style="background: #10b981; color: white;">💰</div>
                                    <div>
                                        <small>Total Diterima</small>
                                        <h3>Rp {{ number_format($payments->where('status', 'success')->sum('amount'), 0, ',', '.') }}</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="db-card">
                                <div style="padding: 25px 30px; border-bottom: 1px solid #e2e8f0;">
                                    <h4 style="margin:0; font-weight: 800; color: #1e293b;">Log Transaksi Finansial</h4>
                                </div>
                                <div style="overflow-x: auto;">
                                    <table class="db-table">
                                        <thead>
                                            <tr>
                                                <th>Penyetor</th>
                                                <th>Keterangan</th>
                                                <th>Jumlah</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($payments->take(15) as $pay)
                                            <tr>
                                                <td style="font-weight: 700; color: #1e293b;">{{ $pay->student_name ?? 'N/A' }}</td>
                                                <td style="color: #64748b; font-size: 0.85rem;">Pembayaran Kursus - ID #{{ $pay->id }}</td>
                                                <td style="font-weight: 800; color: #10b981;">Rp {{ number_format($pay->amount ?? $pay->total ?? 0, 0, ',', '.') }}</td>
                                                <td><span class="badge {{ $pay->status == 'success' ? 'badge-green' : 'badge-blue' }}">{{ strtoupper($pay->status) }}</span></td>
                                                <td><button class="badge badge-blue" style="cursor: pointer; border: none;">Kwitansi</button></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
 
                         <!-- Tab: Status (Student Registration Progress) -->
                         <div id="tab-status" class="tab-pane">
                             @php
                                 // Mengambil pendaftaran terakhir sebagai simulasi data siswa yang sedang login
                                 $myReg = $newRegistrations->first();
                             @endphp
 
                             @if($myReg)
                             <div class="db-card" style="padding: 30px;">
                                 <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px;">
                                     <div>
                                         <h3 style="margin: 0; color: #1e293b; font-weight: 800;">Status Pendaftaran Anda</h3>
                                         <p style="color: #64748b; font-size: 0.9rem;">ID Registrasi: #{{ $myReg->id }}</p>
                                     </div>
                                     <span class="badge {{ $myReg->status == 'completed' ? 'badge-green' : 'badge-blue' }}" style="padding: 10px 20px; font-size: 0.9rem;">
                                         {{ strtoupper($myReg->status) }}
                                     </span>
                                 </div>
 
                                 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; background: #f8fafc; padding: 20px; border-radius: 16px;">
                                     <div>
                                         <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Program Pilihan</div>
                                         <div style="font-weight: 700; color: #1e293b;">{{ $myReg->course->name }}</div>
                                     </div>
                                     <div>
                                         <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Periode</div>
                                         <div style="font-weight: 700; color: #1e293b;">{{ $myReg->period->name }}</div>
                                     </div>
                                 </div>
 
                                 <h4 style="font-size: 1.1rem; font-weight: 800; color: #1e293b; margin-bottom: 15px;">💬 Feedback Admin</h4>
                                 <div style="display: flex; flex-direction: column; gap: 12px; max-height: 300px; overflow-y: auto; padding-right: 10px;">
                                     @forelse($myReg->comments as $comment)
                                     <div style="padding: 15px; border-radius: 14px; border: 1px solid {{ $comment->user->role == 'admin' ? '#dbeafe' : '#e2e8f0' }}; background: {{ $comment->user->role == 'admin' ? '#f0f7ff' : 'white' }};">
                                         <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                             <span style="font-weight: 800; font-size: 0.8rem; color: {{ $comment->user->role == 'admin' ? '#2563eb' : '#64748b' }};">
                                                 {{ $comment->user->name }} {{ $comment->user->role == 'admin' ? '(Admin)' : '' }}
                                             </span>
                                             <span style="font-size: 0.7rem; color: #94a3b8;">{{ $comment->created_at->diffForHumans() }}</span>
                                         </div>
                                         <p style="margin: 0; font-size: 0.9rem; color: #334155; line-height: 1.5;">{{ $comment->comment }}</p>
                                     </div>
                                     @empty
                                     <div style="text-align: center; padding: 30px; color: #94a3b8; background: #f8fafc; border-radius: 12px;">
                                         Belum ada feedback dari admin.
                                     </div>
                                     @endforelse
                                 </div>
                             </div>
                             @else
                             <div class="db-card" style="padding: 50px; text-align: center;">
                                 <div style="font-size: 3rem; margin-bottom: 20px;">📋</div>
                                 <h3 style="color: #1e293b; font-weight: 800;">Belum Ada Pendaftaran</h3>
                                 <p style="color: #64748b;">Silakan pilih program kursus terlebih dahulu untuk melihat status.</p>
                                 <button class="btn-portal-primary" style="width: auto; margin-top: 20px;" onclick="switchRegTab('tab-courses')">LIHAT PROGRAM</button>
                             </div>
                             @endif
                         </div>
 
                         <!-- Add other panes as needed similar to above -->
                        <div id="tab-periods" class="tab-pane">
                             <div class="db-card">
                                <div style="padding: 25px 30px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between;">
                                    <h4 style="margin:0; font-weight: 800;">Kelola Periode Belajar</h4>
                                    <button class="btn-portal-primary" style="width: auto; padding: 10px 20px; font-size: 0.8rem;">+ Periode Baru</button>
                                </div>
                                <table class="db-table">
                                    <thead><tr><th>Nama Periode</th><th>Mulai Belajar</th><th>Estimasi Selesai</th><th>Aksi</th></tr></thead>
                                    <tbody>
                                        @foreach($periods as $p)
                                        <tr>
                                            <td style="font-weight: 700; color: #1e293b;">Periode {{ \Carbon\Carbon::parse($p->date ?? $p->start_date)->format('F Y') }}</td>
                                            <td style="color: #64748b;">{{ \Carbon\Carbon::parse($p->date ?? $p->start_date)->format('d M Y') }}</td>
                                            <td style="color: #94a3b8;">{{ \Carbon\Carbon::parse($p->date ?? $p->start_date)->addMonths(1)->format('d M Y') }}</td>
                                            <td><button class="badge badge-blue" style="cursor: pointer; border: none;">Ubah</button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="tab-banks" class="tab-pane">
                             <div class="db-card">
                                <div style="padding: 25px 30px; border-bottom: 1px solid #e2e8f0;">
                                    <h4 style="margin:0; font-weight: 800;">Rekening Pembayaran Kursus</h4>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; padding: 30px;">
                                    @foreach($banks as $b)
                                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 25px; border-radius: 20px;">
                                        <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 5px;">{{ strtoupper($b->name) }}</div>
                                        <div style="font-size: 1.25rem; font-family: monospace; letter-spacing: 2px; color: #3b82f6; margin-bottom: 15px;">{{ $b->number }}</div>
                                        <div style="font-size: 0.85rem; color: #64748b; font-weight: 700; text-transform: uppercase;">A/N {{ $b->owner }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Review (Admin) -->
                        <div id="tab-review" class="tab-pane">
                            <div style="margin-bottom: 25px;">
                                <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">Review & Penilaian Dokumen</h2>
                                <p style="color: #64748b; margin-top: 6px;">Tinjau dan verifikasi data pendaftaran calon siswa.</p>
                            </div>
                            <div class="db-card">
                                <div style="padding: 25px 30px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                                    <h4 style="margin:0; font-weight: 800; color: #1e293b;">Daftar Pendaftaran Masuk</h4>
                                    <span class="badge badge-blue">{{ $newRegistrations->where('status', 'pending')->count() }} Menunggu</span>
                                </div>
                                <div style="overflow-x: auto;">
                                    <table class="db-table">
                                        <thead>
                                            <tr>
                                                <th>Nama Pendaftar</th>
                                                <th>Email</th>
                                                <th>Program</th>
                                                <th>Periode</th>
                                                <th>Tgl Daftar</th>
                                                <th>Status Bayar</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($newRegistrations as $reg)
                                            <tr>
                                                <td style="font-weight: 700; color: #1e293b;">{{ $reg->user->name ?? 'N/A' }}</td>
                                                <td style="color: #64748b; font-size: 0.85rem;">{{ $reg->user->email ?? '-' }}</td>
                                                <td><span class="badge badge-blue">{{ $reg->course->name ?? 'N/A' }}</span></td>
                                                <td style="color: #64748b; font-size: 0.85rem;">{{ $reg->period->name ?? '-' }}</td>
                                                <td style="color: #64748b; font-size: 0.85rem;">{{ $reg->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <span class="badge {{ $reg->payment_status == 'paid' ? 'badge-green' : 'badge-blue' }}">
                                                        {{ strtoupper($reg->payment_status ?? 'unpaid') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $reg->status == 'verified' ? 'badge-green' : ($reg->status == 'rejected' ? '' : 'badge-blue') }}"
                                                          style="{{ $reg->status == 'rejected' ? 'background:#ef4444;color:white;' : '' }}">
                                                        {{ strtoupper($reg->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr><td colspan="7" style="text-align:center; padding:40px; color:#94a3b8;">Belum ada pendaftaran masuk.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Profile (Student) -->
                        <div id="tab-profile" class="tab-pane">
                            <div style="margin-bottom: 25px;">
                                <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">Profil Pendaftaran</h2>
                                <p style="color: #64748b; margin-top: 6px;">Informasi akun Anda yang terdaftar di BEC.</p>
                            </div>
                            @auth
                            <div class="db-card" style="max-width: 600px;">
                                <div style="padding: 30px; display: flex; flex-direction: column; gap: 18px;">
                                    <div style="display: flex; gap: 16px; align-items: center; padding-bottom: 20px; border-bottom: 1px solid #e2e8f0;">
                                        <div style="width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, #003399, #4f46e5); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.75rem; font-weight: 800;">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-size: 1.25rem; font-weight: 800; color: #1e293b;">{{ auth()->user()->name }}</div>
                                            <div style="font-size: 0.85rem; color: #64748b;">{{ ucfirst(auth()->user()->role ?? 'student') }}</div>
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 140px 1fr; gap: 12px; align-items: center;">
                                        <span style="color: #64748b; font-size: 0.875rem; font-weight: 600;">Email</span>
                                        <span style="color: #1e293b; font-weight: 700;">{{ auth()->user()->email }}</span>
                                        <span style="color: #64748b; font-size: 0.875rem; font-weight: 600;">No. HP</span>
                                        <span style="color: #1e293b; font-weight: 700;">{{ auth()->user()->studentDetail->phone ?? '-' }}</span>
                                        <span style="color: #64748b; font-size: 0.875rem; font-weight: 600;">Status Akun</span>
                                        <span class="badge badge-green">{{ strtoupper(auth()->user()->Status ?? 'Aktif') }}</span>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div style="text-align: center; padding: 60px 20px; color: #94a3b8;">
                                <p style="font-size: 1.1rem;">Silakan masuk terlebih dahulu untuk melihat profil.</p>
                            </div>
                            @endauth
                        </div>

                        <!-- Tab: Registration Form (Student Pilih Program) -->
                        <div id="tab-registration" class="tab-pane">
                            <div style="margin-bottom: 25px;">
                                <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b;">Formulir Pendaftaran</h2>
                                <p style="color: #64748b; margin-top: 6px;">Isi data diri dan pilih program kursus BEC.</p>
                            </div>
                            <div class="db-card" style="max-width: 680px;">
                                <div id="form-reg-success" style="display:none; padding: 30px; text-align: center;">
                                    <div style="font-size: 3rem;">✅</div>
                                    <h3 style="color: #1e293b; margin: 16px 0 8px;">Pendaftaran Berhasil!</h3>
                                    <p style="color: #64748b; margin-bottom: 24px;">Akun kamu telah dibuat. Lanjutkan ke halaman pembayaran.</p>
                                    <a id="btn-ke-checkout" href="{{ route('checkout.index') }}" style="display:inline-block; padding: 14px 40px; background: linear-gradient(135deg, #003399, #4f46e5); color: white; border-radius: 12px; font-weight: 800; text-decoration: none;">LANJUT KE PEMBAYARAN →</a>
                                </div>
                                <form id="form-registrasi" action="{{ route('register.pos.process') }}" method="POST" style="padding: 30px; display: flex; flex-direction: column; gap: 20px;">
                                    @csrf

                                    {{-- === DATA DIRI === --}}
                                    <div style="border-bottom: 1.5px solid #e2e8f0; padding-bottom: 8px;">
                                        <span style="font-size: 0.8rem; font-weight: 700; color: #4f46e5; text-transform: uppercase; letter-spacing: 1px;">Data Diri</span>
                                    </div>

                                    <div>
                                        <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Nama Lengkap <span style="color:#dc2626">*</span></label>
                                        <input type="text" name="name" value="{{ auth()->user()->name ?? '' }}" required
                                            style="width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; outline: none; font-family: inherit; box-sizing: border-box;"
                                            placeholder="Nama lengkap sesuai KTP">
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                        <div>
                                            <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Email <span style="color:#dc2626">*</span></label>
                                            <input type="email" name="email" value="{{ auth()->user()->email ?? '' }}" required
                                                style="width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; outline: none; font-family: inherit; box-sizing: border-box;"
                                                placeholder="email@contoh.com">
                                        </div>
                                        <div>
                                            <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px;">No. HP / WhatsApp <span style="color:#dc2626">*</span></label>
                                            <input type="text" name="phone" required
                                                style="width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; outline: none; font-family: inherit; box-sizing: border-box;"
                                                placeholder="08xxxxxxxxxx">
                                        </div>
                                    </div>

                                    <div>
                                        <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px;">No. HP Wali / Orang Tua</label>
                                        <input type="text" name="guardian_phone"
                                            style="width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; outline: none; font-family: inherit; box-sizing: border-box;"
                                            placeholder="08xxxxxxxxxx">
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                        <div>
                                            <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Jenis Kelamin</label>
                                            <select name="gender"
                                                style="width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; outline: none; font-family: inherit; background: white; box-sizing: border-box;">
                                                <option value="">-- Pilih --</option>
                                                <option value="Laki-Laki">Laki-Laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Ukuran Seragam</label>
                                            <select name="uniform_size"
                                                style="width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; outline: none; font-family: inherit; background: white; box-sizing: border-box;">
                                                <option value="">-- Pilih Ukuran --</option>
                                                <option value="XS">XS</option>
                                                <option value="S">S</option>
                                                <option value="M">M</option>
                                                <option value="L">L</option>
                                                <option value="XL">XL</option>
                                                <option value="XXL">XXL</option>
                                                <option value="XXXL">XXXL</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                        <div>
                                            <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Tempat Lahir</label>
                                            <input type="text" name="birth_place"
                                                style="width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; outline: none; font-family: inherit; box-sizing: border-box;"
                                                placeholder="Kota tempat lahir">
                                        </div>
                                        <div>
                                            <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Tanggal Lahir</label>
                                            <input type="date" name="birth_date"
                                                style="width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; outline: none; font-family: inherit; box-sizing: border-box;">
                                        </div>
                                    </div>

                                    <div>
                                        <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Alamat Lengkap</label>
                                        <input type="text" name="address"
                                            style="width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; outline: none; font-family: inherit; box-sizing: border-box;"
                                            placeholder="Alamat domisili saat ini">
                                    </div>

                                    {{-- === PILIH KURSUS === --}}
                                    <div style="border-bottom: 1.5px solid #e2e8f0; padding-bottom: 8px; margin-top: 4px;">
                                        <span style="font-size: 0.8rem; font-weight: 700; color: #4f46e5; text-transform: uppercase; letter-spacing: 1px;">Pilih Kursus</span>
                                    </div>

                                    {{-- Filter Bahasa --}}
                                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                        @foreach(['Semua', 'Inggris', 'Jerman', 'Mandarin', 'Arab'] as $lang)
                                        <button type="button" onclick="filterCoursePOS('{{ $lang }}')"
                                            id="btn-lang-pos-{{ $lang }}"
                                            style="padding: 6px 14px; border-radius: 20px; border: 1.5px solid {{ $lang === 'Semua' ? '#4f46e5' : '#e2e8f0' }}; background: {{ $lang === 'Semua' ? '#4f46e5' : 'white' }}; color: {{ $lang === 'Semua' ? 'white' : '#64748b' }}; font-size: 0.82rem; font-weight: 600; cursor: pointer;">
                                            {{ $lang === 'Inggris' ? '🇬🇧' : ($lang === 'Jerman' ? '🇩🇪' : ($lang === 'Mandarin' ? '🇨🇳' : ($lang === 'Arab' ? '🕌' : '🌐'))) }} {{ $lang }}
                                        </button>
                                        @endforeach
                                    </div>

                                    <div>
                                        <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Program Kursus <span style="color:#dc2626">*</span></label>
                                        <select name="course_id" id="select-course-pos" required
                                            style="width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; outline: none; font-family: inherit; background: white; box-sizing: border-box;">
                                            <option value="">-- Pilih Program --</option>
                                            @foreach($courses->groupBy('language') as $lang => $langCourses)
                                            <optgroup label="{{ $lang }}" data-lang="{{ $lang }}">
                                                @foreach($langCourses as $course)
                                                <option value="{{ $course->id }}" data-language="{{ $course->language }}">
                                                    [{{ $course->language }}] {{ $course->name }}{{ $course->duration ? ' · '.$course->duration : '' }} — Rp {{ number_format($course->price, 0, ',', '.') }}
                                                </option>
                                                @endforeach
                                            </optgroup>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                        <div>
                                            <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Periode Belajar <span style="color:#dc2626">*</span></label>
                                            <select name="period_id" required
                                                style="width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; outline: none; font-family: inherit; background: white; box-sizing: border-box;">
                                                <option value="">-- Pilih Periode --</option>
                                                @foreach($periods as $period)
                                                <option value="{{ $period->id }}">{{ $period->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Transport <span style="color:#dc2626">*</span></label>
                                            <select name="transport_id" required
                                                style="width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; outline: none; font-family: inherit; background: white; box-sizing: border-box;">
                                                <option value="">-- Pilih Transport --</option>
                                                @foreach($transports as $transport)
                                                <option value="{{ $transport->id }}">{{ $transport->name }} — Rp {{ number_format($transport->price, 0, ',', '.') }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- === TAMBAHAN LAYANAN === --}}
                                    <div style="border-bottom: 1.5px solid #e2e8f0; padding-bottom: 8px; margin-top: 4px;">
                                        <span style="font-size: 0.8rem; font-weight: 700; color: #4f46e5; text-transform: uppercase; letter-spacing: 1px;">Tambahan Layanan</span>
                                    </div>
                                    @if(isset($additionalServices) && $additionalServices->isNotEmpty())
                                    <div>
                                        <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Pilih Layanan Tambahan <span style="font-size:0.8rem; font-weight:400; color:#94a3b8;">(Opsional)</span></label>
                                        <select name="additional_service_id"
                                            style="width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; outline: none; font-family: inherit; background: white; box-sizing: border-box;">
                                            <option value="">-- Tidak Ada --</option>
                                            @foreach($additionalServices as $service)
                                            <option value="{{ $service->id }}">
                                                {{ $service->name }}{{ $service->price > 0 ? ' (+Rp '.number_format($service->price, 0, ',', '.').')' : ' (Gratis)' }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @else
                                    <p style="color:#94a3b8; font-size:0.85rem; margin:0;">Belum ada layanan tambahan tersedia.</p>
                                    <input type="hidden" name="additional_service_id" value="">
                                    @endif

                                    <button type="submit"
                                        style="width: 100%; padding: 14px; background: linear-gradient(135deg, #003399, #4f46e5); color: white; border: none; border-radius: 12px; font-size: 1rem; font-weight: 800; cursor: pointer; letter-spacing: 0.5px;" id="btn-submit-daftar">
                                        DAFTAR SEKARANG →
                                    </button>
                                    <div id="form-reg-error" style="display:none; padding: 12px 16px; background: #fee2e2; border-radius: 8px; color: #dc2626; font-size: 0.9rem;"></div>
                                </form>
                            </div>
                        </div>

                    </div> <!-- end db-body -->
                </div> <!-- end db-content -->
            </div> <!-- end portal interface -->
        </div> <!-- end registration box -->
    </div> <!-- end registration modal overlay -->

    <script>
        function filterCoursePOS(lang) {
            const select = document.getElementById('select-course-pos');
            if (!select) return;
            const optgroups = select.querySelectorAll('optgroup');
            optgroups.forEach(function(og) {
                og.style.display = (lang === 'Semua' || og.getAttribute('data-lang') === lang) ? '' : 'none';
            });
            // Reset selection if current option is hidden
            const selected = select.options[select.selectedIndex];
            if (selected && selected.parentElement && selected.parentElement.style.display === 'none') {
                select.value = '';
            }
            // Update button styles
            ['Semua','Inggris','Jerman','Mandarin','Arab'].forEach(function(l) {
                const btn = document.getElementById('btn-lang-pos-' + l);
                if (!btn) return;
                if (l === lang) {
                    btn.style.background = '#4f46e5';
                    btn.style.color = 'white';
                    btn.style.borderColor = '#4f46e5';
                } else {
                    btn.style.background = 'white';
                    btn.style.color = '#64748b';
                    btn.style.borderColor = '#e2e8f0';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const formReg = document.getElementById('form-registrasi');
            if (formReg) {
                formReg.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const btn = document.getElementById('btn-submit-daftar');
                    const errBox = document.getElementById('form-reg-error');
                    btn.disabled = true;
                    btn.textContent = 'Memproses...';
                    errBox.style.display = 'none';

                    const formData = new FormData(formReg);
                    fetch(formReg.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            formReg.style.display = 'none';
                            document.getElementById('form-reg-success').style.display = 'block';
                            if (data.redirect) {
                                document.getElementById('btn-ke-checkout').href = data.redirect;
                            }
                        } else {
                            errBox.textContent = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                            errBox.style.display = 'block';
                            btn.disabled = false;
                            btn.textContent = 'DAFTAR SEKARANG →';
                        }
                    })
                    .catch(err => {
                        errBox.textContent = 'Koneksi gagal. Silakan coba lagi.';
                        errBox.style.display = 'block';
                        btn.disabled = false;
                        btn.textContent = 'DAFTAR SEKARANG →';
                    });
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/marzipano@0.10.2/dist/marzipano.js"></script>
    <script>
        window.closeAllModals = function() {
            // Cancel Voice
            if ('speechSynthesis' in window) window.speechSynthesis.cancel();
            
            // Stop AR Scanning Camera if active
            if (window.arCameraStream) {
                window.arCameraStream.getTracks().forEach(track => track.stop());
                window.arCameraStream = null;
            }

            // Close AR
            const arModal = document.getElementById('ar-modal-overlay');
            if (arModal) {
                arModal.classList.remove('active');
                setTimeout(() => { 
                    if (arModal) {
                        arModal.style.display = 'none';
                        // Reset to default building model
                        const modalViewer = document.getElementById('ar-modal-viewer');
                        if (modalViewer) {
                            modalViewer.src = "{{ asset('AR/model/bangunan3d.glb') }}";
                        }
                        // Reset title and scanning text
                        const modalTitle = document.getElementById('ar-modal-title');
                        if (modalTitle) {
                            modalTitle.textContent = "AR BEC - Bangunan 3D";
                        }
                        const scanningText = document.querySelector('.scanning-text');
                        const scanningSubtext = document.querySelector('.scanning-subtext');
                        if (scanningText) scanningText.textContent = "Mencari Lantai...";
                        if (scanningSubtext) scanningSubtext.textContent = "Arahkan kamera ke lantai yang datar untuk menempatkan gedung 3D";
                    }
                }, 400);
            }
            
            // Close Tutorial
            const tutorialOverlay = document.getElementById('tutorial-modal-overlay');
            const tutorialVideoEl = document.getElementById('tutorial-video');
            if (tutorialOverlay) tutorialOverlay.classList.remove('active');
            if (tutorialVideoEl) {
                tutorialVideoEl.pause();
                tutorialVideoEl.currentTime = 0;
            }
            
            // Close Registration (Real DOM)
            const regReal = document.getElementById('register-dom-overlay');
            if (regReal) {
                regReal.classList.remove('active');
                regReal.style.display = 'none';
            }

            // Close Simulated Registration
            const regSim = document.getElementById('registration-modal-overlay');
            if (regSim) regSim.classList.remove('active');
        };

        window.openRegisterIframe = function() {
            var overlay = document.getElementById('register-dom-overlay');
            if (overlay) {
                overlay.style.display = 'flex';
            }
            try {
                var audioEl = document.getElementById('global-bg-audio');
                if (audioEl && !audioEl.paused) audioEl.pause();
            } catch(e) {}
        };

        window.closeRegisterDOM = function() {
            var overlay = document.getElementById('register-dom-overlay');
            if (overlay) overlay.style.display = 'none';
        };

        // Note: validation errors are now handled via AJAX inline in the modal
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Audio & Onboarding Modal Elements
            const overlay = document.getElementById('audio-modal-overlay');
            const modalAudio = document.getElementById('modal-audio');
            const modalWelcome = document.getElementById('modal-welcome');
            const modalCamera = document.getElementById('modal-camera');
            const modalLoading = document.getElementById('modal-loading');
            const modalDenied = document.getElementById('modal-denied');
            const modalEntering = document.getElementById('modal-entering');
            const modalVideo = document.getElementById('modal-video');
            
            const btnYes = document.getElementById('btn-yes');
            const btnNo = document.getElementById('btn-no');
            const btnStart = document.getElementById('btn-start');
            const btnAllow = document.getElementById('btn-allow');
            const btnDeny = document.getElementById('btn-deny');
            const btnBack = document.getElementById('btn-back');
            const droneVideo = document.getElementById('drone-video');

            const audio = document.getElementById('global-bg-audio');
            const muteToggle = document.getElementById('global-mute-toggle');

            // Onboarding Steps
            const showWelcome = () => {
                modalAudio.style.display = 'none';
                modalWelcome.style.display = 'block';
            };
            const showCamera = () => {
                modalWelcome.style.display = 'none';
                modalCamera.style.display = 'block';
            };
            const showLoading = () => {
                modalCamera.style.display = 'none';
                modalLoading.style.display = 'block';
            };
            const showEnteringArea = () => {
                modalLoading.style.display = 'none';
                modalEntering.style.display = 'flex';
                setTimeout(() => { showDroneVideo(); }, 3000);
            };
            const showDroneVideo = () => {
                modalEntering.style.display = 'none';
                modalVideo.style.display = 'flex';
                droneVideo.play().catch(e => console.log("Auto-play blocked:", e));
                droneVideo.onended = () => { startTour(); };
            };
            const showDenied = () => {
                modalCamera.style.display = 'none';
                modalDenied.style.display = 'block';
            };
            const resetOnboarding = () => {
                modalDenied.style.display = 'none';
                modalAudio.style.display = 'block';
                audio.pause();
                audio.currentTime = 0;
            };
            const startTour = () => {
                droneVideo.pause();
                modalVideo.style.transition = 'opacity 1s ease';
                modalVideo.style.opacity = '0';
                overlay.style.transition = 'opacity 1s ease';
                overlay.style.opacity = '0';
                setTimeout(() => {
                    overlay.style.display = 'none';
                    modalVideo.style.display = 'none';
                    document.getElementById('tour-ui').style.display = 'block';
                    if (muteToggle) muteToggle.style.display = 'flex';
                    if (window.startAutorotate) window.startAutorotate();
                }, 1000);
            };

            // Audio Logic
            // Audio UI sync is handled by global component
            const syncAudioUI = () => {};

            // Onboarding Click Handlers
            btnYes.onclick = () => {
                audio.play().catch(e => console.log('Autoplay blocked:', e));
                showWelcome();
            };
            btnNo.onclick = () => {
                showWelcome();
            };
            btnStart.onclick = () => showCamera();
            btnAllow.onclick = () => {
                showLoading();
                setTimeout(() => {
                    showEnteringArea(); 
                    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                        navigator.mediaDevices.getUserMedia({ video: true })
                            .then((stream) => { stream.getTracks().forEach(track => track.stop()); })
                            .catch((err) => { console.error("Camera access denied:", err); });
                    }
                }, 2000);
            };
            btnDeny.onclick = () => showDenied();
            btnBack.onclick = () => resetOnboarding();

            // Room Data
            const rooms = [
                { id: "ruang-1", name: "Ruang 1", image: "1.jpg" },
                { id: "ruang-2", name: "Ruang 2", image: "2.jpg" },
                { id: "ruang-3", name: "Ruang 3", image: "3.jpg" },
                { id: "ruang-4a", name: "Ruang 4A", image: "4a.jpg" },
                { id: "ruang-4b", name: "Ruang 4B", image: "4b.jpg" },
                { id: "ruang-5", name: "Ruang 5", image: "5.jpg" },
                { id: "ruang-5a", name: "Ruang 5A", image: "5_a.jpg" },
                { id: "ruang-5b", name: "Ruang 5B", image: "5b.jpg" },
                { id: "ruang-5c", name: "Ruang 5C", image: "5c.jpg" },
                { id: "ruang-5d", name: "Ruang 5D", image: "5d.jpg" },
                { id: "ruang-5e", name: "Ruang 5E", image: "5e.jpg" },
                { id: "ruang-5f", name: "Ruang 5F", image: "5f.jpg" },
                { id: "ruang-5g", name: "Ruang 5G", image: "5g.jpg" },
                { id: "ruang-6", name: "Ruang 6", image: "6.jpg" },
                { id: "ruang-6a", name: "Ruang 6A", image: "6a.jpg" },
                { id: "vip-dalam", name: "VIP Dalam", image: "Tampilan Dalam VIP.jpg" },
                { id: "ruang-7", name: "Ruang 7", image: "7.jpg" },
                { id: "ruang-7a", name: "Ruang 7A", image: "7a.jpg" },
                { id: "ruang-7b", name: "Ruang 7B", image: "7b.jpg" },
                { id: "ruang-8", name: "Ruang 8", image: "8.jpg" },
                { id: "ruang-9", name: "Ruang 9", image: "9.jpg" },
                { id: "ruang-11", name: "Ruang 11", image: "11.jpg" },
                { id: "ruang-11a", name: "Ruang 11A", image: "11a.jpg" },
                { id: "ruang-11b", name: "Ruang 11B", image: "11b.jpg" },
                { id: "ruang-12", name: "Ruang 12", image: "12.jpg" },
                { id: "ruang-12a", name: "Ruang 12A", image: "12 a ( malam hari ).png" },
                { id: "ruang-13", name: "Ruang 13", image: "13.jpg" },
                { id: "ruang-14", name: "Ruang 14", image: "14.jpg" },
                { id: "camp-reguler", name: "Camp Reguler", image: "Camp Reguler ( malam hari).png" },
                { id: "luar-homestay", name: "Luar Homestay", image: "Tampilan Luar Homestay ( malam hari).png" },
                { id: "dalam-homestay", name: "Dalam Homestay", image: "Tampilan Dalam Homestay.jpg" },
                { id: "luar-vip-putra", name: "Luar VIP Putra", image: "Tampilan Luar VIP Putra ( malam hari).png" },
                { id: "luar-vip-putri", name: "Luar VIP Putri", image: "Tampilan Luar VIP Putri.jpg" },
                { id: "ruang-2a-malam", name: "Ruang 2A", image: "2 a malam.png" }
            ];

            const hotspotsData = {
                "ruang-1": [ { target: "ruang-2", label: "Menuju Ruang 2", yaw: 0.5, pitch: 0 } ],
                "ruang-2a-malam": [ { target: "ruang-1", label: "Kembali ke Ruang 1", yaw: -2.5, pitch: 0 }, { target: "ruang-2", label: "Masuk ke Ruang 2", yaw: 0.5, pitch: 0 } ],
                "ruang-2": [ { target: "ruang-2a-malam", label: "Kembali ke Ruang 2A", yaw: -2.5, pitch: 0 }, { target: "ruang-3", label: "Menuju Ruang 3", yaw: 0.5, pitch: 0 } ],
                "ruang-3": [ { target: "ruang-2", label: "Kembali ke Ruang 2", yaw: -2.5, pitch: 0 }, { target: "ruang-4a", label: "Menuju Ruang 4A", yaw: 0.5, pitch: 0 } ],
                "ruang-4a": [ { target: "ruang-3", label: "Kembali ke Ruang 3", yaw: -2.5, pitch: 0 }, { target: "ruang-4b", label: "Menuju Ruang 4B", yaw: 0.5, pitch: 0 } ],
                "ruang-4b": [ { target: "ruang-4a", label: "Kembali ke Ruang 4A", yaw: -2.5, pitch: 0 }, { target: "ruang-5", label: "Menuju Ruang 5", yaw: 0.5, pitch: 0 } ],
                "ruang-5": [ { target: "ruang-4b", label: "Kembali ke Ruang 4B", yaw: -2.5, pitch: 0 }, { target: "ruang-5a", label: "Menuju Ruang 5A", yaw: 0.5, pitch: 0 } ],
                "ruang-5a": [ { target: "ruang-5", label: "Kembali ke Ruang 5", yaw: -2.5, pitch: 0 }, { target: "ruang-5b", label: "Menuju Ruang 5B", yaw: 0.5, pitch: 0 } ],
                "ruang-5b": [ { target: "ruang-5a", label: "Kembali ke Ruang 5A", yaw: -2.5, pitch: 0 }, { target: "ruang-5c", label: "Menuju Ruang 5C", yaw: 0.5, pitch: 0 } ],
                "ruang-5c": [ { target: "ruang-5b", label: "Kembali ke Ruang 5B", yaw: -2.5, pitch: 0 }, { target: "ruang-5d", label: "Menuju Ruang 5D", yaw: 0.5, pitch: 0 } ],
                "ruang-5d": [ { target: "ruang-5c", label: "Kembali ke Ruang 5C", yaw: -2.5, pitch: 0 }, { target: "ruang-5e", label: "Menuju Ruang 5E", yaw: 0.5, pitch: 0 } ],
                "ruang-5e": [ { target: "ruang-5d", label: "Kembali ke Ruang 5D", yaw: -2.5, pitch: 0 }, { target: "ruang-5f", label: "Menuju Ruang 5F", yaw: 0.5, pitch: 0 } ],
                "ruang-5f": [ { target: "ruang-5e", label: "Kembali ke Ruang 5E", yaw: -2.5, pitch: 0 }, { target: "ruang-5g", label: "Menuju Ruang 5G", yaw: 0.5, pitch: 0 } ],
                "ruang-5g": [ { target: "ruang-5f", label: "Kembali ke Ruang 5F", yaw: -2.5, pitch: 0 }, { target: "ruang-6", label: "Menuju Ruang 6", yaw: 0.5, pitch: 0 } ],
                "ruang-6": [ { target: "ruang-5g", label: "Kembali ke Ruang 5G", yaw: -2.5, pitch: 0 }, { target: "ruang-6a", label: "Menuju Ruang 6A", yaw: 0.5, pitch: 0 }, { target: "vip-dalam", label: "Masuk VIP", yaw: -2.1, pitch: 0.0 } ],
                "ruang-6a": [ { target: "ruang-6", label: "Kembali ke Ruang 6", yaw: -2.5, pitch: 0 }, { target: "ruang-7", label: "Menuju Ruang 7", yaw: 0.5, pitch: 0 } ],
                "vip-dalam": [ { target: "ruang-6", label: "Kembali ke Ruang 6", yaw: 3.14, pitch: 0 } ],
                "ruang-7": [ { target: "ruang-6a", label: "Kembali ke Ruang 6A", yaw: -2.5, pitch: 0 }, { target: "ruang-7a", label: "Menuju Ruang 7A", yaw: 0.5, pitch: 0 } ],
                "ruang-7a": [ { target: "ruang-7", label: "Kembali ke Ruang 7", yaw: -2.5, pitch: 0 }, { target: "ruang-7b", label: "Menuju Ruang 7B", yaw: 0.5, pitch: 0 } ],
                "ruang-7b": [ { target: "ruang-7a", label: "Kembali ke Ruang 7A", yaw: -2.5, pitch: 0 }, { target: "ruang-8", label: "Menuju Ruang 8", yaw: 0.5, pitch: 0 } ],
                "ruang-8": [ { target: "ruang-7b", label: "Kembali ke Ruang 7B", yaw: -2.5, pitch: 0 }, { target: "ruang-9", label: "Menuju Ruang 9", yaw: 0.5, pitch: 0 } ],
                "ruang-9": [ { target: "ruang-8", label: "Kembali ke Ruang 8", yaw: -2.5, pitch: 0 }, { target: "ruang-11", label: "Menuju Ruang 11", yaw: 0.5, pitch: 0 } ],
                "ruang-11": [ { target: "ruang-9", label: "Kembali ke Ruang 9", yaw: -2.5, pitch: 0 }, { target: "ruang-11a", label: "Menuju Ruang 11A", yaw: 0.5, pitch: 0 } ],
                "ruang-11a": [ { target: "ruang-11", label: "Kembali ke Ruang 11", yaw: -2.5, pitch: 0 }, { target: "ruang-11b", label: "Menuju Ruang 11B", yaw: 0.5, pitch: 0 } ],
                "ruang-11b": [ { target: "ruang-11a", label: "Kembali ke Ruang 11A", yaw: -2.5, pitch: 0 }, { target: "ruang-12", label: "Menuju Ruang 12", yaw: 0.5, pitch: 0 } ],
                "ruang-12": [ { target: "ruang-11b", label: "Kembali ke Ruang 11B", yaw: -2.5, pitch: 0 }, { target: "ruang-12a", label: "Menuju Ruang 12A", yaw: 0.5, pitch: 0 } ],
                "ruang-12a": [ { target: "ruang-12", label: "Kembali ke Ruang 12", yaw: -2.5, pitch: 0 }, { target: "ruang-13", label: "Menuju Ruang 13", yaw: 0.5, pitch: 0 } ],
                "ruang-13": [ { target: "ruang-12a", label: "Kembali ke Ruang 12A", yaw: -2.5, pitch: 0 }, { target: "ruang-14", label: "Menuju Ruang 14", yaw: 0.5, pitch: 0 } ],
                "ruang-14": [ { target: "ruang-13", label: "Kembali ke Ruang 13", yaw: -2.5, pitch: 0 }, { target: "camp-reguler", label: "Menuju Camp Reguler", yaw: 0.5, pitch: 0 } ],
                "camp-reguler": [ { target: "ruang-14", label: "Kembali ke Ruang 14", yaw: -2.5, pitch: 0 }, { target: "ruang-1", label: "Ke Ruang 1", yaw: 0.5, pitch: 0 }, { target: "luar-homestay", label: "Menuju Luar Homestay", yaw: -0.5, pitch: 0 } ],
                "luar-homestay": [ { target: "camp-reguler", label: "Kembali ke Camp Reguler", yaw: 3.14, pitch: 0 }, { target: "dalam-homestay", label: "Masuk Dalam Homestay", yaw: 0.0, pitch: 0.0 } ],
                "dalam-homestay": [ { target: "luar-homestay", label: "Kembali ke Luar Homestay", yaw: -2.5, pitch: 0 }, { target: "luar-vip-putra", label: "Menuju Luar VIP Putra", yaw: 0.5, pitch: 0 } ],
                "luar-vip-putra": [ { target: "dalam-homestay", label: "Kembali ke Dalam Homestay", yaw: -2.5, pitch: 0 }, { target: "luar-vip-putri", label: "Menuju Luar VIP Putri", yaw: 0.5, pitch: 0 } ],
                "luar-vip-putri": [ { target: "luar-vip-putra", label: "Kembali ke Luar VIP Putra", yaw: -2.5, pitch: 0 }, { target: "ruang-1", label: "Ke Ruang 1", yaw: 0.5, pitch: 0 } ]
            };

            const panoElement = document.getElementById('pano');
            const viewer = new Marzipano.Viewer(panoElement, { controls: { mouseViewMode: 'drag' } });
            const scenes = {};
            const dnPairs = {
                "ruang-1": "1 ( siang hari ).jpeg", "ruang-2": "2 ( siang hari ).jpeg", "ruang-3": "3 ( Siang Hari ).png",
                "ruang-4a": "4A (  Siang Hari ).jpeg", "ruang-4b": "4B ( Siang hari ).png",
                "ruang-5": "5 ( Siang Hari ).png", "ruang-5a": "5_a( siang hari ).jpeg", "ruang-5b": "5b ( siang hari ).jpeg",
                "ruang-5c": "5C ( siang hari ).jpeg", "ruang-5d": "5D ( Siang Hari ).png", "ruang-5e": "5E ( Siang Hari ).jpeg", "ruang-5f": "5 f ( siang hari ).jpeg",
                "ruang-5g": "5G ( Siang hari ).png", "ruang-6": "6 ( siang hari ).jpeg", "ruang-6a": "6a ( siang hari ).png",
                "ruang-7": "7 ( Siang Hari ).jpeg", "ruang-7a": "7a ( siang hari  ).jpeg", "ruang-7b": "7b ( siang hari ).jpeg", 
                "ruang-8": "8 ( Siang Hari ).jpeg", "ruang-9": "9 ( Siang hari ).png", "ruang-11": "11 ( siang hari ).png", 
                "ruang-11a": "11 a ( Siang Hari ).png", "ruang-11b": "11 b ( siang hari ).png", "ruang-12": "12 ( siang hari ).jpeg", 
                "ruang-12a": "12a.jpg", "ruang-13": "13 ( siang hari ).png", "ruang-14": "14 ( siang hari ).jpeg",
                "camp-reguler": "Tampilan Camp Reguler.jpg", "ruang-2a-malam": "2 a siang.png",
                "vip-dalam": "Tampilan Dalam VIP ( siang hari ).png",
                "dalam-homestay": "Tampilan Dalam Homestay ( siang hari ).png",
                "luar-vip-putra": "Tampilan Luar VIP Putra.jpg",
                "luar-vip-putri": "Tampilan Luar VIP Putri ( siang hari ).png",
                "luar-homestay": "Tampilan Luar Homestay.jpg"
            };

            rooms.forEach(room => {
                const source = Marzipano.ImageUrlSource.fromString(`{{ asset('assets/foto360') }}/${room.image}`);
                const geometry = new Marzipano.EquirectGeometry([{ width: 4000 }]);
                const limiter = Marzipano.RectilinearView.limit.traditional(2000, 100*Math.PI/180);
                const view = new Marzipano.RectilinearView(null, limiter);
                scenes[room.id] = viewer.createScene({ source, geometry, view, pinFirstLevel: true });
                if (dnPairs[room.id]) {
                    const daySource = Marzipano.ImageUrlSource.fromString(`{{ asset('assets/foto360') }}/${dnPairs[room.id]}`);
                    scenes[room.id + "-day"] = viewer.createScene({ source: daySource, geometry, view: new Marzipano.RectilinearView(null, limiter), pinFirstLevel: true });
                }
            });

            Object.keys(hotspotsData).forEach(roomId => {
                const scene = scenes[roomId];
                if (!scene) return;
                hotspotsData[roomId].forEach(data => {
                    const create = (s) => {
                        const container = document.createElement('div');
                        container.className = 'hotspot';
                        container.innerHTML = '<div class="hotspot-icon"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" /></svg></div><div class="hotspot-label">' + data.label + '</div>';
                        container.onclick = () => {
                        const targetRoomId = data.target.replace('-day', '');
                        const roomNumber = targetRoomId.replace('ruang-', '');
                        
                        // Play audio "menuju ruang X" sebelum pindah ruangan
                        // Mapping nama ruangan ke audio text
                        const roomNames = {
                            '1': 'satu', '2': 'dua', '3': 'tiga', '4a': 'empat A', '4b': 'empat B',
                            '5': 'lima', '5a': 'lima A', '5b': 'lima B', '5c': 'lima C', '5d': 'lima D', '5e': 'lima E', '5f': 'lima F', '5g': 'lima G',
                            '6': 'enam', '6a': 'enam A', 'vip-dalam': 'VIP dalam', '7': 'tujuh', '7a': 'tujuh A', '7b': 'tujuh B',
                            '8': 'delapan', '9': 'sembilan', '11': 'sebelas', '11a': 'sebelas A', '11b': 'sebelas B',
                            '12': 'dua belas', '12a': 'dua belas A', '13': 'tiga belas', '14': 'empat belas',
                            'camp-reguler': 'Camp Reguler', 'luar-homestay': 'Luar Homestay', 'dalam-homestay': 'Dalam Homestay',
                            'luar-vip-putra': 'Luar V I P Putra', 'luar-vip-putri': 'Luar V I P Putri'
                        };
                        
                        const roomNameText = roomNames[roomNumber] || roomNumber;
                        const isKembali = data.label.toLowerCase().includes('kembali');
                        const prefix = isKembali ? 'Kembali ke ruang' : 'Menuju ruang';
                        const audioText = `${prefix} ${roomNameText}`;
                        
                        // Mainkan audio menggunakan speech synthesis
                        if ('speechSynthesis' in window) {
                            const utterance = new SpeechSynthesisUtterance(audioText);
                            utterance.lang = 'id-ID';
                            utterance.rate = 0.9;
                            utterance.pitch = 1.0;
                            utterance.volume = 0.8;
                            
                            // Cari voice bahasa Indonesia
                            const voices = window.speechSynthesis.getVoices();
                            const idVoice = voices.find(voice => voice.lang.includes('id'));
                            if (idVoice) utterance.voice = idVoice;
                            
                            // Pastikan voices sudah dimuat
                            if (voices.length === 0) {
                                window.speechSynthesis.getVoices();
                            }
                            
                            window.speechSynthesis.speak(utterance);
                        }
                        
                        // Pindah ruangan setelah audio dimulai
                        const isRuang2 = data.target === 'ruang-2' || data.target === 'ruang-2-day';
                        switchRoom(data.target, isRuang2);
                    };
                        s.hotspotContainer().createHotspot(container, { yaw: data.yaw, pitch: data.pitch });
                    };
                    create(scene);
                    if (scenes[roomId + "-day"]) create(scenes[roomId + "-day"]);
                });
            });

            // ===== AR Hotspot Button untuk Ruang 2 (depan tulisan Kampung Inggris Pare) =====
            (function() {
                const scene2    = scenes['ruang-2'];
                const scene2day = scenes['ruang-2-day'];

                function createARHotspot(targetScene) {
                    if (!targetScene) return;

                    // 1. The Interaction Button (Hotspot)
                    const container = document.createElement('div');
                    container.className = 'hotspot-ar-wrap';
                    
                    const ripple = document.createElement('div');
                    ripple.className = 'hotspot-ar-ripple';
                    
                    const btn = document.createElement('div');
                    btn.className = 'hotspot-ar-btn';
                    btn.innerHTML = `
                        <svg viewBox="0 0 24 24"><path fill="currentColor" d="M21,16.5C21,16.88 20.79,17.21 20.47,17.38L12.57,21.82C12.41,21.94 12.21,22 12,22C11.79,22 11.59,21.94 11.43,21.82L3.53,17.38C3.21,17.21 3,16.88 3,16.5V7.5C3,7.12 3.21,6.79 3.53,6.62L11.43,2.18C11.59,2.06 11.79,2 12,2C12.21,2 12.41,2.06 12.57,2.18L20.47,6.62C20.79,6.79 21,7.12 21,7.5V16.5M12,4.15L6.04,7.5L12,10.85L17.96,7.5L12,4.15M5,8.5V15.5L11,18.91V12.03L5,8.5M13,18.91L19,15.5V8.5L13,12.03V18.91Z" /></svg>
                        LIHAT AR GEDUNG
                    `;

                    container.appendChild(ripple);
                    container.appendChild(btn);

                    // 2. The 3D Model Hotspot (Actual Building in Scene)
                    const modelContainer = document.createElement('div');
                    modelContainer.style.width = '400px';
                    modelContainer.style.height = '400px';
                    modelContainer.style.pointerEvents = 'auto';
                    modelContainer.style.display = 'none'; // Hidden until clicked
                    modelContainer.style.transition = 'all 0.5s ease';
                    modelContainer.innerHTML = `
                        <model-viewer 
                            src="{{ asset('AR/model/bangunan3d.glb') }}" 
                            style="width: 100%; height: 100%;"
                            auto-rotate rotation-per-second="30deg"
                            shadow-intensity="1"
                            camera-orbit="0deg 75deg 105%"
                            disable-zoom
                        ></model-viewer>
                    `;

                    container.onclick = () => {
                        const arModal = document.getElementById('ar-modal-overlay');
                        const scanningOverlay = document.getElementById('ar-scanning-overlay');
                        const scanningVideo = document.getElementById('ar-scanning-video');

                        if (arModal && scanningOverlay) {
                            arModal.style.display = 'flex';
                            arModal.classList.add('active');
                            
                            // Start Camera for Scanning Phase
                            scanningOverlay.style.display = 'flex';
                            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                                navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                                    .then((stream) => {
                                        window.arCameraStream = stream;
                                        scanningVideo.srcObject = stream;
                                        
                                        // After 4 seconds of "scanning", show the actual model and play voice-over
                                        setTimeout(() => {
                                            scanningOverlay.style.transition = 'opacity 0.8s ease';
                                            scanningOverlay.style.opacity = '0';
                                            setTimeout(() => {
                                                scanningOverlay.style.display = 'none';
                                                scanningOverlay.style.opacity = '1';
                                                
                                                const mv = document.getElementById('ar-modal-viewer');
                                                if (mv && mv.dismissPoster) mv.dismissPoster();
                                                
                                                // Play voice-over saat gedung 3D ditampilkan
                                                const arText = "Hai aku merupakan gedung icon Brilliant english course. jika kalian ingin mengunjungi atau ingin bergabung menjadi bagian Brilliant english course maka kalian akan melihat gedung kampung inggris pare ini, selamat datang";
                                                if (typeof window.speakIntro === 'function') {
                                                    window.speakIntro(arText);
                                                }
                                            }, 800);
                                        }, 4000);
                                    })
                                    .catch((err) => {
                                        console.error("Camera failed:", err);
                                        scanningOverlay.style.display = 'none';
                                    });
                            } else {
                                scanningOverlay.style.display = 'none';
                            }
                        }
                    };

                    targetScene.hotspotContainer().createHotspot(container, { yaw: 0.0, pitch: 0.15 });
                }

                createARHotspot(scene2);
                createARHotspot(scene2day);
            })();
            // ===== End AR Hotspot Button =====

            
            window.activeSceneId = 'ruang-1';
            
            // ===== Hotspot Tengah Bangunan Menuju Ruang 2 Langsung =====
            // Tambahkan hotspot di tengah bangunan yang menuju ke Ruang 2 langsung
            function addCenterBuildingHotspot(scene, text, targetSceneId, yaw, pitch, fromHotspot = false) {
                const container = document.createElement('div');
                container.className = 'hotspot';
                container.innerHTML = `
                    <div class="hotspot-icon">
                        <svg viewBox="0 0 24 24" fill="white" width="30" height="30">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                    </div>
                    <div class="hotspot-label">${text}</div>
                `;
                container.onclick = () => {
                    switchRoom(targetSceneId, fromHotspot);
                };
                scene.hotspotContainer().createHotspot(container, { yaw: yaw, pitch: pitch });
            }
            
            // Hotspot di Ruang 1 menuju Ruang 2
            // Hotspot ini telah dihapus sesuai permintaan
            // ===== End Hotspot Tengah Bangunan Menuju Ruang 2 Langsung =====
            
            window.switchRoom = function(roomId, fromHotspot = false) {
                const baseRoomId = roomId.replace('-day', '');
                
                // Cek jika menuju Ruang 2 dari hotspot, arahkan ke 2A malam dulu
                if (baseRoomId === 'ruang-2' && fromHotspot && !roomId.endsWith('-day')) {
                    if (scenes['ruang-2a-malam'] && window.activeSceneId !== 'ruang-2a-malam' && window.activeSceneId !== 'ruang-2a-malam-day') {
                        scenes['ruang-2a-malam'].switchTo();
                        window.activeSceneId = 'ruang-2a-malam';
                        // Set nama ruangan ke "Ruang 2"
                        document.getElementById('current-room-name').textContent = 'Ruang 2';
                        return;
                    }
                }
                
                // Switch room normal
                if (scenes[roomId]) {
                    scenes[roomId].switchTo();
                    window.activeSceneId = roomId;
                    
                    // Perbarui nama ruangan di pojok kiri
                    if (roomId === 'ruang-2' || roomId === 'ruang-2-day' || 
                        roomId === 'ruang-2a-malam' || roomId === 'ruang-2a-siang') {
                        document.getElementById('current-room-name').textContent = 'Ruang 2';
                    } else {
                        const r = rooms.find(i => i.id === roomId.replace('-day',''));
                        if (r) document.getElementById('current-room-name').textContent = r.name;
                    }
                    
                    document.querySelectorAll('.room-item').forEach(el => el.classList.toggle('active', el.getAttribute('data-id') === roomId.replace('-day','')));

                    if (window.startAutorotate) window.startAutorotate();
                }
            };
            setInterval(() => {
                const c = window.activeSceneId;
                if (!c) return;
                if (dnPairs[c] && scenes[c + "-day"]) {
                    const v = scenes[c].view();
                    scenes[c + "-day"].view().setYaw(v.yaw());
                    scenes[c + "-day"].view().setPitch(v.pitch());
                    switchRoom(c + "-day");
                } else if (c.endsWith("-day")) {
                    const n = c.replace("-day", "");
                    if (scenes[n]) {
                        const v = scenes[c].view();
                        scenes[n].view().setYaw(v.yaw());
                        scenes[n].view().setPitch(v.pitch());
                        switchRoom(n);
                    }
                }
            }, 20000);

            const autorotateMovement = Marzipano.autorotate({ yawSpeed: 0.03, targetPitch: 0, targetFov: Math.PI / 2 });
            window.startAutorotate = () => { viewer.startMovement(autorotateMovement); viewer.setIdleMovement(3000, autorotateMovement); };
            switchRoom('ruang-1');

            // Sidebar and Footer Logic
            const sidebar = document.getElementById('sidebar-rooms');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const closeSidebar = document.getElementById('close-sidebar');
            const roomListContainer = document.getElementById('room-list-container');
            const homeBtn = document.getElementById('home-btn');

            // Room list is now pre-rendered via Blade for reliability
            // switchRoom(id) is called from onclick attribute

            sidebarToggle.onclick = () => sidebar.classList.toggle('active');
            closeSidebar.onclick = () => sidebar.classList.remove('active');
            document.getElementById('btn-close-sidebar-bottom').onclick = () => sidebar.classList.remove('active');

            // AR Modal Logic
            const arModal       = document.getElementById('ar-modal-overlay');
            const arClose       = document.getElementById('ar-modal-close');
            const arIframe      = document.getElementById('ar-iframe');
            
            if (arClose) {
                arClose.onclick = () => {
                    arModal.classList.remove('active');
                    setTimeout(() => { arModal.style.display = 'none'; }, 400);
                };
            }

            arModal.onclick = (e) => {
                if (e.target === arModal) {
                    arModal.classList.remove('active');
                    setTimeout(() => { arModal.style.display = 'none'; }, 400);
                }
            };
            
            const backHome = () => window.location.reload();
            homeBtn.onclick = backHome;
            document.getElementById('btn-back-home').onclick = backHome;

            // Logic moved higher up for reliability

            // ===== Tutorial Video Modal Logic =====
            const tutorialOverlay = document.getElementById('tutorial-modal-overlay');
            const tutorialVideoEl = document.getElementById('tutorial-video-el');
            const tPlayOverlay    = document.getElementById('tmodal-play-overlay');
            const tPlayBtn        = document.getElementById('tmodal-play-btn');
            const tProgressFill   = document.getElementById('tmodal-progress-fill');
            const tViewCount      = document.getElementById('tmodal-view-count');
            const tLikeCount      = document.getElementById('tmodal-like-count');
            const tCommentCount   = document.getElementById('tmodal-comment-count');
            const tCommentSection = document.getElementById('tmodal-comment-section');
            const tCommentList    = document.getElementById('tmodal-comment-list');
            const tCommentField   = document.getElementById('tmodal-input-comment');
            const tCommentSubmit  = document.getElementById('tmodal-send-comment');
            const tStatLike       = document.getElementById('tmodal-stat-like');
            const tStatComment    = document.getElementById('tmodal-stat-comment');

            // Array video untuk playback berurutan
            const videoSources = [
                "{{ asset('assets/video/video tutorial final.mp4') }}",
                "{{ asset('assets/video/sdh edit.mp4') }}"
            ];
            let currentVideoIndex = 1;
            let isSecondVideo = false;

            let tViews = 0, tLikes = 0, tLiked = false, tComments = 0;

            function loadTutorialData() {
                if (!tViewCount || !tLikeCount) return;
                fetch('/tutorial-data')
                    .then(res => res.json())
                    .then(data => {
                        tViews = data.views || 0;
                        tLikes = data.likes || 0;
                        tComments = (data.comments && data.comments.length) || 0;
                        
                        tViewCount.textContent = tViews;
                        tLikeCount.textContent = tLikes;
                        updateCommentCounts();
                        if (data.comments) renderComments(data.comments);
                    })
                    .catch(err => {
                        console.error("Fetch failed:", err);
                        tViews = 0; tLikes = 0; tComments = 0;
                        updateStats();
                    });
            }

            function renderComments(comments) {
                tCommentList.innerHTML = '';
                comments.forEach(comment => {
                    appendCommentUI(comment);
                });
            }

            function appendCommentUI(comment) {
                // Hide empty state if visible
                const emptyState = document.getElementById('tcomment-empty-state');
                if (emptyState) emptyState.style.display = 'none';

                const item = document.createElement('div');
                item.className = 'tcomment-item';
                if (comment.is_admin) {
                     item.style.marginLeft = '48px';
                }
                
                const avatar = comment.is_admin ? 'Ad' : 'S';
                const displayName = comment.is_admin ? (comment.user_name || 'Admin') : 'Sobat Brilliant';
                const avatarClass = comment.is_admin ? 'admin-av' : '';
                const bodyStyle = comment.is_admin ? 'background: #eef2ff; border: 1px solid #c7d2fe;' : '';
                const timeStr = new Date(comment.created_at).toLocaleDateString('id-ID', { hour: '2-digit', minute: '2-digit' });

                item.innerHTML = `
                    <div class="tcomment-avatar ${avatarClass}">${avatar}</div>
                    <div class="tcomment-body" style="${bodyStyle}">
                        <div class="tcomment-header">
                            <div class="tcomment-name">${displayName} ${comment.is_admin ? '<span class="tcomment-admin-badge">Admin</span>' : ''}</div>
                            <div class="tcomment-time">${timeStr}</div>
                        </div>
                        <div class="tcomment-text">${comment.comment_text.replace(/</g,'&lt;').replace(/>/g,'&gt;')}</div>
                    </div>`;
                tCommentList.appendChild(item);
                tCommentList.scrollTop = tCommentList.scrollHeight;
            }

            loadTutorialData(); 

            function openTutorialModal() {
                if (typeof window.closeAllModals === 'function') window.closeAllModals();
                tutorialOverlay.classList.add('active');
                if (!audio.paused) {
                    audio.pause();
                }

                // Automatically increment all stats when tutorial is opened
                fetch('/tutorial/demo-increment', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => res.json()).then(data => {
                    tViews = data.views;
                    tLikes = data.likes;
                    tComments = data.comment_count;
                    
                    tViewCount.textContent = tViews;
                    tLikeCount.textContent = tLikes;
                    updateCommentCounts();
                    
                    // Reload comments list to show the new automated comment
                    loadTutorialData();
                }).catch(err => {
                    console.error("Increment failed:", err);
                });
            }
            window.openTutorialModal = openTutorialModal;

            window.speakIntro = function(text, callback) {
                if ('speechSynthesis' in window) {
                    window.speechSynthesis.cancel();
                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'id-ID';
                    utterance.rate = 1.0;
                    utterance.pitch = 1.0;
                    const voices = window.speechSynthesis.getVoices();
                    const idVoice = voices.find(v => v.lang.includes('id') || v.lang.includes('ID'));
                    if (idVoice) utterance.voice = idVoice;
                    utterance.onend = () => { if (callback) callback(); };
                    window.speechSynthesis.speak(utterance);
                } else { if (callback) callback(); }
            }

            function closeTutorialModal() {
                tutorialOverlay.classList.remove('active');
                tutorialVideoEl.pause();
                tutorialVideoEl.currentTime = 0;
                // Reset ke video pertama
                tutorialVideoEl.src = videoSources[0];
                currentVideoIndex = 1;
                isSecondVideo = false;
                tutorialVideoEl.classList.remove('hide-duration');
                tPlayOverlay.style.display = 'flex';
                tPlayOverlay.style.opacity = '1';
                tProgressFill.style.width = '0%';
                tCommentSection.classList.remove('open');
                if ('speechSynthesis' in window) window.speechSynthesis.cancel();
            }
            window.closeTutorialModal = closeTutorialModal;

            document.getElementById('btn-video-tutorial').addEventListener('click', openTutorialModal);
            document.getElementById('btn-close-tutorial').addEventListener('click', closeTutorialModal);
            document.getElementById('tmodal-close-x').addEventListener('click', closeTutorialModal);


            tutorialOverlay.addEventListener('click', (e) => { if (e.target === tutorialOverlay) closeTutorialModal(); });

            tPlayOverlay.addEventListener('click', function() {
                const introText = "Selamat Datang di Video Tutorial. Kalian Penasaran dengan BEC AR. Tapi gak bisa membukanya dan ingin bergabung menjadi bagian Brilliant English Course. Yuk Simak videonya guys!";
                if (tPlayOverlay.getAttribute('data-speaking') === 'true') return;
                tPlayOverlay.setAttribute('data-speaking', 'true');

                speakIntro(introText, function() {
                    tutorialVideoEl.play()
                        .then(() => {
                            tPlayOverlay.style.transition = 'opacity 0.6s ease';
                            tPlayOverlay.style.opacity = '0';
                            setTimeout(() => {
                                tPlayOverlay.style.display = 'none';
                                tPlayOverlay.style.opacity = '1';
                                tPlayOverlay.setAttribute('data-speaking', 'false');
                            }, 600);
                        })
                        .catch(err => {
                            console.log('Video play error:', err);
                            tPlayOverlay.setAttribute('data-speaking', 'false');
                        });
                });
            });

            // ===== Play second video after first video ends =====
            tutorialVideoEl.addEventListener('ended', function() {
                if (currentVideoIndex < videoSources.length) {
                    // Ganti ke video kedua dan sembunyikan durasi
                    isSecondVideo = true;
                    tutorialVideoEl.classList.add('hide-duration');
                    tutorialVideoEl.src = videoSources[currentVideoIndex];
                    tutorialVideoEl.load();
                    tutorialVideoEl.play()
                        .then(() => {
                            currentVideoIndex++;
                        })
                        .catch(err => {
                            console.log('Second video play error:', err);
                        });
                }
            });
            // ===== End sequential video playback =====

            function updateStats() {
                tViewCount.textContent = tViews;
                tLikeCount.textContent = tLikes;
                updateCommentCounts();
            }

            // Like toggle
            tStatLike.addEventListener('click', function() {
                tLiked = !tLiked;
                tStatLike.classList.toggle('liked', tLiked);
                
                fetch('/tutorial/like', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ is_liked: tLiked })
                })
                .then(res => {
                    if (!res.ok) throw new Error('Like failed');
                    return res.json();
                })
                .then(data => {
                    tLikes = data.likes || tLikes;
                    tLikeCount.textContent = tLikes;
                })
                .catch(err => console.log('Like update failed:', err));
            });

            // Toggle comment section logic removed for permanent 2-column layout
            if (tStatComment) {
                tStatComment.addEventListener('click', function() {
                    // No action needed as it's always open, or maybe scroll to bottom
                    tCommentList.scrollTop = tCommentList.scrollHeight;
                });
            }

            // Submit comment
            function submitComment() {
                const text = tCommentField.value.trim();
                if (!text) return;

                const originalValue = tCommentField.value;
                tCommentField.value = '';
                tCommentSubmit.classList.remove('active');

                fetch('/tutorial/comment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ comment_text: text })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        appendCommentUI(data.user_comment);
                        tComments++;
                        updateCommentCounts();
                        
                        // Show admin reply with delay for realism
                        setTimeout(() => {
                            appendCommentUI(data.admin_comment);
                            tComments++;
                            updateCommentCounts();
                        }, 1000);
                    }
                })
                .catch(err => {
                    console.log('Comment error:', err);
                    tCommentField.value = originalValue;
                });
            }
            window.submitComment = submitComment;

            function updateCommentCounts() {
                if (tCommentCount) tCommentCount.textContent = tComments;
                const headerCount = document.getElementById('tmodal-comment-count-header');
                if (headerCount) headerCount.textContent = tComments;
            }

            tCommentField.addEventListener('input', function() {
                if (tCommentField.value.trim().length > 0) {
            tCommentSubmit.classList.add('active');
                } else {
                    tCommentSubmit.classList.remove('active');
                }
            });

            // Back to video button logic removed for 2-column layout
            const btnBackVideo = document.getElementById('btn-back-to-video');
            if (btnBackVideo) {
                btnBackVideo.onclick = () => {
                    // No action needed
                };
            }

            // ===== Registration Modal Logic =====
            const regModalOverlay = document.getElementById('registration-modal-overlay');
            const btnOpenReg = document.getElementById('btn-open-registration');
            const portalEntry = document.getElementById('portal-entry');
            const portalInterface = document.getElementById('portal-interface');
            const adminAuthBox = document.getElementById('admin-auth-box');
            const adminPassInput = document.getElementById('admin-portal-pass');

            function openRegModal() {
                if (typeof window.closeAllModals === 'function') window.closeAllModals();
                regModalOverlay.classList.add('active');
                if (!audio.paused) {
                    audio.pause();
                }
                exitPortal(); // Start at entry screen
            }
            window.openRegModal = openRegModal;

            // Langsung buka form registrasi tanpa layar login portal
            window.openDirectRegistration = function() {
                if (typeof window.closeAllModals === 'function') window.closeAllModals();
                if (!audio.paused) { audio.pause(); }

                // Mode langsung: tampilkan form pendaftaran saja, tanpa sidebar/dashboard
                const box = document.getElementById('registration-modal-box');
                box.classList.add('expanded', 'direct-reg');

                // Bypass portal-entry, langsung ke portal-interface
                portalEntry.style.display = 'none';
                portalInterface.style.display = 'flex';

                // Aktifkan hanya tab-registration
                document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
                const regPane = document.getElementById('tab-registration');
                if (regPane) regPane.classList.add('active');

                regModalOverlay.classList.add('active');
            };

            function closeRegModal() {
                regModalOverlay.classList.remove('active');
                // Reset state untuk penggunaan berikutnya
                const box = document.getElementById('registration-modal-box');
                box.classList.remove('direct-reg', 'expanded');
                portalEntry.style.display = 'flex';
                portalInterface.style.display = 'none';
            }
            window.closeRegModal = closeRegModal;

            const sidebarMenuContainer = document.getElementById('sidebar-menu-container');

            window.attemptLogin = function() {
                const user = document.getElementById('portal-user').value;
                const pass = document.getElementById('portal-pass').value;

                if (pass === 'admin123') {
                    enterPortal('admin');
                } else if (user !== '') {
                    enterPortal('student');
                } else {
                    alert('Mohon masukkan username atau password.');
                }
            };

            window.enterPortal = function(role) {
                portalEntry.style.display = 'none';
                portalInterface.style.display = 'flex';
                document.getElementById('registration-modal-box').classList.add('expanded');
                                const roleLabel = document.getElementById('sidebar-role-label');
                const studentOverview = document.getElementById('student-overview');
                const adminOverview = document.getElementById('admin-overview');

                // Clear current menu
                if (sidebarMenuContainer) sidebarMenuContainer.innerHTML = '';

                let menuItems = [];

                if (role === 'student') {
                    roleLabel.textContent = 'BEC STUDENT';
                    studentOverview.style.display = 'block';
                    adminOverview.style.display = 'none';

                    menuItems = [
                        { id: 'menu-home', tab: 'tab-welcome', label: 'Dashboard', icon: '<svg viewBox="0 0 24 24" style="width:20px;height:20px;"><path fill="currentColor" d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" /></svg>' },
                        { id: 'menu-courses', tab: 'tab-courses', label: 'Program', icon: '<svg viewBox="0 0 24 24" style="width:20px;height:20px;"><path fill="currentColor" d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" /></svg>' },
                        { id: 'menu-profile', tab: 'tab-profile', label: 'Profile', icon: '<svg viewBox="0 0 24 24" style="width:20px;height:20px;"><path fill="currentColor" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" /></svg>' },
                        { id: 'menu-reg', tab: 'tab-registration', label: 'Registrasi', icon: '<svg viewBox="0 0 24 24" style="width:20px;height:20px;"><path fill="currentColor" d="M14,10H2V12H14V10M14,6H2V8H14V6M2,16H10V14H2V16M21.5,11.5L23,13L16,20L11.5,15.5L13,14L16,17L21.5,11.5Z" /></svg>' },
                        { id: 'menu-payments', tab: 'tab-payments', label: 'Pembayaran', icon: '<svg viewBox="0 0 24 24" style="width:20px;height:20px;"><path fill="currentColor" d="M21,18V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H19A2,2 0 0,1 21,5V6H12C10.89,6 10,6.9 10,8V16A2,2 0 0,0 12,18H21M12,16H22V8H12V16M16,13.5A1.5,1.5 0 1,1 17.5,12A1.5,1.5 0 0,1 16,13.5Z" /></svg>' },
                        { id: 'menu-status', tab: 'tab-status', label: 'Status', icon: '<svg viewBox="0 0 24 24" style="width:20px;height:20px;"><path fill="currentColor" d="M12,2A10,10 0 1,0 22,12A10,10 0 0,0 12,2M12,20A8,8 0 1,1 20,12A8,8 0 0,1 12,20M11,12V7H13V12H11M11,17V15H13V17H11Z" /></svg>' }
                    ];
                } else {
                    roleLabel.textContent = 'BEC ADMIN';
                    studentOverview.style.display = 'none';
                    adminOverview.style.display = 'block';

                    menuItems = [
                        { id: 'menu-home', tab: 'tab-welcome', label: 'Dashboard', icon: '<svg viewBox="0 0 24 24" style="width:20px;height:20px;"><path fill="currentColor" d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" /></svg>' },
                        { id: 'menu-verify', tab: 'tab-payments', label: 'Verifikasi', icon: '<svg viewBox="0 0 24 24" style="width:20px;height:20px;"><path fill="currentColor" d="M12,2A10,10 0 1,0 22,12A10,10 0 0,0 12,2M12,20A8,8 0 1,1 20,12A8,8 0 0,1 12,20M11,12V7H13V12H11M11,17V15H13V17H11Z" /></svg>' },
                        { id: 'menu-courses', tab: 'tab-courses', label: 'Program', icon: '<svg viewBox="0 0 24 24" style="width:20px;height:20px;"><path fill="currentColor" d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" /></svg>' },
                        { id: 'menu-periods', tab: 'tab-periods', label: 'Periode', icon: '<svg viewBox="0 0 24 24" style="width:20px;height:20px;"><path fill="currentColor" d="M9,10H7V12H9V10M13,10H11V12H13V10M17,10H15V12H17V10M19,3H18V1H16V3H8V1H6V3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,19H5V8H19V19Z" /></svg>' },
                        { id: 'menu-data', tab: 'tab-students', label: 'Data Pendaftar', icon: '<svg viewBox="0 0 24 24" style="width:20px;height:20px;"><path fill="currentColor" d="M16,13C15.71,13 15.38,13 15.03,13.05C16.19,13.89 17,15 17,16.5V19H23V16.5C23,14.17 18.33,13 16,13M8,13C5.67,13 1,14.17 1,16.5V19H15V16.5C15,14.17 10.33,13 8,13M8,11A3,3 0 1,0 8,5A3,3 0 0,0 8,11M16,11A3,3 0 1,0 16,5A3,3 0 0,0 16,11Z" /></svg>' },
                        { id: 'menu-review', tab: 'tab-review', label: 'Review', icon: '<svg viewBox="0 0 24 24" style="width:20px;height:20px;"><path fill="currentColor" d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z" /></svg>' },
                        { id: 'menu-banks', tab: 'tab-banks', label: 'Bank', icon: '<svg viewBox="0 0 24 24" style="width:20px;height:20px;"><path fill="currentColor" d="M11.5,1L2,6V8H21V6L11.5,1M2,10V12H3V20H1V22H22V20H20V12H21V10H2M5,12H8V20H5V12M10,12H13V20H10V12M15,12H18V20H15V12Z" /></svg>' }
                    ];
                }

                // Append menu items to sidebar
                if (sidebarMenuContainer) {
                    menuItems.forEach((item, index) => {
                        const menuItem = document.createElement('div');
                        menuItem.className = 'db-menu-item' + (index === 0 ? ' active' : '');
                        menuItem.id = item.id;
                        menuItem.onclick = () => switchRegTab(item.tab, menuItem);
                        menuItem.innerHTML = item.icon + `<span>${item.label}</span>`;
                        sidebarMenuContainer.appendChild(menuItem);
                    });
                }
                
                // Go to Overview tab by default
                if (sidebarMenuContainer && sidebarMenuContainer.firstChild) {
                    switchRegTab('tab-welcome', sidebarMenuContainer.firstChild);
                }
            };

            window.exitPortal = function() {
                portalEntry.style.display = 'flex';
                portalInterface.style.display = 'none';
                document.getElementById('registration-modal-box').classList.remove('expanded');
                if (document.getElementById('portal-user')) document.getElementById('portal-user').value = '';
                if (document.getElementById('portal-pass')) document.getElementById('portal-pass').value = '';
            };

            window.switchRegTab = function(tabId, el) {
                // Update Menu Items
                document.querySelectorAll('.db-menu-item').forEach(item => {
                    item.classList.remove('active');
                });
                if (el) el.classList.add('active');

                // Update Tab Panes
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('active');
                });
                const targetPane = document.getElementById(tabId);
                if (targetPane) targetPane.classList.add('active');

                // Update Header Title
                const titles = {
                    'tab-welcome': 'Dashboard Overview',
                    'tab-profile': 'Profil Pendaftaran',
                    'tab-courses': 'Program & Kursus',
                    'tab-registration': 'Formulir Registrasi Siswa',
                    'tab-payments': 'Informasi Keuangan',
                    'tab-status': 'Status Proses Pendaftaran',
                    'tab-students': 'Database Pendaftar',
                    'tab-review': 'Review & Penilaian Dokumen',
                    'tab-periods': 'Pengaturan Periode Belajar',
                    'tab-banks': 'Database Rekening Bank'
                };
                const headerTitle = document.getElementById('reg-tab-title');
                if (headerTitle) headerTitle.textContent = titles[tabId] || 'Portal Brilliant';
            };

            // Event Listeners for Registration
            
            document.querySelectorAll('.close-reg-x').forEach(btn => {
                btn.onclick = closeRegModal;
            });
            
            if (regModalOverlay) {
                regModalOverlay.onclick = (e) => {
                    if (e.target === regModalOverlay) closeRegModal();
                };
            }

            // Tutorial Interaction logic
            const btnCloseTutorial = document.getElementById('btn-close-tutorial');
            const btnBackToVideo = document.getElementById('btn-back-to-video');
            const toggleComment = document.getElementById('tmodal-stat-comment');
            const viewCountEl = document.getElementById('tmodal-view-count');
            const likeCountEl = document.getElementById('tmodal-like-count');
            const commentCountEl = document.getElementById('tmodal-comment-count');
            const likeStatBtn = document.getElementById('tmodal-stat-like');

            if (btnCloseTutorial) {
                btnCloseTutorial.addEventListener('click', closeTutorialModal);
            }
            if (btnBackToVideo) {
                btnBackToVideo.addEventListener('click', function() {
                    const commentSection = document.getElementById('tmodal-comment-section');
                    commentSection.style.transform = 'translateY(100%)';
                });
            }
            if (toggleComment) {
                toggleComment.addEventListener('click', function() {
                    const commentSection = document.getElementById('tmodal-comment-section');
                    commentSection.style.transform = 'translateY(0)';
                });
            }
            function scrollToBottomComment() {
                if (tCommentList) tCommentList.scrollTop = tCommentList.scrollHeight;
            }
            scrollToBottomComment();

            // Scope definition removed, defined globally higher up

            // Note: validation errors handled via AJAX - no auto-open needed

            // Expose globally
            window.openRegModal = openRegModal;
            window.closeRegModal = closeRegModal;

            if (tCommentField) {
                tCommentField.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') submitComment();
                });
            }
            // ===== End Tutorial Video Modal Logic =====

            // Initial sync
            if (typeof syncAudioUI === 'function') syncAudioUI();
        });
    </script>
</body>
</html>
