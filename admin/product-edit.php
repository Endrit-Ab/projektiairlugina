<?php
require_once __DIR__ . '/../init.php';
$auth = new Auth();
$auth->requireAdmin();

$productModel = new Product();
$id = (int)($_GET['id'] ?? 0);
$product = $id ? $productModel->getById($id) : null;
if (!$product) {
  header('Location: dashboard.php?tab=products');
  exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  if ($title === '') $errors['title'] = 'Titulli është i detyrueshëm.';
  if (empty($errors)) {
    $productModel->update($id, [
      'title' => $title,
      'description' => trim($_POST['description'] ?? '') ?: null,
      'from_location' => trim($_POST['from_location'] ?? '') ?: null,
      'to_location' => trim($_POST['to_location'] ?? '') ?: null,
      'price' => $_POST['price'] !== '' ? (float)$_POST['price'] : null,
      'image_path' => trim($_POST['image_path'] ?? '') ?: null,
      'pdf_path' => trim($_POST['pdf_path'] ?? '') ?: null,
    ], $auth->userId());
    header('Location: dashboard.php?tab=products&updated=1');
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ndrysho produkt - Admin</title>
  <style> body { font-family: sans-serif; max-width: 600px; margin: 1rem auto; padding: 1rem; } input, textarea { width: 100%; padding: 0.4rem; margin-bottom: 0.5rem; } label { display: block; margin-top: 0.5rem; } </style>
</head>
<body>
  <h1>Ndrysho produkt</h1>
  <p><a href="dashboard.php?tab=products">← Kthehu te Dashboard</a></p>
  <form method="post">
    <label>Titulli *</label>
    <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>" required>
    <label>Përshkrimi</label>
    <textarea name="description" rows="3"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
    <label>Nga</label>
    <input type="text" name="from_location" value="<?= htmlspecialchars($product['from_location'] ?? '') ?>">
    <label>Deri</label>
    <input type="text" name="to_location" value="<?= htmlspecialchars($product['to_location'] ?? '') ?>">
    <label>Çmimi</label>
    <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($product['price'] ?? '') ?>">
    <label>Foto (rrugë/URL)</label>
    <input type="text" name="image_path" value="<?= htmlspecialchars($product['image_path'] ?? '') ?>">
    <label>PDF (rrugë/URL)</label>
    <input type="text" name="pdf_path" value="<?= htmlspecialchars($product['pdf_path'] ?? '') ?>">
    <button type="submit">Ruaj ndryshimet</button>
  </form>
</body>
</html>
