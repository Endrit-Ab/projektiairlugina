<?php
require_once __DIR__ . '/../init.php';
$auth = new Auth();
$auth->requireAdmin();

$newsModel = new News();
$id = (int)($_GET['id'] ?? 0);
$news = $id ? $newsModel->getById($id) : null;
if (!$news) {
  header('Location: dashboard.php?tab=news');
  exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $content = trim($_POST['content'] ?? '');
  if ($title === '') $errors['title'] = 'Titulli është i detyrueshëm.';
  if ($content === '') $errors['content'] = 'Përmbajtja është e detyrueshme.';
  if (empty($errors)) {
    $newsModel->update($id, [
      'title' => $title,
      'content' => $content,
      'image_path' => trim($_POST['image_path'] ?? '') ?: null,
      'pdf_path' => trim($_POST['pdf_path'] ?? '') ?: null,
    ], $auth->userId());
    header('Location: dashboard.php?tab=news&updated=1');
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ndrysho lajm - Admin</title>
  <style> body { font-family: sans-serif; max-width: 600px; margin: 1rem auto; padding: 1rem; } input, textarea { width: 100%; padding: 0.4rem; margin-bottom: 0.5rem; } label { display: block; margin-top: 0.5rem; } </style>
</head>
<body>
  <h1>Ndrysho lajm</h1>
  <p><a href="dashboard.php?tab=news">← Kthehu te Dashboard</a></p>
  <form method="post">
    <label>Titulli *</label>
    <input type="text" name="title" value="<?= htmlspecialchars($news['title']) ?>" required>
    <label>Përmbajtja *</label>
    <textarea name="content" rows="6" required><?= htmlspecialchars($news['content']) ?></textarea>
    <label>Foto (rrugë/URL)</label>
    <input type="text" name="image_path" value="<?= htmlspecialchars($news['image_path'] ?? '') ?>">
    <label>PDF (rrugë/URL)</label>
    <input type="text" name="pdf_path" value="<?= htmlspecialchars($news['pdf_path'] ?? '') ?>">
    <button type="submit">Ruaj ndryshimet</button>
  </form>
</body>
</html>
