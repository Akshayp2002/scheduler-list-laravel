<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Scheduler Control Center</title>
    
    <!-- Self-contained SVG Favicon (matching brand Artisan logo) -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%238b5cf6'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'/%3E%3C/svg%3E">

    
    <!-- Premium Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Dark Theme Variables (Default) */
            --bg-color: #0b0f19;
            --radial-gradient-1: rgba(139, 92, 246, 0.15);
            --radial-gradient-2: rgba(16, 185, 129, 0.1);
            --card-bg: rgba(17, 25, 40, 0.65);
            --card-border: rgba(255, 255, 255, 0.08);
            --accent-color: #8b5cf6;
            --accent-glow: rgba(139, 92, 246, 0.4);
            --success-color: #10b981;
            --success-glow: rgba(16, 185, 129, 0.2);
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --text-dim: #6b7280;
            --glass-blur: 16px;
            --header-border: rgba(255, 255, 255, 0.08);
            --search-bg: rgba(0, 0, 0, 0.25);
            
            /* Terminal Dark Theme */
            --terminal-bg: #0d1117;
            --terminal-border: rgba(255, 255, 255, 0.12);
            --terminal-header: #161b22;
            --terminal-text: #39eb48; /* neon green */
            --terminal-system: #8b949e;
            --terminal-btn-close-bg: rgba(255, 255, 255, 0.05);
            --terminal-btn-close-border: rgba(255, 255, 255, 0.08);
            --terminal-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        }

        :root[data-theme="light"] {
            /* Light Theme Variables */
            --bg-color: #f8fafc;
            --radial-gradient-1: rgba(124, 58, 237, 0.06);
            --radial-gradient-2: rgba(16, 185, 129, 0.04);
            --card-bg: rgba(255, 255, 255, 0.85);
            --card-border: rgba(15, 23, 42, 0.08);
            --accent-color: #7c3aed;
            --accent-glow: rgba(124, 58, 237, 0.15);
            --success-color: #059669;
            --success-glow: rgba(5, 150, 105, 0.1);
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --text-main: #0f172a;
            --text-muted: #475569;
            --text-dim: #94a3b8;
            --glass-blur: 20px;
            --header-border: rgba(15, 23, 42, 0.08);
            --search-bg: rgba(255, 255, 255, 0.9);
            
            /* Terminal Light Theme (Sleek Xcode/DevTools style) */
            --terminal-bg: #ffffff;
            --terminal-border: rgba(15, 23, 42, 0.08);
            --terminal-header: #f1f5f9;
            --terminal-text: #0f172a; /* dark slate */
            --terminal-system: #64748b; /* slate gray */
            --terminal-btn-close-bg: #f1f5f9;
            --terminal-btn-close-border: rgba(15, 23, 42, 0.08);
            --terminal-shadow: 0 20px 50px rgba(15, 23, 42, 0.15);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(at 10% 10%, var(--radial-gradient-1) 0px, transparent 50%),
                radial-gradient(at 90% 80%, var(--radial-gradient-2) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--text-main);
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            min-height: 100vh;
            padding: 2.5rem 1.5rem;
            line-height: 1.5;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* --- Header Section --- */
        .pulse-header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--header-border);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pulse-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .pulse-logo {
            width: 32px;
            height: 32px;
            color: var(--accent-color);
        }

        .pulse-title {
            font-size: 1.35rem;
            font-weight: 500;
            letter-spacing: -0.02em;
            color: var(--text-main);
        }

        .pulse-title-bold {
            font-weight: 700;
        }

        .pulse-header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .theme-toggle-btn {
            background: transparent;
            border: 1px solid var(--card-border);
            color: var(--text-muted);
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .theme-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--text-muted);
            color: var(--text-main);
        }

        :root[data-theme="light"] .theme-toggle-btn:hover {
            background: rgba(15, 23, 42, 0.04);
        }

        .theme-toggle-btn svg {
            width: 18px;
            height: 18px;
        }

        .stat-badge {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            backdrop-filter: blur(var(--glass-blur));
        }

        .stat-badge .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--success-color);
            box-shadow: 0 0 8px var(--success-glow);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 var(--success-glow); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* --- Controls Section --- */
        .controls-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(var(--glass-blur));
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .search-wrapper {
            position: relative;
            flex-grow: 1;
            max-width: 450px;
        }

        .search-wrapper svg {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--text-dim);
        }

        .search-input {
            width: 100%;
            background: var(--search-bg);
            border: 1px solid var(--card-border);
            color: var(--text-main);
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border-radius: 12px;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 10px rgba(139, 92, 246, 0.15);
        }

        .filter-group {
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
            padding-bottom: 2px;
        }

        .filter-btn {
            background: transparent;
            border: 1px solid var(--card-border);
            color: var(--text-muted);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .filter-btn:hover {
            background: rgba(255, 255, 255, 0.03);
            border-color: var(--text-muted);
            color: var(--text-main);
        }

        .filter-btn.active {
            background: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        }

        .refresh-btn {
            background: transparent;
            border: 1px solid var(--card-border);
            color: var(--text-muted);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            flex-shrink: 0;
        }

        .refresh-btn:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--text-muted);
            color: var(--text-main);
        }

        :root[data-theme="light"] .refresh-btn:hover {
            background: rgba(15, 23, 42, 0.04);
        }

        .refresh-btn svg {
            width: 16px;
            height: 16px;
            display: inline-block;
            transform-origin: center center;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .refresh-btn:hover svg {
            transform: rotate(180deg);
        }

        .refresh-btn:active svg {
            transform: rotate(360deg) scale(0.9);
            transition: transform 0.1s ease;
        }

        /* --- Schedulers List --- */
        .schedulers-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .scheduler-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 0.75rem 1.25rem;
            backdrop-filter: blur(var(--glass-blur));
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .scheduler-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: transparent;
            transition: background-color 0.2s ease;
        }

        .scheduler-card:hover {
            border-color: rgba(139, 92, 246, 0.25);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .scheduler-card.type-artisan::before { background: var(--accent-color); }
        .scheduler-card.type-callback::before { background: #ec4899; }
        .scheduler-card.type-shell::before { background: #3b82f6; }

        .task-main-info {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            flex-grow: 1;
            min-width: 0; /* allows text truncation */
        }

        .task-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .type-artisan .task-icon { background: rgba(139, 92, 246, 0.1); color: var(--accent-color); }
        .type-callback .task-icon { background: rgba(236, 72, 153, 0.1); color: #ec4899; }
        .type-shell .task-icon { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }

        .task-icon svg {
            width: 16px;
            height: 16px;
        }

        .task-details {
            min-width: 0;
        }

        .task-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.25rem;
        }

        .task-command {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .task-type-badge {
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            padding: 0.1rem 0.35rem;
            border-radius: 4px;
            letter-spacing: 0.05em;
        }

        .type-artisan .task-type-badge { background: rgba(139, 92, 246, 0.15); color: #a78bfa; }
        .type-callback .task-type-badge { background: rgba(236, 72, 153, 0.15); color: #f472b6; }
        .type-shell .task-type-badge { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }

        .task-description {
            font-size: 0.775rem;
            color: var(--text-muted);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .task-schedule-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-left: 1rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .schedule-expression-block {
            text-align: right;
        }

        .schedule-cron {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 0.1rem;
        }

        .schedule-human {
            font-size: 0.725rem;
            color: var(--text-dim);
        }

        .schedule-next-block {
            text-align: left;
            min-width: 120px;
        }

        .next-countdown {
            font-size: 0.825rem;
            font-weight: 600;
            color: var(--warning-color);
            margin-bottom: 0.1rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .next-countdown svg {
            width: 13px;
            height: 13px;
        }

        .next-date {
            font-size: 0.725rem;
            color: var(--text-dim);
        }

        .task-meta-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-shrink: 0;
        }

        .constraints-wrapper {
            display: flex;
            gap: 0.3rem;
            flex-wrap: wrap;
            max-width: 180px;
            justify-content: flex-end;
        }

        .constraint-badge {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            color: var(--text-muted);
            font-size: 0.675rem;
            padding: 0.15rem 0.4rem;
            border-radius: 5px;
        }

        /* --- Buttons --- */
        .btn-run {
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.3);
            color: #c084fc;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
        }

        .btn-run:hover {
            background: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
            box-shadow: 0 4px 14px var(--accent-glow);
        }

        .btn-run:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-run svg {
            width: 14px;
            height: 14px;
            fill: currentColor;
        }

        /* --- Empty State --- */
        .empty-state {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 4rem 2rem;
            text-align: center;
            backdrop-filter: blur(var(--glass-blur));
        }

        .empty-state svg {
            width: 48px;
            height: 48px;
            color: var(--text-dim);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
        }

        .empty-state p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* --- Terminal / Modal Overlay --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(4, 6, 12, 0.8);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            padding: 1.5rem;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .terminal-window {
            background: var(--terminal-bg);
            border: 1px solid var(--terminal-border);
            width: 100%;
            max-width: 750px;
            border-radius: 14px;
            box-shadow: var(--terminal-shadow);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transform: scale(0.95);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .terminal-window {
            transform: scale(1);
        }

        .terminal-header {
            background: var(--terminal-header);
            padding: 0.85rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--terminal-border);
        }

        .terminal-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .terminal-btn {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .terminal-btn.close { background: #ff5f56; }
        .terminal-btn.minimize { background: #ffbd2e; }
        .terminal-btn.maximize { background: #27c93f; }

        .terminal-title {
            color: var(--text-muted);
            font-size: 0.8rem;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 500;
        }

        .terminal-close-action {
            cursor: pointer;
            color: var(--text-dim);
            transition: color 0.2s ease;
        }

        .terminal-close-action:hover {
            color: var(--text-main);
        }

        .terminal-body {
            padding: 1.5rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.875rem;
            color: var(--terminal-text);
            background: var(--terminal-bg);
            overflow-y: auto;
            max-height: 400px;
            min-height: 250px;
            display: flex;
            flex-direction: column;
        }

        .terminal-line {
            margin-bottom: 0.5rem;
            white-space: pre-wrap;
            word-break: break-all;
        }

        .terminal-system-msg {
            color: var(--terminal-system);
        }

        .terminal-success-msg {
            color: var(--success-color);
        }

        .terminal-error-msg {
            color: var(--danger-color);
        }

        .terminal-footer {
            background: var(--terminal-header);
            padding: 0.75rem 1.25rem;
            display: flex;
            justify-content: flex-end;
            border-top: 1px solid var(--terminal-border);
        }

        .btn-terminal-close {
            background: var(--terminal-btn-close-bg);
            border: 1px solid var(--terminal-btn-close-border);
            color: var(--text-main);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-terminal-close:hover {
            opacity: 0.95;
            background: rgba(255, 255, 255, 0.1);
        }

        :root[data-theme="light"] .btn-terminal-close:hover {
            background: rgba(15, 23, 42, 0.04);
        }

        /* --- Loading Spinner --- */
        .spinner {
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* --- Responsive Queries --- */
        @media (max-width: 992px) {
            .scheduler-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 1.25rem;
            }

            .task-schedule-info {
                margin: 0;
                width: 100%;
                justify-content: space-between;
            }

            .task-meta-info {
                width: 100%;
                justify-content: space-between;
            }

            .constraints-wrapper {
                justify-content: flex-start;
                max-width: 100%;
            }
        }

        @media (max-width: 576px) {
            .task-schedule-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .schedule-expression-block {
                text-align: left;
            }

            .controls-card {
                flex-direction: column;
                align-items: stretch;
            }

            .search-wrapper {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- Pulse Header -->
        <header class="pulse-header-bar">
            <div class="pulse-brand">
                <svg class="pulse-logo" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="pulse-title">Scheduler</span>
            </div>
            
            <div class="pulse-header-actions">
                <div class="stat-badge">
                    <span class="dot"></span>
                    <span>System Timezone: <strong>{{ config('app.timezone') }}</strong> <span id="systemTime" data-timezone="{{ config('app.timezone') }}" style="margin-left: 0.25rem; font-weight: 600; opacity: 0.85;"></span></span>
                </div>
                <div class="stat-badge">
                    <span>Total Tasks: <strong>{{ $events->count() }}</strong></span>
                </div>
                
                <!-- Theme Toggle Button -->
                <button class="theme-toggle-btn" id="themeToggleBtn" aria-label="Toggle Theme">
                    <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.364l-.707-.707M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </div>
        </header>

        <!-- Controls (Search + Filters) -->
        <div class="controls-card">
            <div class="search-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="searchInput" class="search-input" placeholder="Search tasks by command, expression, or description...">
            </div>

            <div class="filter-group">
                <button class="refresh-btn" onclick="window.location.reload()" aria-label="Refresh Dashboard" title="Refresh Dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M23 4v6h-6M1 20v-6h6" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
                    </svg>
                    <span>Refresh</span>
                </button>
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="artisan">Artisan</button>
                <button class="filter-btn" data-filter="callback">Callbacks</button>
                <button class="filter-btn" data-filter="shell">Shell</button>
            </div>
        </div>

        <!-- Task List -->
        <div class="schedulers-list" id="tasksList">
            @forelse ($events as $event)
                <div class="scheduler-card type-{{ strtolower(explode(' ', $event['type'])[0]) }}" 
                     data-name="{{ strtolower($event['command']) }}"
                     data-description="{{ strtolower($event['description']) }}"
                     data-expression="{{ strtolower($event['expression']) }}"
                     data-type="{{ strtolower(explode(' ', $event['type'])[0]) }}">
                    
                    <!-- Left: Icon & Command Details -->
                    <div class="task-main-info">
                        <div class="task-icon">
                            @if(str_contains(strtolower($event['type']), 'artisan'))
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            @elseif(str_contains(strtolower($event['type']), 'callback'))
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                            @endif
                        </div>
                        <div class="task-details">
                            <div class="task-header">
                                <span class="task-command">{{ $event['command'] }}</span>
                                <span class="task-type-badge">{{ $event['type'] }}</span>
                            </div>
                            <div class="task-description" title="{{ $event['description'] }}">{{ $event['description'] }}</div>
                        </div>
                    </div>

                    <!-- Middle: Schedule details -->
                    <div class="task-schedule-info">
                        <div class="schedule-expression-block">
                            <div class="schedule-cron">{{ $event['expression'] }}</div>
                            <div class="schedule-human">TZ: {{ $event['timezone'] }}</div>
                        </div>

                        <div class="schedule-next-block">
                            @if($event['next_run'] !== 'N/A')
                                <div class="next-countdown">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $event['next_run_diff'] }}</span>
                                </div>
                                <div class="next-date">{{ $event['next_run'] }}</div>
                            @else
                                <div class="next-countdown">N/A</div>
                                <div class="next-date">Dynamic expression</div>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Constraints & Action button -->
                    <div class="task-meta-info">
                        @if(!empty($event['constraints']))
                            <div class="constraints-wrapper">
                                @foreach($event['constraints'] as $constraint)
                                    <span class="constraint-badge">{{ $constraint }}</span>
                                @endforeach
                            </div>
                        @endif

                        @if(config('scheduler-list.manual_execution', false))
                            <button class="btn-run" data-task-id="{{ $event['id'] }}" data-command="{{ $event['command'] }}">
                                <span class="spinner" id="spinner-{{ $event['id'] }}"></span>
                                <svg id="play-icon-{{ $event['id'] }}" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                                <span id="btn-text-{{ $event['id'] }}">Run</span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3>No Scheduled Tasks Defined</h3>
                    <p>We couldn't find any scheduled tasks in this Laravel application. Define your schedule in <code>routes/console.php</code> or the Console Kernel.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Terminal Overlay (Console Modal) -->
    <div class="modal-overlay" id="terminalModal">
        <div class="terminal-window">
            <div class="terminal-header">
                <div class="terminal-buttons">
                    <div class="terminal-btn close" onclick="closeTerminal()"></div>
                    <div class="terminal-btn minimize"></div>
                    <div class="terminal-btn maximize"></div>
                </div>
                <div class="terminal-title" id="terminalTitle">execution@laravel-scheduler:~</div>
                <div class="terminal-close-action" onclick="closeTerminal()">
                    <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
            <div class="terminal-body" id="terminalBody">
                <!-- Lines will be appended here dynamically -->
            </div>
            <div class="terminal-footer">
                <button class="btn-terminal-close" onclick="closeTerminal()">Close Console</button>
            </div>
        </div>
    </div>

    <!-- Live Search & Execution Script -->
    <script>
        // --- Theme Toggle Logic ---
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const sunIcon = themeToggleBtn.querySelector('.sun-icon');
        const moonIcon = themeToggleBtn.querySelector('.moon-icon');

        const savedTheme = localStorage.getItem('scheduler-theme') || 'dark';
        setTheme(savedTheme);

        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            setTheme(newTheme);
        });

        function setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('scheduler-theme', theme);

            if (theme === 'light') {
                sunIcon.style.display = 'none';
                moonIcon.style.display = 'block';
            } else {
                sunIcon.style.display = 'block';
                moonIcon.style.display = 'none';
            }
        }

        // --- Live Search & Filter ---
        const searchInput = document.getElementById('searchInput');
        const filterBtns = document.querySelectorAll('.filter-btn');
        const taskCards = document.querySelectorAll('.scheduler-card');

        let activeFilter = 'all';
        let searchQuery = '';

        searchInput.addEventListener('input', (e) => {
            searchQuery = e.target.value.toLowerCase().trim();
            filterTasks();
        });

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                activeFilter = btn.getAttribute('data-filter');
                filterTasks();
            });
        });

        function filterTasks() {
            taskCards.forEach(card => {
                const name = card.getAttribute('data-name');
                const description = card.getAttribute('data-description');
                const expression = card.getAttribute('data-expression');
                const type = card.getAttribute('data-type');

                const matchesSearch = name.includes(searchQuery) || description.includes(searchQuery) || expression.includes(searchQuery);
                const matchesFilter = activeFilter === 'all' || type === activeFilter;

                if (matchesSearch && matchesFilter) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // --- Manual Route Triggering ---
        document.querySelectorAll('.btn-run').forEach(button => {
            button.addEventListener('click', () => {
                runTask(Number(button.dataset.taskId), button.dataset.command || '');
            });
        });

        function runTask(taskId, commandName) {
            const btn = document.querySelector(`.btn-run[data-task-id="${taskId}"]`);
            const spinner = document.getElementById(`spinner-${taskId}`);
            const playIcon = document.getElementById(`play-icon-${taskId}`);
            const btnText = document.getElementById(`btn-text-${taskId}`);

            // Toggle spinner
            btn.disabled = true;
            spinner.style.display = 'block';
            playIcon.style.display = 'none';
            btnText.innerText = 'Running...';

            // Reset & Open Terminal Modal
            const terminalModal = document.getElementById('terminalModal');
            const terminalBody = document.getElementById('terminalBody');
            const terminalTitle = document.getElementById('terminalTitle');

            terminalTitle.innerText = `run:${commandName.split(' ')[0]}@laravel-scheduler:~`;
            terminalBody.innerHTML = '';
            
            appendTerminalLine(`$ php artisan schedule:run --event-id=${taskId}`, 'system');
            appendTerminalLine(`Executing command: "${commandName}"...\n`, 'system');
            
            terminalModal.classList.add('active');

            // Send post request
            fetch('{{ route('scheduler-list.run') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ id: taskId })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    appendTerminalLine(data.output || 'Command completed successfully.', 'success');
                } else {
                    appendTerminalLine(data.output || 'Failed to complete execution.', 'error');
                }
            })
            .catch(error => {
                console.error(error);
                appendTerminalLine(error.output || error.message || 'An error occurred during task execution.', 'error');
            })
            .finally(() => {
                // Restore button
                btn.disabled = false;
                spinner.style.display = 'none';
                playIcon.style.display = 'block';
                btnText.innerText = 'Run';
            });
        }

        function appendTerminalLine(text, type = 'default') {
            const terminalBody = document.getElementById('terminalBody');
            const line = document.createElement('div');
            line.classList.add('terminal-line');

            if (type === 'system') {
                line.classList.add('terminal-system-msg');
            } else if (type === 'success') {
                line.classList.add('terminal-success-msg');
            } else if (type === 'error') {
                line.classList.add('terminal-error-msg');
            }

            line.innerText = text;
            terminalBody.appendChild(line);
            
            // Auto scroll to bottom
            terminalBody.scrollTop = terminalBody.scrollHeight;
        }

        function closeTerminal() {
            document.getElementById('terminalModal').classList.remove('active');
        }

        // --- Live System Time Clock ---
        const systemTimeEl = document.getElementById('systemTime');
        if (systemTimeEl) {
            const timezone = systemTimeEl.getAttribute('data-timezone');
            const updateClock = () => {
                try {
                    const now = new Date();
                    const formatter = new Intl.DateTimeFormat('en-US', {
                        timeZone: timezone,
                        hour: 'numeric',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true
                    });
                    systemTimeEl.innerText = `(${formatter.format(now)})`;
                } catch (e) {
                    const now = new Date();
                    systemTimeEl.innerText = `(${now.toLocaleTimeString()})`;
                }
            };
            updateClock();
            setInterval(updateClock, 1000);
        }
    </script>
</body>
</html>
