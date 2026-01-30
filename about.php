<?php
require_once __DIR__ . '/init.php';
$auth = new Auth();
$contactModel = new Contact();

$pageContent = $contactModel->getPageBySlug('about');
$sliderItems = $contactModel->getSliderItems();

$pageTitle = 'About Us';
$heroSubtitle = 'About Us';
$heroTitle = $pageContent['title'] ?? 'About AirLugina';
$heroDesc = strip_tags(mb_substr($pageContent['content'] ?? '', 0, 120)) . '...';

require __DIR__ . '/includes/header.php';
?>
<div class="content-wrap" style="max-width: 900px; margin: 2rem auto; padding: 1rem;">
  <h1><?= htmlspecialchars($pageContent['title'] ?? 'About Us') ?></h1>
  <div class="page-content" style="line-height: 1.7;">
    <?= nl2br(htmlspecialchars($pageContent['content'] ?? 'AirLugina helps you live and travel. We offer special deals to suit your plan.')) ?>
  </div>
  <?php if (count($sliderItems) > 0): ?>
  <div class="about-slider" style="margin-top: 2rem;">
    <div class="slide" style="text-align: center;">
      <img src="<?= htmlspecialchars($sliderItems[0]['image_path']) ?>" alt="<?= htmlspecialchars($sliderItems[0]['title'] ?? '') ?>" style="max-width: 100%; border-radius: 8px;">
      <p><strong><?= htmlspecialchars($sliderItems[0]['title'] ?? '') ?></strong> – <?= htmlspecialchars($sliderItems[0]['subtitle'] ?? '') ?></p>
    </div>
  </div>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
