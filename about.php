<?php
session_start();

include "cursor.php";

// Redirect if not logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
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
    <meta charset="UTF-8" />
    <title>About - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            overflow-x: hidden;
        }

        .sidebar {
            min-height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #212529;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 1000;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
    </style>
</head>

<body class="<?= $themeClass ?>" oncontextmenu="return false">
    <?php include 'includes/header.php'; ?>

    <!-- Main Content -->
    <div class="main-content mx-auto">
        <div class="card col-8 text-center mx-auto shadow pb-4">
            <img src="sanjeet.jpg" alt="Admin Profile" class="profile-img w-25 mx-auto">
            <div class="admin-name mt-2 mb-2 fs-3">Sanjeet Kalyan</div>
            <div class="admin-bio col-8 mb-2 text-center mx-auto">
                Sanjeet Kalyan is a passionate Ethical Hacker and Cybersecurity Specialist, dedicated to securing
                digital infrastructures and uncovering vulnerabilities before attackers can exploit them. With a sharp
                eye for system weaknesses and a commitment to digital safety, he ensures robust protection across
                networks and applications.
            </div>

            <div class="mt-3 w-25 mx-auto mb-2">
                <a href="https://github.com/sanjeetkalyan" target="_blank" class="text-black me-3 fs-4">
                    <i class="fab fa-github"></i>
                </a>
                <a href="https://linkedin.com/in/sanjeetkalyan" target="_blank" class="text-black me-3 fs-4">
                    <i class="fab fa-linkedin"></i>
                </a>
                <a href="https://instagram.com/analystsanjeet" target="_blank" class="text-black fs-4">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>

            <a href="index.php" class="back-link">← Back to Dashboard</a>
        </div>
    </div>
<script>
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