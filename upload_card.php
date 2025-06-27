<?php
require 'db.php';

header('Content-Type: application/json');
include 'cursor.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetName = trim($_POST['target_name'] ?? '');
    $image = $_FILES['profile_image'] ?? null;

    if (!$targetName || !$image || $image['error'] !== 0) {
        echo json_encode(['success' => false, 'error' => 'Missing fields or file error']);
        exit;
    }

    if (!is_dir('uploads'))
        mkdir('uploads', 0777, true);

    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . uniqid() . '.' . $ext;
    $uploadPath = 'uploads/' . $filename;

    if (!move_uploaded_file($image['tmp_name'], $uploadPath)) {
        echo json_encode(['success' => false, 'error' => 'Failed to move file']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO instagram_cards (image_path, target_name) VALUES (?, ?)");
    $stmt->execute([$uploadPath, $targetName]);

    echo json_encode([
        'success' => true,
        'image_path' => $uploadPath,
        'target_name' => $targetName,
        'id' => $pdo->lastInsertId()
    ]);
    exit;
}
