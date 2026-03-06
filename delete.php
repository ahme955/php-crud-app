<?php
require __DIR__ . '/includes/helpers.php';
require __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Méthode non autorisée';
    exit;
}

if (!verify_csrf()) {
    http_response_code(400);
    set_flash('danger', 'Jeton CSRF invalide. Veuillez réessayer.');
    header('Location: index.php', true, 303);
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    set_flash('danger', 'ID invalide.');
    header('Location: index.php', true, 303);
    exit;
}

$stmt = $pdo->prepare('DELETE FROM items WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

set_flash('success', 'Item supprimé avec succès.');
header('Location: index.php', true, 303);
exit;
