<?php
require __DIR__ . '/includes/helpers.php';
require __DIR__ . '/includes/db.php';

// Récupération des items
$stmt = $pdo->query('SELECT id, title, description, created_at, updated_at FROM items ORDER BY created_at DESC');
$items = $stmt->fetchAll();
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CRUD - Items</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
    <div class="container">
      <a class="navbar-brand" href="index.php">CRUD Items</a>
      <div>
        <a class="btn btn-success d-inline-flex align-items-center gap-2" href="create.php">
          <i class="bi bi-plus-lg"></i>
          Ajouter
        </a>
      </div>
    </div>
  </nav>

  <main class="container">
    <?php if ($flash): ?>
      <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
        <?= e($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Liste des items</h5>
        <a class="btn btn-primary d-inline-flex align-items-center gap-2" href="create.php">
          <i class="bi bi-plus-lg"></i>
          Créer un item
        </a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Créé le</th>
                <th>Mis à jour le</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($items)): ?>
                <tr><td colspan="6" class="text-center p-4">Aucun enregistrement</td></tr>
              <?php else: ?>
                <?php foreach ($items as $it): ?>
                  <tr>
                    <td><?= (int)$it['id'] ?></td>
                    <td><?= e($it['title']) ?></td>
                    <td><?= e($it['description'] ?? '') ?></td>
                    <td><?= e($it['created_at']) ?></td>
                    <td><?= e($it['updated_at'] ?? '') ?></td>
                    <td class="text-end">
                      <a class="btn btn-sm btn-primary d-inline-flex align-items-center gap-2" href="edit.php?id=<?= (int)$it['id'] ?>">
                        <i class="bi bi-pencil"></i>
                        Modifier
                      </a>
                      <form action="delete.php" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet élément ?');">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-2">
                          <i class="bi bi-trash"></i>
                          Supprimer
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
