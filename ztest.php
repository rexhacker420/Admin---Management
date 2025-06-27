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
        header("Location: dashboard.php");
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
    header("Location: dashboard.php");
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

    <!-- Bootstrap CDN Links -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" /> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- My CSS -->
    <!-- <link rel="stylesheet" href="style.css"> -->
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
            transition: .3s ease;
            overflow-x: hidden;
            scrollbar-width: none;
        }

        #sidebar::--webkit-scrollbar {
            display: none;
        }

        #sidebar.hide {
            width: 60px;
        }

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
            padding-bottom: 20px;
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
            /* Son iki <li>'yi seç */
            position: absolute;
            /* Ebeveynine göre konumlandır */
            bottom: 0;
            /* En alt */
            left: 0;
            right: 0;
            text-align: center;
        }

        /* Birbirinin üzerine binmesini engellemek için */
        #sidebar .side-menu.bottom li:nth-last-of-type(2) {
            bottom: 40px;
            /* İkinci son öğeyi yukarı kaydır */
        }

        /* SIDEBAR */





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

        /* Notification Dropdown */
        #content nav .notification-menu {
            display: none;
            position: absolute;
            top: 56px;
            right: 0;
            background: var(--light);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            width: 250px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 9999;
            font-family: var(--lato);
        }

        #content nav .notification-menu ul {
            list-style: none;
            padding: 10px;
            margin: 0;
        }

        #content nav .notification-menu li {
            padding: 10px;
            border-bottom: 1px solid var(--grey);
            color: var(--dark);
        }

        #content nav .notification-menu li:hover {
            background-color: var(--light-blue);
            color: var(--dark);
        }

        #content nav .notification-menu li:hover a {
            background-color: var(--dark-grey);
            color: var(--light);
        }

        body.dark #content nav .notification-menu li:hover {
            background-color: var(--light-blue);
            color: var(--light);
        }

        body.dark #content nav .notification-menu li a {
            background-color: var(--dark-grey);
            color: var(--light);
        }

        #content nav .profile img {
            width: 36px;
            height: 36px;
            object-fit: cover;
            border-radius: 50%;
        }

        #content nav .profile-menu {
            display: none;
            position: absolute;
            top: 56px;
            right: 0;
            background: var(--light);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            width: 200px;
            z-index: 9999;
            font-family: var(--lato);
        }

        #content nav .profile-menu ul {
            list-style: none;
            padding: 10px;
            margin: 0;
        }

        #content nav .profile-menu li {
            padding: 10px;
            border-bottom: 1px solid var(--grey);
        }

        #content nav .profile-menu li:hover {
            background-color: var(--light-blue);
            color: var(--dark);
        }

        #content nav .profile-menu li a {
            color: var(--dark);
            font-size: 16px;
        }

        body.dark #content nav .profile-menu li:hover a {
            color: var(--light);
        }

        body.dark #content nav .profile-menu li a {
            color: var(--dark);
        }

        #content nav .profile-menu li:hover a {
            color: var(--dark);
        }

        /* Active State for Menus */
        #content nav .notification-menu.show,
        #content nav .profile-menu.show {
            display: block;
        }

        #content nav .switch-mode {
            display: block;
            min-width: 50px;
            height: 25px;
            border-radius: 25px;
            background: var(--grey);
            cursor: pointer;
            position: relative;
        }

        #content nav .switch-mode::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            bottom: 2px;
            width: calc(25px - 4px);
            background: var(--blue);
            border-radius: 50%;
            transition: all .3s ease;
        }

        #content nav #switch-mode:checked+.switch-mode::before {
            left: calc(100% - (25px - 4px) - 2px);
        }


        #content nav .swith-lm {
            background-color: var(--grey);
            border-radius: 50px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 3px;
            position: relative;
            height: 21px;
            width: 45px;
            transform: scale(1.5);
        }

        #content nav .swith-lm .ball {
            background-color: var(--blue);
            border-radius: 50%;
            position: absolute;
            top: 2px;
            left: 2px;
            height: 20px;
            width: 20px;
            transform: translateX(0px);
            transition: transform 0.2s linear;
        }

        #content nav .checkbox:checked+.swith-lm .ball {
            transform: translateX(22px);
        }

        .bxs-moon {
            color: var(--yellow);
        }

        .bx-sun {
            color: var(--orange);
            animation: shakeOn .7s;
        }



        /* NAVBAR */





        /* MAIN */
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




        #content main .box-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            grid-gap: 24px;
            margin-top: 36px;
        }

        #content main .box-info li {
            padding: 24px;
            background: var(--light);
            border-radius: 20px;
            display: flex;
            align-items: center;
            grid-gap: 24px;
        }

        #content main .box-info li .bx {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            font-size: 36px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #content main .box-info li:nth-child(1) .bx {
            background: var(--light-blue);
            color: var(--blue);
        }

        #content main .box-info li:nth-child(2) .bx {
            background: var(--light-yellow);
            color: var(--yellow);
        }

        #content main .box-info li:nth-child(3) .bx {
            background: var(--light-orange);
            color: var(--orange);
        }

        #content main .box-info li .text h3 {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
        }

        #content main .box-info li .text p {
            color: var(--dark);
        }





        #content main .table-data {
            display: flex;
            flex-wrap: wrap;
            grid-gap: 24px;
            margin-top: 24px;
            width: 100%;
            color: var(--dark);
        }

        #content main .table-data>div {
            border-radius: 20px;
            background: var(--light);
            padding: 24px;
            overflow-x: auto;
        }

        #content main .table-data .head {
            display: flex;
            align-items: center;
            grid-gap: 16px;
            margin-bottom: 24px;
        }

        #content main .table-data .head h3 {
            margin-right: auto;
            font-size: 24px;
            font-weight: 600;
        }

        #content main .table-data .head .bx {
            cursor: pointer;
        }

        #content main .table-data .order {
            flex-grow: 1;
            flex-basis: 500px;
        }

        #content main .table-data .order table {
            width: 100%;
            border-collapse: collapse;
        }

        #content main .table-data .order table th {
            padding-bottom: 12px;
            font-size: 13px;
            text-align: left;
            border-bottom: 1px solid var(--grey);
        }

        #content main .table-data .order table td {
            padding: 16px 0;
        }

        #content main .table-data .order table tr td:first-child {
            display: flex;
            align-items: center;
            grid-gap: 12px;
            padding-left: 6px;
        }

        #content main .table-data .order table td img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        #content main .table-data .order table tbody tr:hover {
            background: var(--grey);
        }

        #content main .table-data .order table tr td .status {
            font-size: 10px;
            padding: 6px 16px;
            color: var(--light);
            border-radius: 20px;
            font-weight: 700;
        }

        #content main .table-data .order table tr td .status.completed {
            background: var(--blue);
        }

        #content main .table-data .order table tr td .status.process {
            background: var(--yellow);
        }

        #content main .table-data .order table tr td .status.pending {
            background: var(--orange);
        }


        #content main .table-data .todo {
            flex-grow: 1;
            flex-basis: 300px;
        }

        #content main .table-data .todo .todo-list {
            width: 100%;
        }

        #content main .table-data .todo .todo-list li {
            width: 100%;
            margin-bottom: 16px;
            background: var(--grey);
            border-radius: 10px;
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #content main .table-data .todo .todo-list li .bx {
            cursor: pointer;
        }

        #content main .table-data .todo .todo-list li.completed {
            border-left: 10px solid var(--blue);
        }

        #content main .table-data .todo .todo-list li.not-completed {
            border-left: 10px solid var(--orange);
        }

        #content main .table-data .todo .todo-list li:last-child {
            margin-bottom: 0;
        }

        /* MAIN */
        /* CONTENT */
        #content main .menu,
        #content nav .menu {

            display: none;
            list-style-type: none;
            padding-left: 20px;
            margin-top: 5px;
            position: absolute;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 200px;
        }

        #content main .menu a,
        #content nav .menu a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 8px 16px;
        }

        #content main .menu a:hover,
        #content nav .menu a:hover {
            background-color: #444;
        }

        #content main .menu-link,
        #content nav .menu-link {
            margin: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            color: #007bff;
        }

        #content main .menu-link:hover,
        #content nav .menu-link:hover {
            text-decoration: underline;
        }





        /* Media Query for Smaller Screens */
        @media screen and (max-width: 768px) {

            /* Reduce width of notification and profile menu */
            #content nav .notification-menu,
            #content nav .profile-menu {
                width: 180px;
            }

            #sidebar {
                width: 200px;
            }

            #content {
                width: calc(100% - 60px);
                left: 200px;
            }

            #content nav .nav-link {
                display: none;
            }
        }




        @media screen and (max-width: 576px) {

            #content nav .notification-menu,
            #content nav .profile-menu {
                width: 150px;
            }

            #content nav form .form-input input {
                display: none;
            }

            #content nav form .form-input button {
                width: auto;
                height: auto;
                background: transparent;
                border-radius: none;
                color: var(--dark);
            }

            #content nav form.show .form-input input {
                display: block;
                width: 100%;
            }

            #content nav form.show .form-input button {
                width: 36px;
                height: 100%;
                border-radius: 0 36px 36px 0;
                color: var(--light);
                background: var(--red);
            }

            #content nav form.show~.notification,
            #content nav form.show~.profile {
                display: none;
            }

            #content main .box-info {
                grid-template-columns: 1fr;
            }

            #content main .table-data .head {
                min-width: 420px;
            }

            #content main .table-data .order table {
                min-width: 420px;
            }

            #content main .table-data .todo .todo-list {
                min-width: 420px;
            }
        }
    </style>

    <title>AdminHub</title>
</head>

<body>
    <!-- SIDEBAR -->
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

    <!-- SIDEBAR -->



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
        <!-- NAVBAR -->


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
                            <a href="dashboard.php">Dashboard</a>
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
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" required minlength="8">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" required>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" required>
                                    <option value="Active">Active</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Add User</button>
                            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
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
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <!-- <script src="script.js"></script> -->
    <script>
        // Mobile sidebar toggle
        const mobileMenuButton = document.querySelector('#content nav .bx.bx-menu');
        const sidebar = document.getElementById('sidebar');

        mobileMenuButton.addEventListener('click', function () {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);

            if (window.innerWidth <= 992) {
                sidebar.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
            } else {
                sidebar.classList.toggle('hide');
            }
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 992 && !sidebar.contains(e.target)) {
                if (e.target !== mobileMenuButton && !mobileMenuButton.contains(e.target)) {
                    sidebar.classList.remove('show');
                    document.body.style.overflow = '';
                    mobileMenuButton.setAttribute('aria-expanded', 'false');
                }
            }
        });

        // Window resize handler
        window.addEventListener('resize', function () {
            if (window.innerWidth > 992) {
                sidebar.classList.remove('show');
                document.body.style.overflow = '';
                mobileMenuButton.setAttribute('aria-expanded', 'false');

                // Reset sidebar to default state on larger screens
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