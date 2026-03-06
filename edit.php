<?php
require __DIR__ . '/includes/helpers.php';
require __DIR__ . '/includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo 'ID invalide';
    exit;
}

$stmt = $pdo->prepare('SELECT id, title, description FROM items WHERE id = :id');
$stmt->execute([':id' => $id]);
$item = $stmt->fetch();

if (!$item) {
    http_response_code(404);
    echo 'Item introuvable';
    exit;
}

$errors = [];
$title = $item['title'];
$description = $item['description'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        http_response_code(400);
        $errors['csrf'] = "Jeton CSRF invalide. Veuillez réessayer.";
    }

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($title === '') {
        $errors['title'] = 'Le titre est requis.';
    } elseif (mb_strlen($title) > 150) {
        $errors['title'] = 'Le titre ne doit pas dépasser 150 caractères.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('UPDATE items SET title = :title, description = :description WHERE id = :id');
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description !== '' ? $description : null, $description !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        set_flash('success', 'Item mis à jour avec succès.');
        header('Location: index.php', true, 303);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier un item</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
    <div class="container">
      <a class="navbar-brand" href="index.php">CRUD Items</a>
    </div>
  </nav>

  <main class="container" style="max-width: 720px;">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Modifier l'item #<?= (int)$id ?></h5>
        <a class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" href="index.php">
          <i class="bi bi-arrow-left"></i>
          Retour
        </a>
      </div>
      <div class="card-body">
        <?php if (!empty($errors['csrf'])): ?>
          <div class="alert alert-danger"><?= e($errors['csrf']) ?></div>
        <?php endif; ?>
        <form method="POST" novalidate>
          <?= csrf_field() ?>
          <div class="mb-3">
            <label for="title" class="form-label">Titre *</label>
            <input type="text" class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>" id="title" name="title" value="<?= e($title) ?>" maxlength="150" required>
            <?php if (isset($errors['title'])): ?>
              <div class="invalid-feedback"><?= e($errors['title']) ?></div>
            <?php else: ?>
              <div class="form-text">Obligatoire. 150 caractères max.</div>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4"><?= e($description) ?></textarea>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
              <i class="bi bi-check-lg"></i>
              Enregistrer
            </button>
            <a href="index.php" class="btn btn-secondary d-inline-flex align-items-center gap-2">
              <i class="bi bi-x-lg"></i>
              Annuler
            </a>
          </div>
        </form>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
