<?php
require_once __DIR__ . '/init.php';
$auth = new Auth();
$newsModel = new News();

$newsList = $newsModel->getAll(50);
$pageTitle = 'News';
$heroSubtitle = 'News';
$heroTitle = 'Lajme & Njoftime';
$heroDesc = 'Lexo lajmet e fundit nga AirLugina.';

require __DIR__ . '/includes/header.php';
?>
<div class="content-wrap" style="max-width: 1000px; margin: 2rem auto; padding: 1rem;">
  <h1>Lajme</h1>
  <?php if (empty($newsList)): ?>
    <p>Nuk ka lajme të regjistruara ende. Administratori mund të shtojë lajme nga Dashboard.</p>
  <?php else: ?>
    <ul style="list-style: none; padding: 0;">
      <?php foreach ($newsList as $n): ?>
        <li style="border: 1px solid #eee; margin-bottom: 1rem; padding: 1rem; border-radius: 8px;">
          <?php if (!empty($n['image_path'])): ?>
            <img src="<?= htmlspecialchars($n['image_path']) ?>" alt="" style="max-width: 200px; height: auto; float: left; margin-right: 1rem;">
          <?php endif; ?>
          <h3><a href="news-detail.php?slug=<?= urlencode($n['slug']) ?>"><?= htmlspecialchars($n['title']) ?></a></h3>
          <p><?= nl2br(htmlspecialchars(mb_substr($n['content'], 0, 200))) ?>...</p>
          <p style="font-size: 0.9rem; color: #666;">
            Shtuar nga: <?= htmlspecialchars(trim(($n['created_by_name'] ?? '') . ' ' . ($n['created_by_surname'] ?? ''))) ?: '–' ?>
            | <?= date('d.m.Y', strtotime($n['created_at'])) ?>
          </p>
          <?php if (!empty($n['pdf_path'])): ?>
            <a href="<?= htmlspecialchars($n['pdf_path']) ?>" target="_blank">Shkarko PDF</a>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
