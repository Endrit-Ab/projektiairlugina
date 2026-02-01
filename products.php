<?php
require_once __DIR__ . '/init.php';
$auth = new Auth();
$productModel = new Product();

$products = $productModel->getAll(100);
$pageTitle = 'Products';
$heroSubtitle = 'Products';
$heroTitle = 'Flights & Deals';
$heroDesc = 'Zgjidhni fluturimet dhe ofertat tona.';

require __DIR__ . '/includes/header.php';
?>
<style>
.content-wrap .product-card .card-image {
  width: 100%;
  height: 220px;
  object-fit: cover;
  object-position: center;
  image-rendering: -webkit-optimize-contrast;
  image-rendering: high-quality;
}
</style>
<div class="content-wrap" style="max-width: 1100px; margin: 2rem auto; padding: 1rem;">
  <h1>Produkte / Flights</h1>
  <?php if (empty($products)): ?>
    <p>Nuk ka produkte te regjistruara ende. Administratori mund te shtoje nga Dashboard. <a href="deals.php">Shiko faqen Deals</a>.</p>
  <?php else: ?>
    <div class="cards" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
      <?php foreach ($products as $p): ?>
        <div class="card product-card" style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; cursor: pointer; transition: box-shadow 0.2s;" onclick="window.location='deals.php'">
          <?php if (!empty($p['image_path'])): ?>
            <img src="<?= htmlspecialchars($p['image_path']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" class="card-image">
          <?php else: ?>
            <img src="assets/Images/Frame 197-1.png" alt="" class="card-image">
          <?php endif; ?>
          <div class="card-content" style="padding: 1rem;">
            <h3 class="card-title"><?= htmlspecialchars($p['title']) ?></h3>
            <p class="card-text"><?= htmlspecialchars($p['from_location'] ?? '') ?> → <?= htmlspecialchars($p['to_location'] ?? '') ?></p>
            <?php if (!empty($p['description'])): ?>
              <p><?= nl2br(htmlspecialchars(mb_substr($p['description'], 0, 120))) ?>...</p>
            <?php endif; ?>
            <?php if (isset($p['price']) && $p['price'] !== null): ?>
              <p><strong>€<?= number_format($p['price'], 2) ?></strong></p>
            <?php endif; ?>
            <p style="font-size: 0.85rem; color: #666;">
              Shtuar nga: <?= htmlspecialchars(trim(($p['created_by_name'] ?? '') . ' ' . ($p['created_by_surname'] ?? ''))) ?: '–' ?>
            </p>
            <?php if (!empty($p['pdf_path'])): ?>
              <a href="<?= htmlspecialchars($p['pdf_path']) ?>" target="_blank" onclick="event.stopPropagation()">Shkarko PDF</a>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
