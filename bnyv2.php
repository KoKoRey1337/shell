<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiniShell Pro - Admin Dashboard</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Flag Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: url('https://i.ibb.co/mCZkXzZh/bny.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(6px);
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 1440px;
            margin: 0 auto;
            padding: 20px;
            min-height: 100vh;
        }

        /* ===== HEADER ===== */
        .header {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 18px 28px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .header-left .logo {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, #00d2ff, #7b2ffc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-left .logo i {
            -webkit-text-fill-color: initial;
            color: #7b2ffc;
            margin-right: 8px;
        }

        .header-time {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.06);
            padding: 6px 14px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .header-right .badge {
            background: rgba(123, 47, 252, 0.25);
            padding: 5px 12px;
            border-radius: 16px;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid rgba(123, 47, 252, 0.15);
            color: #c084fc;
        }

        .header-right .badge i {
            margin-right: 4px;
        }

        .ip-info {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 12px;
            background: rgba(0, 0, 0, 0.3);
            padding: 5px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .ip-info .flag-icon {
            width: 20px;
            height: 15px;
            border-radius: 2px;
        }

        .ip-info .label {
            color: rgba(255, 255, 255, 0.4);
            margin-right: 3px;
        }

        .ip-info .value {
            color: #00d2ff;
            font-weight: 600;
        }

        /* ===== STATS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-bottom: 18px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border-radius: 14px;
            padding: 12px 16px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.3s ease;
            cursor: pointer;
            text-align: center;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.09);
            border-color: rgba(255, 255, 255, 0.12);
        }

        .stat-card .icon { font-size: 18px; display: block; margin-bottom: 2px; }
        .stat-card .number { font-size: 20px; font-weight: 700; display: block; }
        .stat-card .label { font-size: 10px; color: rgba(255, 255, 255, 0.5); font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px; }

        .stat-card:nth-child(1) .icon { color: #00d2ff; }
        .stat-card:nth-child(2) .icon { color: #7b2ffc; }
        .stat-card:nth-child(3) .icon { color: #ffd93d; }
        .stat-card:nth-child(4) .icon { color: #6bcb77; }
        .stat-card:nth-child(5) .icon { color: #ff6b6b; }
        .stat-card:nth-child(6) .icon { color: #a29bfe; }
        .stat-card:nth-child(7) .icon { color: #fd79a8; }

        /* ===== TOOLBAR ===== */
        .toolbar {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border-radius: 14px;
            padding: 10px 16px;
            margin-bottom: 14px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 6px;
        }

        .toolbar .btn {
            padding: 7px 14px;
            border: none;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Inter', sans-serif;
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .toolbar .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }

        .toolbar .btn i { font-size: 13px; }
        .toolbar .btn-primary { background: rgba(0, 210, 255, 0.15); color: #00d2ff; border-color: rgba(0, 210, 255, 0.15); }
        .toolbar .btn-primary:hover { background: rgba(0, 210, 255, 0.25); }
        .toolbar .btn-success { background: rgba(107, 203, 119, 0.15); color: #6bcb77; border-color: rgba(107, 203, 119, 0.15); }
        .toolbar .btn-success:hover { background: rgba(107, 203, 119, 0.25); }
        .toolbar .btn-danger { background: rgba(255, 82, 82, 0.15); color: #ff5252; border-color: rgba(255, 82, 82, 0.15); }
        .toolbar .btn-danger:hover { background: rgba(255, 82, 82, 0.25); }
        .toolbar .btn-warning { background: rgba(255, 217, 61, 0.15); color: #ffd93d; border-color: rgba(255, 217, 61, 0.15); }
        .toolbar .btn-warning:hover { background: rgba(255, 217, 61, 0.25); }
        .toolbar .btn-purple { background: rgba(123, 47, 252, 0.15); color: #7b2ffc; border-color: rgba(123, 47, 252, 0.15); }
        .toolbar .btn-purple:hover { background: rgba(123, 47, 252, 0.25); }
        .toolbar .btn-info { background: rgba(162, 155, 254, 0.15); color: #a29bfe; border-color: rgba(162, 155, 254, 0.15); }
        .toolbar .btn-info:hover { background: rgba(162, 155, 254, 0.25); }
        .toolbar .btn-pink { background: rgba(253, 121, 168, 0.15); color: #fd79a8; border-color: rgba(253, 121, 168, 0.15); }
        .toolbar .btn-pink:hover { background: rgba(253, 121, 168, 0.25); }
        .toolbar .btn-outline { background: transparent; border-color: rgba(255,255,255,0.08); }
        .toolbar .btn-outline:hover { background: rgba(255,255,255,0.05); }

        /* ===== PATH BAR ===== */
        .path-bar {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 10px 18px;
            margin-bottom: 14px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .path-bar .pwd {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            font-family: 'Courier New', monospace;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
        }

        .path-bar .pwd i {
            color: #7b2ffc;
            margin-right: 6px;
        }

        .path-bar .pwd .dir-link {
            color: #00d2ff;
            cursor: pointer;
            padding: 2px 8px;
            border-radius: 6px;
            transition: all 0.2s ease;
            text-decoration: none;
            font-weight: 500;
        }

        .path-bar .pwd .dir-link:hover {
            background: rgba(0, 210, 255, 0.15);
            color: #fff;
        }

        .path-bar .pwd .dir-link.separator {
            color: rgba(255, 255, 255, 0.2);
            cursor: default;
            padding: 0 2px;
        }

        .path-bar .pwd .dir-link.separator:hover {
            background: transparent;
        }

        .path-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .path-actions .btn-sm {
            padding: 4px 12px;
            border: none;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
        }
        .path-actions .btn-sm:hover { transform: scale(1.05); }
        .path-actions .btn-sm.primary { background: rgba(0, 210, 255, 0.15); color: #00d2ff; }
        .path-actions .btn-sm.success { background: rgba(107, 203, 119, 0.15); color: #6bcb77; }

        /* ===== TERMINAL INPUT ===== */
        .terminal-input-area {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 12px 18px;
            margin-bottom: 14px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .terminal-input-area .term-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .terminal-input-area .term-label i {
            color: #7b2ffc;
        }

        .terminal-input-area input {
            flex: 1;
            min-width: 150px;
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            font-size: 13px;
            font-family: 'Courier New', monospace;
            transition: all 0.3s ease;
        }

        .terminal-input-area input:focus {
            outline: none;
            border-color: #7b2ffc;
            background: rgba(255, 255, 255, 0.08);
        }

        .terminal-input-area input::placeholder {
            color: rgba(255, 255, 255, 0.25);
        }

        .terminal-input-area .btn {
            padding: 7px 16px;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            background: rgba(123, 47, 252, 0.2);
            color: #7b2ffc;
            border: 1px solid rgba(123, 47, 252, 0.15);
            white-space: nowrap;
        }

        .terminal-input-area .btn:hover {
            background: rgba(123, 47, 252, 0.3);
            transform: translateY(-2px);
        }

        .terminal-input-area .btn-success {
            background: rgba(107, 203, 119, 0.15);
            color: #6bcb77;
            border-color: rgba(107, 203, 119, 0.15);
        }

        .terminal-input-area .btn-success:hover {
            background: rgba(107, 203, 119, 0.25);
        }

        .terminal-input-area .btn-danger {
            background: rgba(255, 82, 82, 0.15);
            color: #ff5252;
            border-color: rgba(255, 82, 82, 0.15);
        }

        .terminal-input-area .btn-danger:hover {
            background: rgba(255, 82, 82, 0.25);
        }

        /* ===== TABLES ===== */
        .table-section {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(12px);
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            overflow: hidden;
            margin-bottom: 14px;
        }

        .table-header {
            padding: 12px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            user-select: none;
            transition: background 0.3s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }

        .table-header:hover { background: rgba(255, 255, 255, 0.03); }
        .table-header h3 { font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .table-header h3 i { color: #7b2ffc; }
        .table-header .toggle-icon { transition: transform 0.4s ease; font-size: 13px; color: rgba(255, 255, 255, 0.3); }
        .table-header .toggle-icon.open { transform: rotate(180deg); }
        .table-header .badge-count {
            background: rgba(123, 47, 252, 0.2);
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 10px;
            color: #c084fc;
        }

        .table-body {
            overflow: hidden;
            transition: max-height 0.4s ease;
            max-height: 0;
        }
        .table-body.open { max-height: 2000px; }

        .table-wrapper { overflow-x: auto; padding: 0 5px 5px 5px; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table thead { background: rgba(255, 255, 255, 0.03); }
        table th {
            text-align: left;
            padding: 8px 14px;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: rgba(255, 255, 255, 0.4);
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }
        table td {
            padding: 8px 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            color: rgba(255, 255, 255, 0.85);
        }
        table tr:hover td { background: rgba(255, 255, 255, 0.03); }

        .file-item {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .file-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .file-item .file-icon {
            margin-right: 6px;
        }
        .file-item .file-icon.folder { color: #ffd93d; }
        .file-item .file-icon.file { color: #a29bfe; }
        .file-item .file-icon.image { color: #6bcb77; }
        .file-item .file-icon.php { color: #7b2ffc; }
        .file-item .file-icon.txt { color: #00d2ff; }
        .file-item .file-icon.zip { color: #ff6b6b; }
        .file-item .file-icon.html { color: #fd79a8; }
        .file-item .file-icon.css { color: #00d2ff; }
        .file-item .file-icon.js { color: #ffd93d; }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 600;
        }
        .status.active { background: rgba(107, 203, 119, 0.15); color: #6bcb77; }
        .status.inactive { background: rgba(255, 82, 82, 0.15); color: #ff5252; }
        .status.pending { background: rgba(255, 217, 61, 0.15); color: #ffd93d; }

        .status-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            display: inline-block;
        }
        .status-dot.active { background: #6bcb77; }
        .status-dot.inactive { background: #ff5252; }
        .status-dot.pending { background: #ffd93d; }

        .btn-sm {
            padding: 3px 10px;
            border: none;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
        }
        .btn-sm:hover { transform: scale(1.05); }
        .btn-sm.primary { background: rgba(0, 210, 255, 0.2); color: #00d2ff; }
        .btn-sm.success { background: rgba(107, 203, 119, 0.2); color: #6bcb77; }
        .btn-sm.danger { background: rgba(255, 82, 82, 0.2); color: #ff5252; }
        .btn-sm.warning { background: rgba(255, 217, 61, 0.2); color: #ffd93d; }
        .btn-sm.purple { background: rgba(123, 47, 252, 0.2); color: #7b2ffc; }

        /* ===== TERMINAL OUTPUT ===== */
        .terminal-output {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(12px);
            border-radius: 12px;
            padding: 12px 16px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            margin-top: 10px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #a29bfe;
            max-height: 200px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-all;
            display: none;
        }
        .terminal-output.show { display: block; }
        .terminal-output .prompt { color: #00d2ff; }
        .terminal-output .output-line { padding: 2px 0; border-bottom: 1px solid rgba(255,255,255,0.02); }

        /* ===== MODAL ===== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            z-index: 999;
            display: none;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }
        .modal-overlay.show { display: flex; }

        .modal-box {
            background: rgba(30, 30, 50, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 28px;
            max-width: 520px;
            width: 90%;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.3s ease;
        }
        .modal-box h3 {
            font-size: 17px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .modal-box h3 i { color: #7b2ffc; }
        .modal-box label {
            font-size: 12px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.6);
            display: block;
            margin: 8px 0 4px;
        }
        .modal-box input, .modal-box textarea, .modal-box select {
            width: 100%;
            padding: 9px 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }
        .modal-box input:focus, .modal-box textarea:focus, .modal-box select:focus {
            outline: none;
            border-color: #7b2ffc;
            background: rgba(255, 255, 255, 0.08);
        }
        .modal-box textarea { min-height: 100px; resize: vertical; font-family: 'Courier New', monospace; }
        .modal-box .modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 16px;
            justify-content: flex-end;
        }
        .modal-box .modal-actions .btn {
            padding: 8px 20px;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }
        .modal-box .modal-actions .btn-primary { background: #7b2ffc; color: #fff; }
        .modal-box .modal-actions .btn-primary:hover { background: #6b1fc9; }
        .modal-box .modal-actions .btn-secondary { background: rgba(255,255,255,0.1); color: #fff; }
        .modal-box .modal-actions .btn-secondary:hover { background: rgba(255,255,255,0.15); }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: rgba(255,255,255,0.03); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

        /* ===== FOOTER ===== */
        .footer {
            text-align: center;
            padding: 12px;
            color: rgba(255, 255, 255, 0.25);
            font-size: 11px;
            border-top: 1px solid rgba(255, 255, 255, 0.03);
            margin-top: 10px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .header { flex-direction: column; align-items: flex-start; padding: 14px 18px; }
            .header-right { width: 100%; flex-wrap: wrap; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .toolbar .btn { font-size: 11px; padding: 5px 10px; }
            .ip-info { font-size: 10px; padding: 4px 10px; }
            .modal-box { padding: 20px; }
            .container { padding: 10px; }
            .terminal-input-area { flex-direction: column; align-items: stretch; }
            .terminal-input-area input { min-width: auto; }
            .path-bar .pwd { font-size: 11px; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .header-left .logo { font-size: 18px; }
            .modal-box { padding: 16px; }
        }

        /* ===== LOADING ===== */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.1);
            border-radius: 50%;
            border-top: 2px solid #7b2ffc;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- ===== HEADER ===== -->
    <header class="header">
        <div class="header-left">
            <div class="logo"><i class="fas fa-terminal"></i> MiniShell Pro</div>
            <div class="header-time"><i class="fas fa-clock"></i> <span id="liveTime">Loading...</span></div>
        </div>
        <div class="header-right">
            <div class="ip-info">
                <span class="label"><i class="fas fa-server"></i> Server:</span>
                <span class="value" id="serverIP">Loading...</span>
                <span class="flag-icon flag-icon-id" id="serverFlag"></span>
            </div>
            <div class="ip-info">
                <span class="label"><i class="fas fa-user"></i> Your IP:</span>
                <span class="value" id="userIP">Loading...</span>
                <span class="flag-icon" id="userFlag"></span>
            </div>
            <span class="badge"><i class="fas fa-shield-alt"></i> Secure</span>
            <span class="badge"><i class="fas fa-user-shield"></i> Admin</span>
        </div>
    </header>

    <!-- ===== STATS ===== -->
    <div class="stats-grid">
        <div class="stat-card"><span class="icon"><i class="fas fa-folder"></i></span><span class="number" id="totalFiles">0</span><span class="label">Files</span></div>
        <div class="stat-card"><span class="icon"><i class="fas fa-users"></i></span><span class="number" id="totalUsers">0</span><span class="label">Users</span></div>
        <div class="stat-card"><span class="icon"><i class="fas fa-plug"></i></span><span class="number" id="totalPlugins">0</span><span class="label">Plugins</span></div>
        <div class="stat-card"><span class="icon"><i class="fas fa-hdd"></i></span><span class="number" id="totalStorage">0</span><span class="label">Storage</span></div>
        <div class="stat-card"><span class="icon"><i class="fas fa-globe"></i></span><span class="number" id="totalDomains">0</span><span class="label">Domains</span></div>
        <div class="stat-card"><span class="icon"><i class="fas fa-shield-alt"></i></span><span class="number" id="totalSecurity">0</span><span class="label">Security</span></div>
        <div class="stat-card"><span class="icon"><i class="fas fa-code"></i></span><span class="number" id="totalCommands">0</span><span class="label">Commands</span></div>
    </div>

    <!-- ===== PATH BAR ===== -->
    <div class="path-bar">
        <div class="pwd">
            <i class="fas fa-folder-open"></i>
            <span id="pathBreadcrumb"></span>
        </div>
        <div class="path-actions">
            <button class="btn-sm primary" onclick="copyPath()"><i class="fas fa-copy"></i> Copy</button>
            <button class="btn-sm success" onclick="refreshFiles()"><i class="fas fa-sync"></i> Refresh</button>
            <button class="btn-sm primary" onclick="goHome()"><i class="fas fa-home"></i> Home</button>
            <button class="btn-sm primary" onclick="goUp()"><i class="fas fa-level-up-alt"></i> Up</button>
        </div>
    </div>

    <!-- ===== TOOLBAR ===== -->
    <div class="toolbar">
        <button class="btn btn-primary" onclick="openModal('edit')"><i class="fas fa-edit"></i> Edit File</button>
        <button class="btn btn-success" onclick="openModal('createfile')"><i class="fas fa-file"></i> New File</button>
        <button class="btn btn-warning" onclick="openModal('createfolder')"><i class="fas fa-folder-plus"></i> New Folder</button>
        <button class="btn btn-purple" onclick="openModal('rename')"><i class="fas fa-i-cursor"></i> Rename</button>
        <button class="btn btn-info" onclick="openModal('upload')"><i class="fas fa-upload"></i> Upload</button>
        <button class="btn btn-pink" onclick="toggleTerminal()"><i class="fas fa-terminal"></i> Terminal</button>
        <button class="btn btn-outline" onclick="clearTerminalOutput()"><i class="fas fa-eraser"></i> Clear</button>
    </div>

    <!-- ===== TERMINAL INPUT ===== -->
    <div class="terminal-input-area" id="terminalInputArea">
        <div class="term-label"><i class="fas fa-terminal"></i> $</div>
        <input type="text" id="terminalCommand" placeholder="Type command... (ls, pwd, cd, cat, etc)" onkeypress="if(event.key==='Enter') executeTerminalCommand()">
        <button class="btn" onclick="executeTerminalCommand()"><i class="fas fa-play"></i> Run</button>
        <button class="btn btn-success" onclick="runQuickCommand('ls -la')"><i class="fas fa-list"></i> ls</button>
        <button class="btn btn-success" onclick="runQuickCommand('pwd')"><i class="fas fa-folder"></i> pwd</button>
        <button class="btn btn-danger" onclick="clearTerminalOutput()"><i class="fas fa-trash"></i></button>
    </div>

    <!-- ===== TERMINAL OUTPUT ===== -->
    <div class="terminal-output" id="terminalOutput">
        <div class="output-line"><span class="prompt">$</span> Welcome to MiniShell Pro v3.0</div>
        <div class="output-line"><span class="prompt">$</span> Type command in the input above</div>
        <div class="output-line"><span class="prompt">$</span> <span id="terminalMessage">Ready...</span></div>
    </div>

    <!-- ===== TABLE: FILE MANAGER ===== -->
    <div class="table-section">
        <div class="table-header" onclick="toggleTable('table1')">
            <h3><i class="fas fa-folder-open"></i> File Manager <span class="badge-count" id="fileCount">0 items</span></h3>
            <span class="toggle-icon open" id="toggle1"><i class="fas fa-chevron-down"></i></span>
        </div>
        <div class="table-body open" id="table1">
            <div class="table-wrapper">
                <table>
                    <thead><tr><th>Name</th><th>Type</th><th>Size</th><th>Modified</th><th>Perms</th><th>Actions</th></tr></thead>
                    <tbody id="fileTableBody">
                        <!-- Data akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===== TABLE: PLUGINS ===== -->
    <div class="table-section">
        <div class="table-header" onclick="toggleTable('table2')">
            <h3><i class="fas fa-plug"></i> Plugins & Extensions</h3>
            <span class="toggle-icon" id="toggle2"><i class="fas fa-chevron-down"></i></span>
        </div>
        <div class="table-body" id="table2">
            <div class="table-wrapper">
                <table>
                    <thead><tr><th>Plugin</th><th>Version</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody id="pluginTableBody">
                        <!-- Data akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===== FOOTER ===== -->
    <div class="footer">
        <i class="fas fa-terminal"></i> MiniShell Pro v3.0 &bull; <span style="color:#7b2ffc;">Nerogativsky13</span> &bull; Anti-Delete Shell Active
    </div>

</div>

<!-- ===== MODAL ===== -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal-box" id="modalBox">
        <h3 id="modalTitle"><i class="fas fa-edit"></i> Action</h3>
        <div id="modalContent"></div>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            <button class="btn btn-primary" id="modalSubmitBtn" onclick="submitModal()">Execute</button>
        </div>
    </div>
</div>

<script>
    // ============================================
    // DATA & STATE - LENGKAP
    // ============================================
    let currentPath = '/home/admin/public_html';
    
    // File System Lengkap
    const fileSystem = {
        '/': {
            type: 'dir',
            items: [
                { name: 'home', type: 'dir', size: '--', modified: '2024-01-15 14:30', perms: '755' },
                { name: 'etc', type: 'dir', size: '--', modified: '2024-01-14 08:00', perms: '755' },
                { name: 'var', type: 'dir', size: '--', modified: '2024-01-13 22:00', perms: '755' },
                { name: 'tmp', type: 'dir', size: '--', modified: '2024-01-12 10:00', perms: '777' }
            ]
        },
        '/home': {
            type: 'dir',
            items: [
                { name: 'admin', type: 'dir', size: '--', modified: '2024-01-15 14:30', perms: '755' },
                { name: 'user1', type: 'dir', size: '--', modified: '2024-01-14 09:00', perms: '755' },
                { name: 'user2', type: 'dir', size: '--', modified: '2024-01-13 16:00', perms: '755' }
            ]
        },
        '/home/admin': {
            type: 'dir',
            items: [
                { name: 'public_html', type: 'dir', size: '--', modified: '2024-01-15 14:30', perms: '755' },
                { name: 'logs', type: 'dir', size: '--', modified: '2024-01-14 08:00', perms: '755' },
                { name: 'backups', type: 'dir', size: '--', modified: '2024-01-13 22:00', perms: '755' },
                { name: '.bashrc', type: 'txt', size: '3.2 KB', modified: '2024-01-10 12:00', perms: '644' },
                { name: '.profile', type: 'txt', size: '0.8 KB', modified: '2024-01-09 10:30', perms: '644' }
            ]
        },
        '/home/admin/public_html': {
            type: 'dir',
            items: [
                { name: 'httpdocs', type: 'dir', size: '--', modified: '2024-01-15 14:30', perms: '755' },
                { name: 'wp-admin', type: 'dir', size: '--', modified: '2024-01-14 09:20', perms: '755' },
                { name: 'wp-content', type: 'dir', size: '--', modified: '2024-01-13 18:45', perms: '755' },
                { name: 'wp-includes', type: 'dir', size: '--', modified: '2024-01-13 17:30', perms: '755' },
                { name: 'index.php', type: 'php', size: '4.2 KB', modified: '2024-01-13 22:15', perms: '644' },
                { name: 'license.txt', type: 'txt', size: '12.8 KB', modified: '2024-01-10 16:45', perms: '644' },
                { name: 'readme.html', type: 'html', size: '7.2 KB', modified: '2024-01-12 11:20', perms: '644' },
                { name: 'logo.png', type: 'image', size: '245 KB', modified: '2024-01-08 11:00', perms: '644' },
                { name: 'config.php', type: 'php', size: '1.8 KB', modified: '2024-01-11 09:30', perms: '600' },
                { name: 'style.css', type: 'css', size: '18.4 KB', modified: '2024-01-09 15:20', perms: '644' },
                { name: 'script.js', type: 'js', size: '32.1 KB', modified: '2024-01-07 13:10', perms: '644' },
                { name: 'backup.zip', type: 'zip', size: '2.3 MB', modified: '2024-01-05 08:00', perms: '644' },
                { name: '.htaccess', type: 'txt', size: '0.5 KB', modified: '2024-01-06 14:00', perms: '644' }
            ]
        },
        '/home/admin/public_html/httpdocs': {
            type: 'dir',
            items: [
                { name: 'images', type: 'dir', size: '--', modified: '2024-01-14 10:00', perms: '755' },
                { name: 'css', type: 'dir', size: '--', modified: '2024-01-13 16:30', perms: '755' },
                { name: 'js', type: 'dir', size: '--', modified: '2024-01-12 14:20', perms: '755' },
                { name: 'index.html', type: 'html', size: '3.5 KB', modified: '2024-01-14 12:00', perms: '644' },
                { name: 'about.html', type: 'html', size: '2.1 KB', modified: '2024-01-13 11:45', perms: '644' },
                { name: 'contact.html', type: 'html', size: '1.8 KB', modified: '2024-01-12 09:30', perms: '644' }
            ]
        },
        '/home/admin/public_html/wp-admin': {
            type: 'dir',
            items: [
                { name: 'css', type: 'dir', size: '--', modified: '2024-01-13 09:00', perms: '755' },
                { name: 'js', type: 'dir', size: '--', modified: '2024-01-12 18:30', perms: '755' },
                { name: 'images', type: 'dir', size: '--', modified: '2024-01-11 15:20', perms: '755' },
                { name: 'admin.php', type: 'php', size: '5.8 KB', modified: '2024-01-13 10:15', perms: '644' },
                { name: 'index.php', type: 'php', size: '0.8 KB', modified: '2024-01-12 12:00', perms: '644' },
                { name: 'install.php', type: 'php', size: '2.3 KB', modified: '2024-01-11 14:30', perms: '644' }
            ]
        },
        '/home/admin/public_html/wp-content': {
            type: 'dir',
            items: [
                { name: 'themes', type: 'dir', size: '--', modified: '2024-01-13 14:30', perms: '755' },
                { name: 'plugins', type: 'dir', size: '--', modified: '2024-01-12 11:00', perms: '755' },
                { name: 'uploads', type: 'dir', size: '--', modified: '2024-01-11 09:30', perms: '755' },
                { name: 'index.php', type: 'php', size: '0.1 KB', modified: '2024-01-10 08:00', perms: '644' }
            ]
        },
        '/home/admin/public_html/wp-includes': {
            type: 'dir',
            items: [
                { name: 'js', type: 'dir', size: '--', modified: '2024-01-12 16:00', perms: '755' },
                { name: 'css', type: 'dir', size: '--', modified: '2024-01-11 13:30', perms: '755' },
                { name: 'images', type: 'dir', size: '--', modified: '2024-01-10 10:20', perms: '755' },
                { name: 'version.php', type: 'php', size: '0.6 KB', modified: '2024-01-09 08:00', perms: '644' }
            ]
        },
        '/home/admin/logs': {
            type: 'dir',
            items: [
                { name: 'access.log', type: 'txt', size: '1.2 MB', modified: '2024-01-15 14:30', perms: '644' },
                { name: 'error.log', type: 'txt', size: '245 KB', modified: '2024-01-15 13:20', perms: '644' },
                { name: 'nginx.log', type: 'txt', size: '890 KB', modified: '2024-01-15 12:00', perms: '644' }
            ]
        },
        '/home/admin/backups': {
            type: 'dir',
            items: [
                { name: 'backup-2024-01-15.tar.gz', type: 'zip', size: '45.2 MB', modified: '2024-01-15 02:00', perms: '644' },
                { name: 'backup-2024-01-14.tar.gz', type: 'zip', size: '43.8 MB', modified: '2024-01-14 02:00', perms: '644' },
                { name: 'backup-2024-01-13.tar.gz', type: 'zip', size: '42.1 MB', modified: '2024-01-13 02:00', perms: '644' }
            ]
        }
    };

    // ============================================
    // 1. NAVIGATION - BREADCRUMB
    // ============================================
    function renderBreadcrumb() {
        const parts = currentPath.split('/').filter(p => p);
        const breadcrumb = document.getElementById('pathBreadcrumb');
        let html = '';
        
        if (parts.length === 0) {
            html = `<span class="dir-link" onclick="navigateTo('/')">/</span>`;
        } else {
            html = `<span class="dir-link" onclick="navigateTo('/')">/</span>`;
            let path = '';
            parts.forEach((part, index) => {
                path += '/' + part;
                if (index === parts.length - 1) {
                    html += `<span class="dir-link separator">/</span><span style="color:#fff;font-weight:600;">${part}</span>`;
                } else {
                    html += `<span class="dir-link separator">/</span><span class="dir-link" onclick="navigateTo('${path}')">${part}</span>`;
                }
            });
        }
        
        breadcrumb.innerHTML = html;
        updateFileList();
    }

    function navigateTo(path) {
        // Normalize path
        if (path === '/') {
            currentPath = '/home/admin/public_html';
        } else if (fileSystem[path]) {
            currentPath = path;
        } else {
            // Try to find closest match
            let found = false;
            for (const key in fileSystem) {
                if (path.startsWith(key)) {
                    currentPath = key;
                    found = true;
                    break;
                }
            }
            if (!found) {
                addTerminalLine(`❌ Directory not found: ${path}`);
                return;
            }
        }
        renderBreadcrumb();
        addTerminalLine(`📁 Navigated to: ${currentPath}`);
    }

    function goHome() {
        navigateTo('/home/admin/public_html');
    }

    function goUp() {
        const parts = currentPath.split('/').filter(p => p);
        if (parts.length <= 1) {
            navigateTo('/');
        } else {
            parts.pop();
            const newPath = '/' + parts.join('/');
            navigateTo(newPath);
        }
    }

    // ============================================
    // 2. FILE LIST
    // ============================================
    function updateFileList() {
        const tbody = document.getElementById('fileTableBody');
        const dir = fileSystem[currentPath];
        
        if (!dir || !dir.items) {
            tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;color:rgba(255,255,255,0.3);padding:30px;">📂 Empty directory</td></tr>`;
            document.getElementById('fileCount').textContent = '0 items';
            return;
        }

        const items = dir.items;
        document.getElementById('fileCount').textContent = `${items.length} items`;
        
        let html = '';
        items.forEach(item => {
            const iconMap = {
                'dir': '<i class="fas fa-folder file-icon folder"></i>',
                'php': '<i class="fas fa-file-code file-icon php"></i>',
                'txt': '<i class="fas fa-file-alt file-icon txt"></i>',
                'image': '<i class="fas fa-file-image file-icon image"></i>',
                'html': '<i class="fas fa-file-code file-icon html"></i>',
                'css': '<i class="fas fa-file-code file-icon css"></i>',
                'js': '<i class="fas fa-file-code file-icon js"></i>',
                'zip': '<i class="fas fa-file-archive file-icon zip"></i>'
            };
            
            const icon = iconMap[item.type] || '<i class="fas fa-file file-icon file"></i>';
            const isDir = item.type === 'dir';
            
            let nameDisplay;
            if (isDir) {
                const targetPath = currentPath === '/' ? '/' + item.name : currentPath + '/' + item.name;
                nameDisplay = `<span style="cursor:pointer;color:#ffd93d;" onclick="navigateTo('${targetPath}')">${icon} ${item.name}</span>`;
            } else {
                nameDisplay = `${icon} ${item.name}`;
            }
            
            const protectedFiles = ['index.php', 'config.php', '.htaccess', 'shell.php', 'admin.php', 'wp-config.php'];
            const isProtected = protectedFiles.some(p => item.name.includes(p));
            
            html += `<tr>
                <td>${nameDisplay}</td>
                <td>${isDir ? 'Directory' : item.type.toUpperCase()}</td>
                <td>${item.size}</td>
                <td>${item.modified}</td>
                <td>${item.perms}</td>
                <td>
                    ${!isDir ? `<button class="btn-sm primary" onclick="openModal('edit','${currentPath}/${item.name}')"><i class="fas fa-edit"></i></button> ` : ''}
                    <button class="btn-sm danger" onclick="confirmDelete(this, '${item.name}')"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;
        });
        
        tbody.innerHTML = html;
        updateStats();
    }

    // ============================================
    // 3. LIVE CLOCK
    // ============================================
    function updateClock() {
        const now = new Date();
        document.getElementById('liveTime').textContent = 
            now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }) + 
            ' | ' + now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    updateClock();
    setInterval(updateClock, 1000);

    // ============================================
    // 4. GET IP & COUNTRY
    // ============================================
    async function getIPInfo() {
        try {
            const res = await fetch('https://api.ipify.org?format=json');
            const data = await res.json();
            const ip = data.ip;
            document.getElementById('userIP').textContent = ip;
            
            const geoRes = await fetch(`https://ipapi.co/${ip}/json/`);
            const geoData = await geoRes.json();
            if (geoData.country_code) {
                const flag = document.getElementById('userFlag');
                flag.className = `flag-icon flag-icon-${geoData.country_code.toLowerCase()}`;
                flag.title = geoData.country_name || '';
            }
            
            const serverIP = ip.replace(/\.\d+$/, '.1');
            document.getElementById('serverIP').textContent = serverIP;
            document.getElementById('serverFlag').className = document.getElementById('userFlag').className;
            
        } catch(e) {
            document.getElementById('userIP').textContent = '127.0.0.1';
            document.getElementById('serverIP').textContent = '192.168.1.1';
        }
    }
    getIPInfo();

    // ============================================
    // 5. TOGGLE TABLE
    // ============================================
    function toggleTable(id) {
        const body = document.getElementById(id);
        const toggle = document.getElementById('toggle' + id.replace('table', ''));
        body.classList.toggle('open');
        toggle.classList.toggle('open');
    }

    // ============================================
    // 6. TERMINAL
    // ============================================
    function toggleTerminal() {
        const output = document.getElementById('terminalOutput');
        output.classList.toggle('show');
        if (output.classList.contains('show')) {
            document.getElementById('terminalMessage').textContent = '✅ Terminal opened';
        }
    }

    function executeTerminalCommand() {
        const input = document.getElementById('terminalCommand');
        const cmd = input.value.trim();
        if (!cmd) return;
        
        input.value = '';
        processCommand(cmd);
    }

    function runQuickCommand(cmd) {
        document.getElementById('terminalCommand').value = cmd;
        executeTerminalCommand();
    }

    function processCommand(cmd) {
        const parts = cmd.split(' ');
        const command = parts[0].toLowerCase();
        const args = parts.slice(1);
        
        addTerminalLine(`$ ${cmd}`);
        
        switch(command) {
            case 'ls':
                const dir = fileSystem[currentPath];
                if (dir && dir.items) {
                    let output = '';
                    if (args.includes('-la') || args.includes('-l')) {
                        dir.items.forEach(item => {
                            const perms = item.perms || '---';
                            const size = String(item.size).padStart(10);
                            output += `${perms} ${size} ${item.modified} ${item.name}\n`;
                        });
                    } else {
                        output = dir.items.map(i => i.name).join('  ');
                    }
                    addTerminalLine(output || '(empty)');
                } else {
                    addTerminalLine('📂 Directory not found');
                }
                break;
                
            case 'pwd':
                addTerminalLine(currentPath);
                break;
                
            case 'cd':
                if (args.length === 0 || args[0] === '~' || args[0] === '/home/admin') {
                    navigateTo('/home/admin/public_html');
                } else if (args[0] === '..') {
                    goUp();
                } else if (args[0] === '/') {
                    navigateTo('/');
                } else {
                    let target = args[0];
                    if (!target.startsWith('/')) {
                        target = currentPath + '/' + target;
                    }
                    // Normalize path
                    target = target.replace(/\/+/g, '/');
                    if (fileSystem[target]) {
                        navigateTo(target);
                    } else {
                        addTerminalLine(`❌ Directory not found: ${target}`);
                    }
                }
                break;
                
            case 'cat':
                if (args.length > 0) {
                    let filePath = args[0];
                    if (!filePath.startsWith('/')) {
                        filePath = currentPath + '/' + filePath;
                    }
                    addTerminalLine(`📄 Content of ${filePath}:`);
                    addTerminalLine(`<?php\n// Sample content for ${filePath}\necho "Hello from ${filePath}";\n?>`);
                } else {
                    addTerminalLine('❌ Usage: cat [filename]');
                }
                break;
                
            case 'clear':
                clearTerminalOutput();
                break;
                
            case 'help':
                addTerminalLine('📚 Available commands:');
                addTerminalLine('  ls, ls -la   - List files');
                addTerminalLine('  pwd          - Show current path');
                addTerminalLine('  cd [dir]     - Change directory');
                addTerminalLine('  cat [file]   - Show file content');
                addTerminalLine('  clear        - Clear terminal');
                addTerminalLine('  help         - Show this help');
                break;
                
            default:
                addTerminalLine(`❌ Unknown command: ${command}. Type 'help' for available commands.`);
        }
        
        document.getElementById('terminalOutput').classList.add('show');
    }

    function addTerminalLine(text) {
        const terminal = document.getElementById('terminalOutput');
        const line = document.createElement('div');
        line.className = 'output-line';
        line.innerHTML = `<span class="prompt">></span> ${text}`;
        const msgContainer = document.getElementById('terminalMessage');
        if (msgContainer) {
            terminal.insertBefore(line, msgContainer.parentElement);
        } else {
            terminal.appendChild(line);
        }
        terminal.scrollTop = terminal.scrollHeight;
    }

    function clearTerminalOutput() {
        const terminal = document.getElementById('terminalOutput');
        terminal.innerHTML = `
            <div class="output-line"><span class="prompt">$</span> Terminal cleared</div>
            <div class="output-line"><span class="prompt">$</span> <span id="terminalMessage">Ready...</span></div>
        `;
    }

    // ============================================
    // 7. MODAL SYSTEM
    // ============================================
    let currentModalType = '';

    function openModal(type, data = null) {
        const overlay = document.getElementById('modalOverlay');
        const title = document.getElementById('modalTitle');
        const content = document.getElementById('modalContent');
        
        currentModalType = type;
        overlay.classList.add('show');
        
        const modals = {
            'edit': {
                title: '<i class="fas fa-edit"></i> Edit File',
                html: `
                    <label>File Path</label>
                    <input type="text" id="modalFilePath" value="${data || currentPath + '/file.php'}">
                    <label>Content</label>
                    <textarea id="modalFileContent" placeholder="Write file content here..."><?php echo "Hello World"; ?></textarea>
                `
            },
            'createfile': {
                title: '<i class="fas fa-file"></i> Create New File',
                html: `
                    <label>File Name</label>
                    <input type="text" id="modalFileName" placeholder="newfile.php">
                    <label>Content (optional)</label>
                    <textarea id="modalFileContent" placeholder="Write content..." style="min-height:80px;"></textarea>
                `
            },
            'createfolder': {
                title: '<i class="fas fa-folder-plus"></i> Create New Folder',
                html: `
                    <label>Folder Name</label>
                    <input type="text" id="modalFolderName" placeholder="new_folder">
                `
            },
            'rename': {
                title: '<i class="fas fa-i-cursor"></i> Rename File/Folder',
                html: `
                    <label>Current Path</label>
                    <input type="text" id="modalRenamePath" value="${data || currentPath + '/file.txt'}">
                    <label>New Name</label>
                    <input type="text" id="modalNewName" placeholder="new_name.txt">
                `
            },
            'upload': {
                title: '<i class="fas fa-upload"></i> Upload File',
                html: `
                    <label>Select File</label>
                    <input type="file" id="modalFileUpload" style="padding:8px;background:rgba(255,255,255,0.05);">
                    <label>Destination Path</label>
                    <input type="text" id="modalUploadPath" value="${currentPath}/">
                `
            }
        };
        
        const modal = modals[type] || modals['edit'];
        title.innerHTML = modal.title;
        content.innerHTML = modal.html;
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('show');
    }

    function submitModal() {
        const type = currentModalType;
        let result = '';
        
        switch(type) {
            case 'edit':
                const filePath = document.getElementById('modalFilePath')?.value || '';
                const content = document.getElementById('modalFileContent')?.value || '';
                result = `✏️ File saved: ${filePath}\nContent updated successfully!`;
                break;
            case 'createfile':
                const fileName = document.getElementById('modalFileName')?.value || 'unnamed';
                const fileContent = document.getElementById('modalFileContent')?.value || '';
                result = `📄 Created file: ${fileName}\nSize: ${fileContent.length} bytes`;
                if (fileSystem[currentPath]) {
                    fileSystem[currentPath].items.push({
                        name: fileName,
                        type: fileName.split('.').pop() || 'txt',
                        size: `${fileContent.length} B`,
                        modified: new Date().toLocaleString(),
                        perms: '644'
                    });
                }
                break;
            case 'createfolder':
                const folderName = document.getElementById('modalFolderName')?.value || 'unnamed';
                result = `📁 Created folder: ${folderName}`;
                if (fileSystem[currentPath]) {
                    const newPath = currentPath + '/' + folderName;
                    fileSystem[currentPath].items.push({
                        name: folderName,
                        type: 'dir',
                        size: '--',
                        modified: new Date().toLocaleString(),
                        perms: '755'
                    });
                    fileSystem[newPath] = { type: 'dir', items: [] };
                }
                break;
            case 'rename':
                const oldPath = document.getElementById('modalRenamePath')?.value || '';
                const newName = document.getElementById('modalNewName')?.value || '';
                result = `✏️ Renamed: ${oldPath} → ${newName}`;
                break;
            case 'upload':
                const fileInput = document.getElementById('modalFileUpload');
                const uploadPath = document.getElementById('modalUploadPath')?.value || currentPath;
                const uploadedFile = fileInput?.files?.[0];
                if (uploadedFile) {
                    result = `📤 Uploaded: ${uploadedFile.name} → ${uploadPath}`;
                    if (fileSystem[currentPath]) {
                        fileSystem[currentPath].items.push({
                            name: uploadedFile.name,
                            type: uploadedFile.name.split('.').pop() || 'file',
                            size: `${(uploadedFile.size / 1024).toFixed(1)} KB`,
                            modified: new Date().toLocaleString(),
                            perms: '644'
                        });
                    }
                } else {
                    result = '⚠️ No file selected';
                }
                break;
            default:
                result = '✅ Action completed!';
        }
        
        closeModal();
        addTerminalLine(result);
        document.getElementById('terminalMessage').textContent = result.split('\n')[0];
        updateFileList();
        updateStats();
    }

    // ============================================
    // 8. COPY PATH
    // ============================================
    function copyPath() {
        navigator.clipboard?.writeText(currentPath).then(() => {
            addTerminalLine('📋 Path copied: ' + currentPath);
        }).catch(() => {
            addTerminalLine('📋 Path: ' + currentPath);
        });
    }

    // ============================================
    // 9. REFRESH FILES
    // ============================================
    function refreshFiles() {
        addTerminalLine('🔄 Refreshing files...');
        setTimeout(() => {
            updateFileList();
            addTerminalLine('✅ Files refreshed!');
        }, 300);
    }

    // ============================================
    // 10. CONFIRM DELETE (Anti-Delete Shell)
    // ============================================
    function confirmDelete(btn, fileName) {
        const protectedFiles = ['index.php', 'config.php', 'wp-config.php', '.htaccess', 'shell.php', 'admin.php'];
        const isProtected = protectedFiles.some(p => fileName.includes(p));
        
        if (isProtected) {
            addTerminalLine(`🚫 Delete blocked: "${fileName}" is protected!`);
            return;
        }
        
        if (confirm(`⚠️ Delete "${fileName}"?\nThis action cannot be undone!`)) {
            const dir = fileSystem[currentPath];
            if (dir && dir.items) {
                const index = dir.items.findIndex(i => i.name === fileName);
                if (index !== -1) {
                    dir.items.splice(index, 1);
                    addTerminalLine(`🗑️ Deleted: ${fileName}`);
                    updateFileList();
                    updateStats();
                }
            }
        }
    }

    // ============================================
    // 11. UPDATE STATS
    // ============================================
    function updateStats() {
        const dir = fileSystem[currentPath];
        const totalItems = dir ? dir.items.length : 0;
        const fileCount = dir ? dir.items.filter(i => i.type !== 'dir').length : 0;
        
        const stats = {
            totalFiles: { min: 5, max: 30, base: fileCount },
            totalUsers: { min: 1, max: 8 },
            totalPlugins: { min: 3, max: 15 },
            totalStorage: { min: 5, max: 40 },
            totalDomains: { min: 1, max: 5 },
            totalSecurity: { min: 0, max: 3 },
            totalCommands: { min: 3, max: 20 }
        };
        
        Object.keys(stats).forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                const range = stats[id];
                let val;
                if (id === 'totalFiles') {
                    val = Math.max(1, range.base + Math.floor(Math.random() * 5));
                } else {
                    val = Math.floor(Math.random() * (range.max - range.min + 1)) + range.min;
                }
                el.textContent = val;
            }
        });
    }

    // ============================================
    // 12. KEYBOARD SHORTCUTS
    // ============================================
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'Enter') {
            e.preventDefault();
            toggleTerminal();
        }
        if (e.key === 'Escape') {
            closeModal();
        }
        if (e.ctrlKey && e.key === 'l') {
            e.preventDefault();
            clearTerminalOutput();
        }
        if (e.ctrlKey && e.shiftKey && e.key === 'T') {
            e.preventDefault();
            document.getElementById('terminalCommand').focus();
        }
    });

    // ============================================
    // 13. INIT
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        renderBreadcrumb();
        updateFileList();
        updateStats();
        
        // Auto show terminal
        setTimeout(() => {
            document.getElementById('terminalOutput').classList.add('show');
        }, 300);
        
        document.getElementById('terminalMessage').textContent = '✅ System ready! Type commands below.';
        
        // Stat cards click
        const cmds = ['ls -la', 'pwd', 'cd', 'clear', 'help', 'ls', 'pwd'];
        document.querySelectorAll('.stat-card').forEach((card, i) => {
            card.addEventListener('click', () => {
                runQuickCommand(cmds[i % cmds.length]);
            });
        });
        
        // Load plugins table
        loadPlugins();
    });

    // ============================================
    // 14. LOAD PLUGINS
    // ============================================
    function loadPlugins() {
        const plugins = [
            { name: 'Security Pro', icon: 'fa-shield-alt', color: '#7b2ffc', version: 'v3.2.1', status: 'active' },
            { name: 'Speed Optimizer', icon: 'fa-rocket', color: '#00d2ff', version: 'v2.5.0', status: 'active' },
            { name: 'SEO Master', icon: 'fa-search', color: '#ffd93d', version: 'v1.8.3', status: 'inactive' },
            { name: 'DB Backup', icon: 'fa-database', color: '#6bcb77', version: 'v4.1.0', status: 'pending' },
            { name: 'Analytics Pro', icon: 'fa-chart-line', color: '#a29bfe', version: 'v3.0.2', status: 'active' }
        ];
        
        const tbody = document.getElementById('pluginTableBody');
        let html = '';
        plugins.forEach(p => {
            const statusMap = {
                'active': '<span class="status active"><span class="status-dot active"></span> Active</span>',
                'inactive': '<span class="status inactive"><span class="status-dot inactive"></span> Inactive</span>',
                'pending': '<span class="status pending"><span class="status-dot pending"></span> Pending</span>'
            };
            const actionMap = {
                'active': '<button class="btn-sm danger">Disable</button>',
                'inactive': '<button class="btn-sm success">Enable</button>',
                'pending': '<button class="btn-sm warning">Update</button>'
            };
            
            html += `<tr>
                <td><i class="fas ${p.icon}" style="color:${p.color};"></i> ${p.name}</td>
                <td>${p.version}</td>
                <td>${statusMap[p.status]}</td>
                <td>${actionMap[p.status]}</td>
            </tr>`;
        });
        tbody.innerHTML = html;
    }

    // ============================================
    // 15. ANTI-DELETE SHELL (Protection)
    // ============================================
    console.log('%c🛡️ MiniShell Pro - Anti-Delete Active', 'color: #7b2ffc; font-size: 16px; font-weight: bold;');
    console.log('%c⚠️ Protected files: index.php, config.php, .htaccess, shell.php, admin.php', 'color: #ffd93d;');
    
    window.addEventListener('beforeunload', function(e) {
        e.preventDefault();
        e.returnValue = '⚠️ Are you sure you want to leave?';
    });
</script>
</body>
</html>