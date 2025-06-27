<?php
session_start();
// Ensure user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Default values
$_SESSION['admin_name'] = $_SESSION['admin_name'] ?? 'Sanjeet Kalyan';
$_SESSION['theme'] = $_SESSION['theme'] ?? 'system';
$theme = $_SESSION['theme'];

// Handle theme switch
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['theme'])) {
    $_SESSION['theme'] = $_POST['theme'];
    $theme = $_POST['theme'];
}

// Handle password change
$password_change_success = '';
$password_change_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
    // Dummy current password check (replace with DB check)
    $storedPassword = 'admin123'; // Replace with actual user DB password hash
    if ($_POST['current_password'] !== $storedPassword) {
        $password_change_error = 'Current password is incorrect.';
    } elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
        $password_change_error = 'New passwords do not match.';
    } else {
        // Password change logic (save to DB)
        $password_change_success = 'Password changed successfully!';
    }
}

// Theme CSS classes
$_SESSION['theme'] = $_SESSION['theme'] ?? 'system';

$themeClass = match ($_SESSION['theme']) {
    'dark' => 'bg-dark text-white',
    'light' => 'bg-light text-dark',
    'success' => 'bg-success text-white',
    default => '',
};
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Settings - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            cursor: none;
            overflow: hidden;
        }

        .sidebar {
            min-height: 100vh;
            width: 250px;
            position: fixed;
            background-color: #212529;
            color: white;
            padding-top: 20px;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }

        .form-control {
            background-color: #495057;
            color: white;
            border: none;
        }

        .nav-link.active-link {
            font-weight: bold;
            background-color: #343a40;
            color: white !important;
        }
    </style>
</head>

<body class="<?= $themeClass ?>">

    <div class="sidebar">
        <h4 class="text-center text-white mb-4">Admin Panel</h4>
        <ul class="nav flex-column px-4">
            <li class="nav-item"><a class="nav-link text-white" href="index.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="target.php">Target</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="about.php">About</a></li>
            <li class="nav-item"><a class="nav-link active-link" href="#">Settings</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
        </ul>
    </div>
    <footer>
        <div class="sidebar-footer mb-5 float-bottom ms-2 mx-2">Developer ❤️ by Sanjeet Kalyan</div>
    </footer>
    <div class="main-content col-10 " style="position: relative; left: 5rem;">
        <h3>Settings</h3>

        <!-- Admin Name -->
        <div class="mb-4 w-25">
            <label class="form-label">Admin Name</label>
            <input type="text" class="form-control text-center" value="<?= htmlspecialchars($_SESSION['admin_name']) ?>"
                style="outline: none; border: none;" readonly>
        </div>

        <!-- Theme Switcher -->
        <form method="POST" class="mb-4 w-50">
            <label class="form-label">Theme</label>
            <select name="theme" class="form-select w-50 mb-2" style="cursor: none;" onchange="this.form.submit()">
                <option value="system" <?= $theme === 'system' ? 'selected' : '' ?>>System</option>
                <option value="light" <?= $theme === 'light' ? 'selected' : '' ?>>Light</option>
                <option value="dark" <?= $theme === 'dark' ? 'selected' : '' ?>>Dark</option>
                <option value="success" <?= $theme === 'success' ? 'selected' : '' ?>>Green</option>
            </select>
        </form>

        <!-- Password Change -->
        <form method="POST">
            <h5 class="mb-3 w-25">Change Password</h5>
            <?php if ($password_change_success): ?>
                <div class="alert alert-success w-25"><?= $password_change_success ?></div>
            <?php elseif ($password_change_error): ?>
                <div class="alert alert-danger w-25"><?= $password_change_error ?></div>
            <?php endif; ?>
            <div class="mb-3 w-25">
                <label>Current Password</label>
                <input type="password" class="form-control" name="current_password" required>
            </div>
            <div class="mb-3 w-25">
                <label>New Password</label>
                <div class="input-group">
                    <input type="password" class="form-control col-auto" name="new_password" id="newPassword" required>
                    <span class="input-group-text show-password" onclick="togglePassword('newPassword')">👁️</span>
                </div>
            </div>
            <div class="mb-3 w-25">
                <label>Confirm New Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="confirm_password" id="confirmPassword" required>
                    <span class="input-group-text show-password" onclick="togglePassword('confirmPassword')">👁️</span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="cursor: none;">Change Password</button>
        </form>
    </div>
    <?php include "cursor.php" ?>
    <script>
        function togglePassword(id) {
            const field = document.getElementById(id);
            field.type = field.type === 'password' ? 'text' : 'password';
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

        // Initialize when your cursor initializes
        window.onload = function () {
            zoomController.init();
            // Your existing load code...
        };
    </script>
</body>

</html>