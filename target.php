<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

require 'db.php'; // Ensure this file defines $pdo

$error = ''; // Initialize error variable

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetName = trim($_POST['target_name']);
    $image = $_FILES['profile_image'];

    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true); // Create uploads folder if not exists
    }

    if ($image['error'] === 0) {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $uploadPath = "uploads/$filename";

        if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
            $stmt = $pdo->prepare("INSERT INTO instagram_cards (image_path, target_name) VALUES (?, ?)");
            $stmt->execute([$uploadPath, $targetName]);
        } else {
            $error = "Failed to upload image.";
        }
    } else {
        $error = "Image upload error.";
    }
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("SELECT image_path FROM instagram_cards WHERE id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetchColumn();

    if ($file && file_exists($file)) {
        unlink($file);
    }

    $stmt = $pdo->prepare("DELETE FROM instagram_cards WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: instagram.php");
    exit;
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
    <title>Instagram - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #fff;
            cursor: none;
            overflow-x: hidden;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>

<body class="<?= $themeClass ?>">
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4 pb-5">
        <h2 class="mb-4 text-center">Instagram Profiles</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="text-center mb-4">
            <button class="btn btn-primary " style="cursor: none;"
                onclick="document.getElementById('uploadForm').style.display='block'">
                New Target
            </button>
        </div>
        <div id="uploadForm" class="position-fixed top-50 start-50 translate-middle bg-dark p-4 rounded shadow mb-4"
            style="display: none; max-width: 500px; z-index: 1050;">
            <form id="uploadCardForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Target Name</label>
                    <input type="text" name="target_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Profile Image</label>
                    <input type="file" name="profile_image" class="form-control" accept="image/*" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Add Card</button>
                    <button type="button" class="btn btn-secondary"
                        onclick="document.getElementById('uploadForm').style.display='none'">Cancel</button>
                </div>
            </form>
        </div>


        <div class=" d-flex justify-content-around " id="cardContainer">
            <?php
            $stmt = $pdo->query("SELECT * FROM instagram_cards ORDER BY created_at DESC");
            while ($card = $stmt->fetch()):
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card bg-secondary text-white w-75 mx-auto shadow">
                        <img src="<?= htmlspecialchars($card['image_path']) ?>" class="card-img-top w-auto h-auto"
                            alt="Profile Image">
                        <div class="card-body">
                            <h5 class="card-title text-center" style="margin: auto auto 1rem auto;font-size: 1rem; ">
                                <?= htmlspecialchars($card['target_name']) ?>
                            </h5>
                            <button class="btn btn-danger btn-sm d-flex delete-btn" data-id="<?= $card['id'] ?>"
                                style="margin: auto;">Delete</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include "cursor.php" ?>
    <script>
        document.getElementById("uploadCardForm").addEventListener("submit", function (e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            fetch("upload_card.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Add card to DOM
                        const cardContainer = document.getElementById("cardContainer");
                        const newCard = document.createElement("div");
                        newCard.className = "col-md-4 mb-4";
                        newCard.innerHTML = `
                <div class="card bg-secondary text-white w-75  shadow">
                    <img src="${data.image_path}" class="card-img-top w-auto h-auto" alt="Profile Image">
                    <div class="card-body">
                        <h5 class="card-title text-center"  style="margin: auto auto 1rem auto;font-size: 1rem; ">${data.target_name}</h5>
                        <button class="btn btn-danger btn-sm d-flex delete-btn" data-id="${data.id}"  style="margin: auto;">Delete</button>
                    </div>
                </div>
            `;
                        cardContainer.prepend(newCard);

                        // Re-bind delete functionality to new button
                        newCard.querySelector('.delete-btn').addEventListener('click', handleDelete);

                        // Clear form and hide it
                        form.reset();
                        document.getElementById("uploadForm").style.display = "none";
                    } else {
                        alert(data.error || "Upload failed.");
                    }
                })
                .catch(err => {
                    alert("Error uploading.");
                    console.error(err);
                });
        });

        // Extract delete functionality so it can be reused
        function handleDelete() {
            if (!confirm('Are you sure you want to delete this card?')) return;

            const cardId = this.getAttribute('data-id');
            const cardElement = this.closest('.col-md-4');

            fetch('delete_card.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + encodeURIComponent(cardId)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cardElement.remove();
                    } else {
                        alert('Error deleting card.');
                    }
                })
                .catch(error => {
                    alert('Request failed.');
                    console.error(error);
                });
        }

        // Attach delete listeners on page load
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', handleDelete);
        });


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