<?php
require_once __DIR__ . '/init.php';
$auth = new Auth();
$newsModel = new News();

$slug = $_GET['slug'] ?? '';
$news = $slug ? $newsModel->getBySlug($slug) : null;
if (!$news) {
  header('Location: news.php');
  exit;
}

$pageTitle = $news['title'];
$heroSubtitle = 'News';
$heroTitle = $news['title'];
$heroDesc = strip_tags(mb_substr($news['content'], 0, 100)) . '...';

require __DIR__ . '/includes/header.php';
?>
<div class="content-wrap" style="max-width: 800px; margin: 2rem auto; padding: 1rem;">
  <h1><?= htmlspecialchars($news['title']) ?></h1>
  <?php if (!empty($news['image_path'])): ?>
    <img src="<?= htmlspecialchars($news['image_path']) ?>" alt="" style="max-width: 100%; margin-bottom: 1rem;">
  <?php endif; ?>
  <div class="news-content"><?= nl2br(htmlspecialchars($news['content'])) ?></div>
  <?php if (!empty($news['pdf_path'])): ?>
    <p><a href="<?= htmlspecialchars($news['pdf_path']) ?>" target="_blank">Shkarko PDF</a></p>
  <?php endif; ?>
  <p style="color: #666; font-size: 0.9rem;">
    Shtuar nga: <?= htmlspecialchars(trim(($news['created_by_name'] ?? '') . ' ' . ($news['created_by_surname'] ?? ''))) ?: '–' ?>
    | Përditësuar nga: <?= htmlspecialchars(trim(($news['updated_by_name'] ?? '') . ' ' . ($news['updated_by_surname'] ?? ''))) ?: '–' ?>
    | <?= date('d.m.Y H:i', strtotime($news['updated_at'])) ?>
  </p>
  <a href="news.php">← Kthehu te Lajmet</a>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
