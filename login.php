<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $storedHash = trim(@file_get_contents("password.txt")); // Use @ to suppress errors if file doesn't exist

    if ($username === "admin" && password_verify($password, $storedHash)) {
        $_SESSION["loggedin"] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid login credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-white d-flex justify-content-center align-items-center vh-100">
    <div class="bg-secondary p-4 rounded shadow" style="width: 300px;">
        <h4 class="text-center mb-3">Admin Login</h4>
        <form method="POST" action="">
            <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger mt-3 text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </div>

    <script>
        const zoomController = {
            init() {
                document.addEventListener('keydown', this.handleKeyZoom);
                document.addEventListener('wheel', this.handleWheelZoom, { passive: false });
                document.addEventListener('touchmove', this.handleTouchZoom, { passive: false });
            },
            handleKeyZoom(e) {
                if ((e.ctrlKey || e.metaKey) &&
                    ['+', '-', '0', '=', '_'].includes(e.key)) {
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

        window.onload = () => zoomController.init();
    </script>
</body>

</html>
