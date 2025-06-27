<?php
ob_start();

session_start();

// Authentication Check
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Start session with secure settings
// session_start([
//     'cookie_lifetime' => 86400,
//     'cookie_secure' => true,
//     'cookie_httponly' => true,
//     'use_strict_mode' => true
// ]);

// Regenerate session ID to prevent fixation
if (!isset($_SESSION['created'])) {
    session_regenerate_id(true);
    $_SESSION['created'] = time();
} elseif (time() - $_SESSION['created'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// User Agent Detection
function detectDeviceType($userAgent)
{
    if (!$userAgent || !is_string($userAgent)) {
        return 'Unknown';
    }

    $ua = strtolower($userAgent);

    $deviceTypes = [
        'iphone|ipad' => 'iOS',
        'android' => 'Android',
        'windows' => 'Windows',
        'macintosh|mac os' => 'Mac',
        'linux' => 'Linux'
    ];

    foreach ($deviceTypes as $pattern => $type) {
        if (preg_match("/$pattern/", $ua)) {
            return $type;
        }
    }

    return 'Unknown';
}

// Get User IP with validation
function getUserIP()
{
    $ip = 'Unknown';

    if (!empty($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($ips as $tmpIp) {
            if (filter_var(trim($tmpIp), FILTER_VALIDATE_IP)) {
                $ip = trim($tmpIp);
                break;
            }
        }
    } elseif (!empty($_SERVER['REMOTE_ADDR']) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

// Form Submission Handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token validation failed');
    }

    try {
        // Validate inputs
        $required = ['name', 'username', 'password', 'email', 'status'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("$field is required");
            }
        }

        // Sanitize inputs
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $status = in_array($_POST['status'], ['Active', 'Pending', 'Inactive']) ? $_POST['status'] : 'Pending';

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Check if username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            throw new Exception("Username already exists");
        }

        // Hash password
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Insert user
        $stmt = $pdo->prepare("INSERT INTO users (name, username, password, email, address, device, status) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $name,
            $username,
            $password,
            $email,
            getUserIP(),
            detectDeviceType($_SERVER['HTTP_USER_AGENT']),
            $status
        ]);

        $_SESSION['message'] = "User added successfully";
        header("Location: index.php");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Delete User Handling
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['message'] = "User deleted successfully";
        } catch (PDOException $e) {
            $error = "Error deleting user: " . $e->getMessage();
        }
    }
    header("Location: index.php");
    exit;
}
// Theme Management
$_SESSION['theme'] = $_SESSION['theme'] ?? 'system';

$themeClass = match ($_SESSION['theme']) {
    'dark' => 'bg-dark text-white',
    'light' => 'bg-light text-dark',
    'success' => 'bg-success text-white',
    default => '',
};
ob_end_flush();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminHub - User Management</title>


    <!-- Bootstrap CDN Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        a {
            text-decoration: none;
        }

        li {
            list-style: none;
        }

        :root {
            --poppins: 'Poppins', sans-serif;
            --lato: 'Lato', sans-serif;
            --light: #F9F9F9;
            --blue: #3C91E6;
            --light-blue: #CFE8FF;
            --grey: #eee;
            --dark-grey: #AAAAAA;
            --dark: #342E37;
            --red: #DB504A;
            --yellow: #FFCE26;
            --light-yellow: #FFF2C6;
            --orange: #FD7238;
            --light-orange: #FFE0D3;
        }

        html {
            overflow-x: hidden;
        }

        body.dark {
            --light: #0C0C1E;
            --grey: #060714;
            --dark: #FBFBFB;
        }

        body {
            background: var(--grey);
            overflow-x: hidden;
            font-family: var(--poppins);
        }

        /* SIDEBAR */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100%;
            background: var(--light);
            z-index: 2000;
            font-family: var(--lato);
            transition: width 0.3s ease-in-out;
            overflow-x: hidden;
            scrollbar-width: none;
        }

        #sidebar::-webkit-scrollbar {
            display: none;
        }

        #sidebar.hide {
            width: 60px;
        }

        /* #sidebar.hide .side-menu li a {
            width: 48px;
            transition: width .3s ease;
            justify-content: center;
            padding: 0;
        }

        #sidebar.hide .side-menu li a .bx,
        #sidebar.hide .side-menu li a i {
            min-width: auto;
            margin: 0;
            font-size: 1.2rem;
        } */

        #sidebar .brand {
            font-size: 24px;
            font-weight: 700;
            height: 56px;
            display: flex;
            align-items: center;
            color: var(--blue);
            position: sticky;
            top: 0;
            left: 0;
            background: var(--light);
            z-index: 500;
            /* padding-bottom: 20px; */
            box-sizing: content-box;
        }

        #sidebar .brand .bx {
            min-width: 60px;
            display: flex;
            justify-content: center;
        }

        #sidebar .side-menu {
            width: 100%;
            margin-top: 48px;
        }

        #sidebar .side-menu li {
            height: 48px;
            background: transparent;
            margin-left: 6px;
            border-radius: 48px 0 0 48px;
            padding: 4px;
        }

        #sidebar .side-menu li.active {
            background: var(--grey);
            position: relative;
        }

        #sidebar .side-menu li.active::before {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            top: -40px;
            right: 0;
            box-shadow: 20px 20px 0 var(--grey);
            z-index: -1;
        }

        #sidebar .side-menu li.active::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            bottom: -40px;
            right: 0;
            box-shadow: 20px -20px 0 var(--grey);
            z-index: -1;
        }

        #sidebar .side-menu li a {
            width: 100%;
            height: 100%;
            background: var(--light);
            display: flex;
            align-items: center;
            border-radius: 48px;
            font-size: 16px;
            color: var(--dark);
            white-space: nowrap;
            overflow-x: hidden;
        }

        #sidebar .side-menu.top li.active a {
            color: var(--blue);
        }

        #sidebar.hide .side-menu li a {
            width: calc(48px - (4px * 2));
            transition: width .3s ease;
        }

        #sidebar .side-menu li a.logout {
            color: var(--red);
        }

        #sidebar .side-menu.top li a:hover {
            color: var(--blue);
        }

        #sidebar .side-menu li a .bx {
            min-width: calc(60px - ((4px + 6px) * 2));
            display: flex;
            justify-content: center;
        }

        #sidebar .side-menu.bottom li:nth-last-of-type(-n+2) {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
        }

        #sidebar .side-menu.bottom li:nth-last-of-type(2) {
            bottom: 40px;
        }

        /* CONTENT */
        #content {
            position: relative;
            width: calc(100% - 220px);
            left: 220px;
            transition: .3s ease;
        }

        #sidebar.hide~#content {
            width: calc(100% - 60px);
            left: 60px;
        }

        /* NAVBAR */
        #content nav {
            height: 56px;
            background: var(--light);
            padding: 0 24px;
            display: flex;
            align-items: center;
            grid-gap: 24px;
            font-family: var(--lato);
            position: sticky;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        #content nav::before {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            bottom: -40px;
            left: 0;
            border-radius: 50%;
            box-shadow: -20px -20px 0 var(--light);
        }

        #content nav a {
            color: var(--dark);
        }

        #content nav .bx.bx-menu {
            cursor: pointer;
            color: var(--dark);
        }

        #content nav .nav-link {
            font-size: 16px;
            transition: .3s ease;
        }

        #content nav .nav-link:hover {
            color: var(--blue);
        }

        #content nav form {
            max-width: 400px;
            width: 100%;
            margin-right: auto;
        }

        #content nav form .form-input {
            display: flex;
            align-items: center;
            height: 36px;
        }

        #content nav form .form-input input {
            flex-grow: 1;
            padding: 0 16px;
            height: 100%;
            border: none;
            background: var(--grey);
            border-radius: 36px 0 0 36px;
            outline: none;
            width: 100%;
            color: var(--dark);
        }

        #content nav form .form-input button {
            width: 36px;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--blue);
            color: var(--light);
            font-size: 18px;
            border: none;
            outline: none;
            border-radius: 0 36px 36px 0;
            cursor: pointer;
        }

        #content nav .notification {
            font-size: 20px;
            position: relative;
        }

        #content nav .notification .num {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid var(--light);
            background: var(--red);
            color: var(--light);
            font-weight: 700;
            font-size: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #content nav .profile img {
            width: 36px;
            height: 36px;
            object-fit: cover;
            border-radius: 50%;
        }

        /* MAIN CONTENT */
        #content main {
            width: 100%;
            padding: 36px 24px;
            font-family: var(--poppins);
            max-height: calc(100vh - 56px);
            overflow-y: auto;
        }

        #content main .head-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            grid-gap: 16px;
            flex-wrap: wrap;
        }

        #content main .head-title .left h1 {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark);
        }

        #content main .head-title .left .breadcrumb {
            display: flex;
            align-items: center;
            grid-gap: 16px;
        }

        #content main .head-title .left .breadcrumb li {
            color: var(--dark);
        }

        #content main .head-title .left .breadcrumb li a {
            color: var(--dark-grey);
            pointer-events: none;
        }

        #content main .head-title .left .breadcrumb li a.active {
            color: var(--blue);
            pointer-events: unset;
        }

        #content main .head-title .btn-download {
            height: 36px;
            padding: 0 16px;
            border-radius: 36px;
            background: var(--blue);
            color: var(--light);
            display: flex;
            justify-content: center;
            align-items: center;
            grid-gap: 10px;
            font-weight: 500;
        }

        /* FORM STYLING */
        .user-form {
            background: var(--light);
            padding: 24px;
            border-radius: 20px;
            margin-bottom: 24px;
        }

        .user-form .form-group {
            margin-bottom: 16px;
        }

        .user-form label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark);
            font-weight: 500;
        }

        .user-form input,
        .user-form select {
            width: 100%;
            padding: 10px 16px;
            border: 1px solid var(--grey);
            border-radius: 8px;
            background: var(--light);
            color: var(--dark);
        }

        .user-form button {
            background: var(--blue);
            color: var(--light);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
        }

        /* TABLE STYLING */
        .table-container {
            background: var(--light);
            border-radius: 20px;
            padding: 24px;
            overflow-x: auto;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--grey);
            color: var(--dark);
            font-weight: 600;
        }

        .table-container td {
            padding: 16px;
            border-bottom: 1px solid var(--grey);
            color: var(--dark);
        }

        .table-container tr:last-child td {
            border-bottom: none;
        }

        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status.active {
            background: var(--blue);
            color: white;
        }

        .status.pending {
            background: var(--yellow);
            color: var(--dark);
        }

        .status.inactive {
            background: var(--red);
            color: white;
        }

        .action-btn {
            color: var(--red);
            cursor: pointer;
        }

        /* Responsive styles */
        /* Responsive styles */
        @media screen and (max-width: 1200px) {
            #sidebar {
                width: 200px;
            }

            #content {
                width: calc(100% - 200px);
                left: 200px;
            }
        }

        @media screen and (max-width: 992px) {
            #sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 3000;
            }

            #sidebar {
                transition: transform 0.3s ease, width 0.3s ease;
            }

            #sidebar.show {
                transform: translateX(0);
            }

            #sidebar:not(.show) {
                transform: translateX(-100%);
            }

            #sidebar.show {
                transform: translateX(0);
            }

            #content {
                width: 100%;
                left: 0;
            }

            #content nav .bx-menu {
                display: block !important;
            }

            .table-container {
                overflow-x: auto;
            }

            .user-form {
                width: 100% !important;
                margin: 1rem auto !important;
            }
        }

        @media screen and (max-width: 768px) {
            #content nav .nav-link {
                display: none;
            }

            #content nav form {
                max-width: 300px;
            }

            .head-title .left h1 {
                font-size: 24px !important;
            }

            #sidebar {
                transition: transform 0.3s ease, width 0.3s ease;
            }

            #sidebar.show {
                transform: translateX(0);
            }

            #sidebar:not(.show) {
                transform: translateX(-100%);
            }
        }

        @media screen and (max-width: 576px) {
            #content nav form .form-input input {
                display: none;
            }

            #content nav form .form-input button {
                background: transparent;
                color: var(--dark);
            }

            #content nav form.show .form-input input {
                display: block;
            }

            #content nav form.show .form-input button {
                background: var(--blue);
                color: var(--light);
            }

            .table-container {
                padding: 16px;
            }

            .table-container th,
            .table-container td {
                padding: 8px 12px;
            }

            .user-form {
                padding: 16px !important;
            }

            #developer {
                font-size: 0.8rem;
                width: 100%;
                left: 0;
            }

            #sidebar {
                transition: transform 0.3s ease, width 0.3s ease;
            }

            #sidebar.show {
                transform: translateX(0);
            }

            #sidebar:not(.show) {
                transform: translateX(-100%);
            }
        }

        @media screen and (max-width: 768px) {
            .table-container table {
                display: block;
            }

            .table-container thead,
            .table-container tbody,
            .table-container tr,
            .table-container th,
            .table-container td {
                display: block;
            }

            .table-container thead {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            .table-container tr {
                margin-bottom: 1rem;
                border: 1px solid var(--grey);
                border-radius: 8px;
            }

            .table-container td {
                border: none;
                position: relative;
                padding-left: 50%;
            }

            .table-container td:before {
                position: absolute;
                left: 12px;
                content: attr(data-label);
                font-weight: 600;
            }

            #sidebar {
                transition: transform 0.3s ease, width 0.3s ease;
            }

            #sidebar.show {
                transform: translateX(0);
            }

            #sidebar:not(.show) {
                transform: translateX(-100%);
            }
        }
    </style>
</head>

<body class="<?= htmlspecialchars($themeClass) ?>">
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-shield-alt bx-lg'></i>
            <span class="text">AdminHub</span>
        </a>
        <ul class="side-menu top">
            <li class="active">
                <a href="#">
                    <i class='bx bxs-dashboard bx-sm'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bxs-user bx-sm'></i>
                    <span class="text">Users</span>
                </a>
            </li>
            <li>
                <a href="target.php" class="<?= basename($_SERVER['PHP_SELF']) == 'target.php' ? 'active-link' : '' ?>">
                    <i class='bx bxs-bullseye bx-sm'></i>
                    <span class="text">Target</span>
                </a>
            </li>
            <li>
                <a href="about.php" class="<?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active-link' : '' ?>">
                    <i class="bx bi-info-circle-fill bx-sm"></i>
                    <span class="text"> About</span>
                </a>
            </li>
            <li>
                <a href="setting.php"
                    class="<?= basename($_SERVER['PHP_SELF']) == 'setting.php' ? 'active-link' : '' ?>">
                    <i class='bx bxs-cog bx-sm'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu bottom">
            <li>
                <a href="#" class="logout">
                    <i class='bx bx-power-off bx-sm bx-burst-hover'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu bx-sm' aria-label="Toggle menu" aria-expanded="false"></i>
            <form onsubmit="return false;">
                <div class="form-input">
                    <input type="search" id="liveSearch" placeholder="Search users...">
                    <button type="submit"><i class='bx bx-search'></i></button>
                </div>
            </form>

            <a href="#" class="notification">
                <i class='bx bxs-bell bx-tada-hover'></i>
                <span class="num">3</span>
            </a>
            <a href="#" class="profile">
                <img src="sanjeet.jpg   " alt="Profile">
            </a>
        </nav>

        <!-- MAIN -->
        <main>
            <?php if (isset($_SESSION['message'])): ?>
                <div id="successMsg" class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['message']) ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div id="errorMsg" class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="head-title">
                <div class="left">
                    <h1>User Management</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="index.php">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Users</a>
                        </li>
                    </ul>
                </div>
                <a href="?q=add" class="btn btn-primary">Add User</a>
            </div>

            <?php if (!isset($_GET['q']) || $_GET['q'] !== 'add'): ?>
                <!-- Users Table -->
                <div class="table-container mt-4">
                    <h3>User Records</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>IP Address</th>
                                <th>Device</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $search = $_GET['q'] ?? '';
                            $query = "SELECT * FROM users";
                            $params = [];

                            if (!empty($search) && $search !== 'add') {
                                $query .= " WHERE name LIKE ? OR username LIKE ? OR email LIKE ?";
                                $params = ["%$search%", "%$search%", "%$search%"];
                            }

                            $query .= " ORDER BY created_at DESC";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute($params);

                            while ($row = $stmt->fetch()):
                                ?>
                                <tr>
                                    <td data-label="ID"><?= htmlspecialchars($row['id']) ?></td>
                                    <td data-label="Name"><?= htmlspecialchars($row['name']) ?></td>
                                    <td data-label="Username"><?= htmlspecialchars($row['username']) ?></td>
                                    <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
                                    <td data-label="IP"><?= htmlspecialchars($row['address'] ?? 'Unknown') ?></td>
                                    <td data-label="Device"><?= htmlspecialchars($row['device'] ?? 'Unknown') ?></td>
                                    <td data-label="Status">
                                        <span class="status <?= strtolower($row['status']) ?>">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </td>
                                    <td data-label="Action">
                                        <a href="?delete=<?= $row['id'] ?>" class="action-btn"
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class='bx bx-trash'></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['q']) && $_GET['q'] === 'add'): ?>
                <div class="main-content mx-auto" style="width: 90%; max-width: 600px; margin: 1rem auto;">
                    <h3>Add New User</h3>
                    <form method="POST" class="mb-4 p-3 <?= $themeClass ?> rounded">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <div class="row g-3">
                            <div class="col">
                                <label for="name">Full Name</label>
                                <input type="text" name="name" id="name" class="form-control" required minlength="2"
                                    maxlength="50">
                            </div>
                            <div class="col">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required minlength="3"
                                    maxlength="30">
                            </div>
                            <div class="col">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" required minlength="8">
                            </div>
                            <div class="col">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" required>
                            </div>
                            <div class="col">
                                <label for="status">Status</label>
                                <select name="status" id="status" required>
                                    <option value="Active">Active</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Add User</button>
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
                <?php include "cursor.php" ?>
            <div class="col-9 text-center text-secondary pt-2 small mx-auto"
                style="position: fixed; bottom: 0.5rem;text-shadow: 0 0 30px #0f0; cursor: none;margin: auto; overflow: hidden;"
                id="developer">
                Developer ❤️ by Sanjeet Kalyan
            </div>
        </main>
    </section>

    <script>
        // Mobile sidebar toggle
        const mobileMenuButton = document.querySelector('#content nav .bx.bx-menu'); // Fixed selector - added second .bx class
        const sidebar = document.getElementById('sidebar');

        mobileMenuButton.addEventListener('click', function () {
            if (window.innerWidth <= 992) {
                sidebar.classList.toggle('show');
            } else {
                sidebar.classList.toggle('hide');
            }
        });

        mobileMenuButton.addEventListener('click', function () {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            // ... rest of your code
        });
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 992 && !sidebar.contains(e.target)) { // Added missing parenthesis
                if (e.target !== mobileMenuButton && !mobileMenuButton.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Window resize handler
        window.addEventListener('resize', function () {
            if (window.innerWidth > 992) {
                sidebar.classList.remove('show');
                // Also ensure sidebar isn't hidden on larger screens if it should be visible
                if (!sidebar.classList.contains('hide')) {
                    sidebar.classList.remove('hide');
                }
            }
        });

        // Dark mode toggle (you can implement this if needed)
        const switchMode = document.getElementById('switch-mode');
        if (switchMode) {
            switchMode.addEventListener('change', function () {
                if (this.checked) {
                    document.body.classList.add('dark');
                } else {
                    document.body.classList.remove('dark');
                }
            });
        }

        let serialCounter = 1;

        function showForm() {
            document.getElementById("addForm").style.display = "block";
        }

        function closeForm() {
            document.getElementById("addForm").style.display = "none";
        }

        function updateSerialNumbers() {
            const rows = document.querySelectorAll("#dataRows .row");
            rows.forEach((row, index) => {
                const serialCol = row.querySelector(".col");
                if (serialCol) {
                    serialCol.textContent = index + 1;
                }
            });
        }

        function submitForm() {
            const name = document.getElementById("inputName").value;
            const username = document.getElementById("inputUsername").value;
            const password = document.getElementById("inputPassword").value;
            const email = document.getElementById("inputEmail").value;

            if (!name || !username || !password || !email) {
                alert("Please fill in all fields.");
                return;
            }

            const row = document.createElement("div");
            row.className = "row text-center py-2 border-bottom align-items-center";
            row.innerHTML = `
                <div class="col">${serialCounter++}</div>
                <div class="col text-secondary">Pending</div>
                <div class="col">${name}</div>
                <div class="col">${username}</div>
                <div class="col">••••••••</div>
                <div class="col">${email}</div>
                <div class="col">-</div>
                <div class="col">-</div>
                <div class="col">
                    <span class="delete-btn" onclick="confirmDelete(this)">🗑</span>
                    <span class="text-warning" style="cursor:pointer" onclick="openUpdateForm()">✏️</span>
                </div>
            `;
            document.getElementById("dataRows").appendChild(row);

            document.getElementById("inputName").value = "";
            document.getElementById("inputUsername").value = "";
            document.getElementById("inputPassword").value = "";
            document.getElementById("inputEmail").value = "";

            closeForm();
        }

        function confirmDelete(element) {
            if (confirm("Are you sure you want to delete this target?")) {
                element.closest(".row").remove();
                serialCounter--;
                updateSerialNumbers();
            }
        }

        function openUpdateForm() {
            const form = document.getElementById("updateFormContainer");
            form.style.display = "block";

            const serialSelect = document.getElementById("updateSerialSelect");
            const nameSelect = document.getElementById("updateNameSelect");

            serialSelect.innerHTML = "";
            nameSelect.innerHTML = "";

            const rows = document.querySelectorAll("#dataRows .row");

            rows.forEach((row, index) => {
                const serialOption = document.createElement("option");
                serialOption.value = index;
                serialOption.textContent = index + 1;
                serialSelect.appendChild(serialOption);

                const name = row.querySelectorAll(".col")[2].textContent.trim();
                const nameOption = document.createElement("option");
                nameOption.value = index;
                nameOption.textContent = name;
                nameSelect.appendChild(nameOption);
            });
        }

        function closeUpdateForm() {
            document.getElementById("updateFormContainer").style.display = "none";
        }

        function submitUpdateForm(e) {
            e.preventDefault();

            const serialIndex = document.getElementById("updateSerialSelect").value;
            const nameIndex = document.getElementById("updateNameSelect").value;

            if (serialIndex !== nameIndex) {
                alert("Serial and Name do not match!");
                return;
            }

            const row = document.querySelectorAll("#dataRows .row")[serialIndex];
            const cols = row.querySelectorAll(".col");

            const newStatus = document.getElementById("updateStatus").value;
            const newEmail = document.getElementById("updateEmail").value;

            if (newStatus) {
                cols[1].textContent = newStatus;
                cols[1].className = "col text-" + (newStatus === "Active" ? "success" : newStatus === "Pending" ? "warning" : "secondary");
            }

            if (newEmail) {
                cols[5].textContent = newEmail;
            }

            closeUpdateForm();
        }

        // block Inspect mode

        document.addEventListener('contextmenu', event => event.preventDefault());
        document.onkeydown = function (e) {
            if (event.keyCode == 123) {
                return false;
            }
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
                return false;
            }
            if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
                return false;
            }
            if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
                return "You are not allowed.";
            }
        }


        // Zoom Control
        document.addEventListener('wheel', function (e) {
            if (e.ctrlKey) {
                e.preventDefault();
                return false;
            }
        }, { passive: false });

        // Also prevent mobile pinch zoom
        document.addEventListener('touchmove', function (e) {
            if (e.scale !== 1) {
                e.preventDefault();
            }
        }, { passive: false });

        // Zoom Control
        // Add this to your existing script
        const zoomController = {
            init() {
                // Prevent keyboard zoom
                document.addEventListener('keydown', this.handleKeyZoom);

                // Prevent wheel zoom
                document.addEventListener('wheel', this.handleWheelZoom, { passive: false });

                // Prevent touch zoom
                document.addEventListener('touchmove', this.handleTouchZoom, { passive: false });
            },

            handleKeyZoom(e) {
                if ((e.ctrlKey || e.metaKey) &&
                    (e.key === '+' || e.key === '-' || e.key === '0' ||
                        e.key === '=' || e.key === '_')) {
                    e.preventDefault();
                }
            },

            handleWheelZoom(e) {
                if (e.ctrlKey || e.metaKey) {
                    e.preventDefault();
                }
            },

            handleTouchZoom(e) {
                if (e.touches.length > 1) {
                    e.preventDefault();
                }
            }
        };



        // Auto-hide success and error messages after 5 seconds (5000 milliseconds)
        setTimeout(() => {
            const success = document.getElementById('successMsg');
            const error = document.getElementById('errorMsg');
            if (success) success.style.display = 'none';
            if (error) error.style.display = 'none';
        }, 2500);


        // Live Time Search Bars
        document.getElementById('liveSearch').addEventListener('input', function () {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll(".table-container tbody tr");

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });
    </script>
</body>

</html>