<?php
require 'db.php';
header('Content-Type: application/json');

$id = intval($_POST['id'] ?? 0);
if (!$id) {
    echo json_encode(['success' => false, 'error' => 'Missing ID']);
    exit;
}

$stmt = $pdo->prepare("SELECT image_path FROM instagram_cards WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetchColumn();

if ($file && file_exists($file)) {
    unlink($file);
}

$stmt = $pdo->prepare("DELETE FROM instagram_cards WHERE id = ?");
$stmt->execute([$id]);

echo json_encode(['success' => true]);
