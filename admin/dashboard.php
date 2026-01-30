<?php
require_once __DIR__ . '/../init.php';
$auth = new Auth();
$auth->requireAdmin();

$contactModel = new Contact();
$newsModel = new News();
$productModel = new Product();

$messages = $contactModel->getAllMessages();
$unreadCount = count(array_filter($messages, function ($m) { return empty($m['read_at']); }));
$pageTitle = 'Admin Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> - AirLugina</title>
  <style>
    body { font-family: sans-serif; max-width: 1200px; margin: 0 auto; padding: 1rem; }
    nav { margin-bottom: 2rem; }
    nav a { margin-right: 1rem; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 0.5rem; text-align: left; }
    th { background: #f5f5f5; }
    .tabs { display: flex; gap: 0.5rem; margin-bottom: 1rem; }
    .tabs a { padding: 0.5rem 1rem; background: #eee; text-decoration: none; border-radius: 4px; }
    .tabs a.active { background: #333; color: #fff; }
    .unread { background: #fffde7; }
    .success { color: green; }
    .error { color: red; }
    input, textarea { width: 100%; padding: 0.4rem; margin-bottom: 0.5rem; }
    label { display: block; margin-top: 0.5rem; }
  </style>
</head>
<body>
  <nav>
    <a href="../index.php">Faqja kryesore</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="../logout.php">Dil</a>
  </nav>
  <h1>Dashboard – Administrator</h1>
  <?php if (!empty($_GET['added'])): ?><p class="success">U shtua me sukses.</p><?php endif; ?>
  <?php if (!empty($_GET['updated'])): ?><p class="success">U përditësua me sukses.</p><?php endif; ?>
  <p>Përdorues: <?= htmlspecialchars($auth->userName()) ?> (<?= $auth->userRole() ?>)</p>

  <div class="tabs">
    <a href="?tab=messages" class="<?= ($_GET['tab'] ?? 'messages') === 'messages' ? 'active' : '' ?>">Mesazhet e kontaktit (<?= count($messages) ?>, të palexuara: <?= $unreadCount ?>)</a>
    <a href="?tab=news" class="<?= ($_GET['tab'] ?? '') === 'news' ? 'active' : '' ?>">Lajme</a>
    <a href="?tab=products" class="<?= ($_GET['tab'] ?? '') === 'products' ? 'active' : '' ?>">Produkte</a>
  </div>

  <?php if (($_GET['tab'] ?? 'messages') === 'messages'): ?>
    <h2>Mesazhet e kontaktit</h2>
    <p>Të dhënat nga faqja Contact us ruhen këtu. Lexo nga administratori.</p>
    <?php if (empty($messages)): ?>
      <p>Nuk ka mesazhe.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Data</th>
            <th>Emri</th>
            <th>Email</th>
            <th>Subjekti</th>
            <th>Lexuar</th>
            <th>Veprime</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($messages as $m): ?>
            <tr class="<?= empty($m['read_at']) ? 'unread' : '' ?>">
              <td><?= date('d.m.Y H:i', strtotime($m['created_at'])) ?></td>
              <td><?= htmlspecialchars($m['name']) ?></td>
              <td><?= htmlspecialchars($m['email']) ?></td>
              <td><?= htmlspecialchars($m['subject']) ?></td>
              <td><?= $m['read_at'] ? 'Po' : 'Jo' ?></td>
              <td>
                <a href="?tab=messages&view=<?= (int)$m['id'] ?>">Shiko</a>
                <?php if (empty($m['read_at'])): ?>
                  | <a href="?tab=messages&mark=<?= (int)$m['id'] ?>">Shëno si të lexuar</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <?php if (!empty($_GET['view'])): ?>
      <?php
      $msg = $contactModel->getMessageById((int)$_GET['view']);
      if ($msg):
        $contactModel->markAsRead($msg['id']);
      ?>
        <div style="margin-top: 2rem; padding: 1rem; border: 1px solid #ddd;">
          <h3>Mesazhi #<?= $msg['id'] ?></h3>
          <p><strong>Emri:</strong> <?= htmlspecialchars($msg['name']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($msg['email']) ?></p>
          <p><strong>Subjekti:</strong> <?= htmlspecialchars($msg['subject']) ?></p>
          <p><strong>Mesazhi:</strong></p>
          <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
          <p><small><?= $msg['created_at'] ?></small></p>
        </div>
      <?php endif; ?>
    <?php endif; ?>
    <?php if (!empty($_GET['mark'])): ?>
      <?php $contactModel->markAsRead((int)$_GET['mark']); header('Location: dashboard.php?tab=messages'); exit; ?>
    <?php endif; ?>
  <?php endif; ?>

  <?php if (($_GET['tab'] ?? '') === 'news'): ?>
    <h2>Menaxhimi i lajmeve</h2>
    <p>Lajmet shfaqen në faqen News. Cili përdorues ka shtuar/modifikuar shihet në faqe.</p>
    <?php
    $newsList = $newsModel->getAll(100);
    if (!empty($_POST['add_news'])):
      $newsModel->create([
        'title' => $_POST['title'] ?? '',
        'content' => $_POST['content'] ?? '',
        'image_path' => trim($_POST['image_path'] ?? '') ?: null,
        'pdf_path' => trim($_POST['pdf_path'] ?? '') ?: null,
      ], $auth->userId());
      header('Location: dashboard.php?tab=news&added=1');
      exit;
    endif;
    ?>
    <form method="post" style="max-width: 600px; margin-bottom: 2rem;">
      <h3>Shto lajm</h3>
      <label>Titulli *</label>
      <input type="text" name="title" required>
      <label>Përmbajtja *</label>
      <textarea name="content" rows="5" required></textarea>
      <label>Foto (rrugë ose URL)</label>
      <input type="text" name="image_path" placeholder="Assets/Images/... ose URL">
      <label>PDF (rrugë ose URL)</label>
      <input type="text" name="pdf_path" placeholder="uploads/... ose URL">
      <button type="submit" name="add_news" value="1">Shto lajm</button>
    </form>
    <table>
      <thead><tr><th>ID</th><th>Titulli</th><th>Shtuar nga</th><th>Data</th><th>Veprime</th></tr></thead>
      <tbody>
        <?php foreach ($newsList as $n): ?>
          <tr>
            <td><?= $n['id'] ?></td>
            <td><?= htmlspecialchars($n['title']) ?></td>
            <td><?= htmlspecialchars(trim(($n['created_by_name'] ?? '') . ' ' . ($n['created_by_surname'] ?? ''))) ?: '–' ?></td>
            <td><?= $n['created_at'] ?></td>
            <td><a href="news-edit.php?id=<?= $n['id'] ?>">Ndrysho</a> | <a href="news-delete.php?id=<?= $n['id'] ?>" onclick="return confirm('Fshi këtë lajm?');">Fshi</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <?php if (($_GET['tab'] ?? '') === 'products'): ?>
    <h2>Menaxhimi i produkteve (Flights/Deals)</h2>
    <p>Produktet shfaqen në faqen Products. Cili përdorues ka shtuar/modifikuar shihet në faqe.</p>
    <?php
    $productsList = $productModel->getAll(100);
    if (!empty($_POST['add_product'])):
      $productModel->create([
        'title' => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'from_location' => $_POST['from_location'] ?? '',
        'to_location' => $_POST['to_location'] ?? '',
        'price' => !empty($_POST['price']) ? (float)$_POST['price'] : null,
        'image_path' => trim($_POST['image_path'] ?? '') ?: null,
        'pdf_path' => trim($_POST['pdf_path'] ?? '') ?: null,
      ], $auth->userId());
      header('Location: dashboard.php?tab=products&added=1');
      exit;
    endif;
    ?>
    <form method="post" style="max-width: 600px; margin-bottom: 2rem;">
      <h3>Shto produkt / flight</h3>
      <label>Titulli *</label>
      <input type="text" name="title" required>
      <label>Përshkrimi</label>
      <textarea name="description" rows="3"></textarea>
      <label>Nga (lokacioni)</label>
      <input type="text" name="from_location">
      <label>Deri (lokacioni)</label>
      <input type="text" name="to_location">
      <label>Çmimi</label>
      <input type="number" name="price" step="0.01" placeholder="0.00">
      <label>Foto (rrugë ose URL)</label>
      <input type="text" name="image_path">
      <label>PDF (rrugë ose URL)</label>
      <input type="text" name="pdf_path">
      <button type="submit" name="add_product" value="1">Shto produkt</button>
    </form>
    <table>
      <thead><tr><th>ID</th><th>Titulli</th><th>Nga → Deri</th><th>Çmimi</th><th>Shtuar nga</th><th>Veprime</th></tr></thead>
      <tbody>
        <?php foreach ($productsList as $p): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['title']) ?></td>
            <td><?= htmlspecialchars($p['from_location'] ?? '') ?> → <?= htmlspecialchars($p['to_location'] ?? '') ?></td>
            <td><?= $p['price'] !== null ? '€' . number_format($p['price'], 2) : '–' ?></td>
            <td><?= htmlspecialchars(trim(($p['created_by_name'] ?? '') . ' ' . ($p['created_by_surname'] ?? ''))) ?: '–' ?></td>
            <td><a href="product-edit.php?id=<?= $p['id'] ?>">Ndrysho</a> | <a href="product-delete.php?id=<?= $p['id'] ?>" onclick="return confirm('Fshi këtë produkt?');">Fshi</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</body>
</html>
