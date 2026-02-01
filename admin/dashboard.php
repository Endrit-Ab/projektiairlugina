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
  <!-- Main site styles -->
  <link rel="stylesheet" href="../assets/landingpage.css">
  <style>
    /* Dashboard Specific Overrides and Layout */
    body {
      background-color: #FAFBFC;
      display: block; /* Override flex from landingpage.css if needed */
      min-height: 100vh;
      color: #333;
      margin: 0;
      padding: 0;
      font-family: 'ClashDisplay-Regular', sans-serif;
    }

    /* Navbar Styling */
    .admin-navbar {
      background-color: #fff;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      margin-bottom: 2rem;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .admin-logo {
      font-family: 'ClashDisplay-Bold', sans-serif;
      font-size: 1.5rem;
      color: #000;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .admin-logo img {
      height: 35px;
    }
    
    .admin-nav-links {
        display: flex;
        gap: 20px;
    }

    .admin-nav-links a {
      text-decoration: none;
      color: #555;
      font-family: 'ClashDisplay-Medium', sans-serif;
      transition: color 0.3s;
      font-size: 1rem;
    }
    
    .admin-nav-links a:hover,
    .admin-nav-links a.active {
      color: #ff4500;
    }

    /* Layout Container */
    .admin-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem 4rem;
    }

    .dashboard-header {
      margin-bottom: 2rem;
    }

    .dashboard-title {
      font-family: 'ClashDisplay-Bold', sans-serif;
      font-size: 2.5rem;
      color: #111;
      margin-bottom: 0.5rem;
    }

    .user-info {
      color: #666;
      font-size: 1rem;
    }

    /* Tabs Component */
    .dashboard-tabs {
      display: flex;
      gap: 1rem;
      margin-bottom: 2rem;
      border-bottom: 1px solid #eee;
      padding-bottom: 1rem;
      overflow-x: auto;
    }

    .tab-link {
      padding: 0.75rem 1.5rem;
      border-radius: 50px; /* Pill shape */
      text-decoration: none;
      color: #555;
      background: #fff;
      border: 1px solid #eee;
      font-family: 'ClashDisplay-Medium', sans-serif;
      transition: all 0.3s ease;
      white-space: nowrap;
    }

    .tab-link:hover {
      border-color: #ff4500;
      color: #ff4500;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(255, 69, 0, 0.1);
    }

    .tab-link.active {
      background-color: #ff4500;
      color: white;
      border-color: #ff4500;
      box-shadow: 0 4px 12px rgba(255, 69, 0, 0.3);
    }

    /* Card Component */
    .content-card {
      background: white;
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
      border: 1px solid #f0f0f0;
    }

    /* Forms */
    .admin-form {
      max-width: 100%;
      background: #f9f9f9;
      padding: 1.5rem;
      border-radius: 12px;
      margin-bottom: 2rem;
    }

    .admin-form h3 {
        margin-top: 0;
        margin-bottom: 1.5rem;
    }

    .admin-form label {
      display: block;
      margin-bottom: 0.5rem;
      font-family: 'ClashDisplay-Medium', sans-serif;
      color: #333;
      font-size: 0.9rem;
    }

    .admin-form input,
    .admin-form textarea, 
    .admin-form select {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      margin-bottom: 1.2rem;
      font-family: 'ClashDisplay-Regular', sans-serif;
      background: #fff;
      font-size: 0.95rem;
      transition: border 0.3s;
    }

    .admin-form input:focus,
    .admin-form textarea:focus,
    .admin-form select:focus {
      border-color: #ff4500;
      outline: none;
      box-shadow: 0 0 0 3px rgba(255, 69, 0, 0.1);
    }

    .admin-btn {
      background-color: #ff4500;
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      cursor: pointer;
      font-family: 'ClashDisplay-Medium', sans-serif;
      font-size: 1rem;
      transition: transform 0.2s, background 0.3s;
      width: auto;
      display: inline-block;
    }

    .admin-btn:hover {
      background-color: #e63e00;
      transform: translateY(-1px);
    }
    
    .admin-btn-secondary {
        background-color: #fff;
        color: #555;
        border: 1px solid #ddd;
    }
    .admin-btn-secondary:hover {
        background-color: #f5f5f5;
        color: #333;
    }

    /* Tables */
    .table-responsive {
        overflow-x: auto;
    }
    
    .admin-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      margin-top: 1rem;
    }

    .admin-table th {
      text-align: left;
      padding: 1.2rem 1rem;
      background-color: #f8f9fa;
      font-family: 'ClashDisplay-SemiBold', sans-serif;
      color: #444;
      border-bottom: 2px solid #eee;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .admin-table td {
      padding: 1.2rem 1rem;
      border-bottom: 1px solid #eee;
      vertical-align: middle;
      color: #555;
    }

    .admin-table tr:last-child td {
      border-bottom: none;
    }

    .admin-table tr:hover td {
      background-color: #fafbfc;
    }

    .status-unread td {
      background-color: #fffaf5; /* Very light orange */
      font-family: 'ClashDisplay-Medium', sans-serif;
      color: #333;
    }

    /* Alerts */
    .alert {
      padding: 1rem 1.5rem;
      border-radius: 8px;
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .alert-success {
      background-color: #ecfdf5;
      color: #065f46;
      border: 1px solid #d1fae5;
    }

    /* Utilities */
    .action-links a {
      margin-right: 12px;
      text-decoration: none;
      font-weight: 500;
      transition: opacity 0.2s;
    }
    .action-links a:hover {
        opacity: 0.8;
    }
    .text-primary { color: #ff4500; }
    .text-danger { color: #dc2626; }
    
    .badge {
      background: #ff4500;
      color: white;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.75rem;
      margin-left: 8px;
      vertical-align: middle;
    }

    /* Grid layout for News/Products tabs */
    .two-col-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 2rem;
    }

    @media (max-width: 900px) {
        .two-col-grid {
            grid-template-columns: 1fr;
        }
        .admin-form {
            max-width: 100%;
        }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="admin-navbar">
    <a href="dashboard.php" class="admin-logo">
      <img src="../assets/Images/logo.png" alt="AirLugina">
      <span>AirLugina Admin</span>
    </a>
    <div class="admin-nav-links">
      <a href="../index.php">Faqja Kryesore</a>
      <a href="dashboard.php" class="active">Dashboard</a>
      <a href="../logout.php">Dil</a>
    </div>
  </nav>

  <div class="admin-container">
    
    <div class="dashboard-header">
      <h1 class="dashboard-title">Dashboard</h1>
      <p class="user-info">Mirë se vini, <strong><?= htmlspecialchars($auth->userName()) ?></strong> (<?= $auth->userRole() ?>)</p>
    </div>

    <!-- Notification Alerts -->
    <?php if (!empty($_GET['added'])): ?>
      <div class="alert alert-success">✔ U shtua me sukses.</div>
    <?php endif; ?>
    <?php if (!empty($_GET['updated'])): ?>
      <div class="alert alert-success">✔ U përditësua me sukses.</div>
    <?php endif; ?>

    <!-- Navigation Tabs -->
    <div class="dashboard-tabs">
      <a href="?tab=messages" class="tab-link <?= ($_GET['tab'] ?? 'messages') === 'messages' ? 'active' : '' ?>">
        Mesazhet 
        <?php if($unreadCount > 0): ?><span class="badge"><?= $unreadCount ?></span><?php endif; ?>
      </a>
      <a href="?tab=news" class="tab-link <?= ($_GET['tab'] ?? '') === 'news' ? 'active' : '' ?>">Lajme</a>
      <a href="?tab=products" class="tab-link <?= ($_GET['tab'] ?? '') === 'products' ? 'active' : '' ?>">Produkte (Flights)</a>
    </div>

    <div class="content-card">
      
      <!-- MESSAGES TAB -->
      <?php if (($_GET['tab'] ?? 'messages') === 'messages'): ?>
        <div style="margin-bottom: 2rem;">
            <h2>Mesazhet e Kontaktit</h2>
            <p style="color:#666;">Menaxhoni mesazhet e dëguara nga forma e kontaktit.</p>
        </div>
        
        <?php if (empty($messages)): ?>
          <div style="text-align:center; padding: 2rem; color: #888;">
              <p>Nuk ka mesazhe.</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
              <table class="admin-table">
                <thead>
                  <tr>
                    <th>Data</th>
                    <th>Emri</th>
                    <th>Email</th>
                    <th>Subjekti</th>
                    <th>Statusi</th>
                    <th>Veprime</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($messages as $m): ?>
                    <tr class="<?= empty($m['read_at']) ? 'status-unread' : '' ?>">
                      <td><?= date('d.m.Y H:i', strtotime($m['created_at'])) ?></td>
                      <td><?= htmlspecialchars($m['name']) ?></td>
                      <td><?= htmlspecialchars($m['email']) ?></td>
                      <td><?= htmlspecialchars($m['subject']) ?></td>
                      <td>
                        <?php if($m['read_at']): ?>
                          <span style="color:green; font-size: 0.9em;">● Lexuar</span>
                        <?php else: ?>
                          <span style="color:#ff4500; font-weight:bold; font-size: 0.9em;">● I Palexuar</span>
                        <?php endif; ?>
                      </td>
                      <td class="action-links">
                        <a href="?tab=messages&view=<?= (int)$m['id'] ?>" class="text-primary">Shiko</a>
                        <?php if (empty($m['read_at'])): ?>
                           <a href="?tab=messages&mark=<?= (int)$m['id'] ?>" style="color:#666; font-size:0.9em;">Shëno lexuar</a>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
          </div>
        <?php endif; ?>

        <!-- Message View Section -->
        <?php if (!empty($_GET['view'])): ?>
          <?php
          $msg = $contactModel->getMessageById((int)$_GET['view']);
          if ($msg):
            $contactModel->markAsRead($msg['id']);
          ?>
            <div id="message-view" style="margin-top: 3rem; background: #fafbfc; padding: 2rem; border-radius: 12px; border: 1px solid #eee; scroll-margin-top: 100px;">
              <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 1.5rem;">
                   <h3 style="margin:0;">Mesazhi #<?= $msg['id'] ?></h3>
                   <a href="?tab=messages" class="admin-btn admin-btn-secondary" style="padding: 8px 16px; font-size:0.9rem;">Mbyll</a>
              </div>
              
              <div style="display:grid; grid-template-columns: 100px 1fr; gap: 15px; margin-bottom: 1.5rem;">
                <strong style="color:#555;">Dërguesi:</strong> 
                <span><?= htmlspecialchars($msg['name']) ?> &lt;<?= htmlspecialchars($msg['email']) ?>&gt;</span>
                
                <strong style="color:#555;">Subjekti:</strong> 
                <span style="font-weight:bold;"><?= htmlspecialchars($msg['subject']) ?></span>
                
                <strong style="color:#555;">Data:</strong> 
                <span><?= $msg['created_at'] ?></span>
              </div>
              
              <div>
                <strong style="color:#555; display:block; margin-bottom:0.5rem;">Përmbajtja:</strong>
                <div style="background: white; padding: 1.5rem; border-radius: 8px; border: 1px solid #eee; line-height: 1.6; white-space: pre-wrap;"><?= htmlspecialchars($msg['message']) ?></div>
              </div>
            </div>
            <script>document.getElementById('message-view').scrollIntoView({behavior: 'smooth'});</script>
          <?php endif; ?>
        <?php endif; ?>

        <?php if (!empty($_GET['mark'])): ?>
          <?php $contactModel->markAsRead((int)$_GET['mark']); header('Location: dashboard.php?tab=messages'); exit; ?>
        <?php endif; ?>
      <?php endif; ?>


      <!-- NEWS TAB -->
      <?php if (($_GET['tab'] ?? '') === 'news'): ?>
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

        <div class="two-col-grid">
            <!-- Form Section -->
            <div>
                <div class="admin-form">
                    <h3>Shto Lajm të Ri</h3>
                    <form method="post">
                      <label>Titulli <span class="text-danger">*</span></label>
                      <input type="text" name="title" required placeholder="Titulli i lajmit">
                      
                      <label>Përmbajtja <span class="text-danger">*</span></label>
                      <textarea name="content" rows="6" required placeholder="Shkruaj përmbajtjen e lajmit..."></textarea>
                      
                      <label>Foto (Asset Path ose URL)</label>
                      <input type="text" name="image_path" placeholder="ex: assets/Images/news1.jpg">
                      
                      <label>PDF (Path ose URL)</label>
                      <input type="text" name="pdf_path" placeholder="ex: uploads/doc.pdf">
                      
                      <button type="submit" name="add_news" value="1" class="admin-btn" style="width:100%">Publiko Lajmin</button>
                    </form>
                </div>
            </div>

            <!-- List Section -->
            <div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                    <h3>Lajmet e Fundit</h3>
                    <span style="color:#666; font-size:0.9rem;">Total: <?= count($newsList) ?></span>
                </div>
                
                <div class="table-responsive">
                    <table class="admin-table">
                      <thead><tr><th>Titulli</th><th>Autor</th><th>Veprime</th></tr></thead>
                      <tbody>
                        <?php foreach ($newsList as $n): ?>
                          <tr>
                            <td>
                                <div style="font-weight:bold;"><?= htmlspecialchars($n['title']) ?></div>
                                <small style="color:#888;">ID: <?= $n['id'] ?> • <?= date('d M Y', strtotime($n['created_at'])) ?></small>
                            </td>
                            <td style="font-size:0.9em;"><?= htmlspecialchars(trim(($n['created_by_name'] ?? '') . ' ' . ($n['created_by_surname'] ?? ''))) ?: '–' ?></td>
                            <td class="action-links">
                                <a href="news-edit.php?id=<?= $n['id'] ?>" class="text-primary">Ndrysho</a>
                                <a href="news-delete.php?id=<?= $n['id'] ?>" onclick="return confirm('Fshi këtë lajm?');" class="text-danger">Fshi</a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
      <?php endif; ?>


      <!-- PRODUCTS TAB -->
      <?php if (($_GET['tab'] ?? '') === 'products'): ?>
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

        <div class="two-col-grid">
             <!-- Form Section -->
             <div>
                <div class="admin-form">
                    <h3>Shto Produkt / Fluturim</h3>
                    <form method="post">
                      <label>Titulli <span class="text-danger">*</span></label>
                      <input type="text" name="title" required placeholder="psh. Fluturim Prishtinë - Zurich">
                      
                      <label>Përshkrimi</label>
                      <textarea name="description" rows="3" placeholder="Detaje shtesë..."></textarea>
                      
                      <div style="display:flex; gap:10px;">
                          <div style="flex:1;">
                              <label>Nga (Lokacioni)</label>
                              <input type="text" name="from_location" placeholder="PRN">
                          </div>
                          <div style="flex:1;">
                              <label>Deri (Lokacioni)</label>
                              <input type="text" name="to_location" placeholder="ZRH">
                          </div>
                      </div>

                      <label>Çmimi (€)</label>
                      <input type="number" name="price" step="0.01" placeholder="0.00">
                      
                      <label>Foto (Path ose URL)</label>
                      <input type="text" name="image_path" placeholder="ex: assets/Images/flight.jpg">
                      
                      <label>PDF (Path ose URL)</label>
                      <input type="text" name="pdf_path">
                      
                      <button type="submit" name="add_product" value="1" class="admin-btn" style="width:100%">Shto Produkt</button>
                    </form>
                </div>
             </div>

             <!-- List Section -->
             <div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                    <h3>Lista e Produkteve</h3>
                    <span style="color:#666; font-size:0.9rem;">Total: <?= count($productsList) ?></span>
                </div>
                
                <div class="table-responsive">
                    <table class="admin-table">
                      <thead><tr><th>Produkt</th><th>Itinerari</th><th>Çmimi</th><th>Veprime</th></tr></thead>
                      <tbody>
                        <?php foreach ($productsList as $p): ?>
                          <tr>
                            <td>
                                <div style="font-weight:bold;"><?= htmlspecialchars($p['title']) ?></div>
                                <small style="color:#888;">ID: <?= $p['id'] ?></small>
                            </td>
                            <td>
                                <?php if(!empty($p['from_location']) || !empty($p['to_location'])): ?>
                                    <div style="display:inline-block; background:#f0f0f0; padding:2px 8px; border-radius:4px; font-size:0.9rem;">
                                        <?= htmlspecialchars($p['from_location'] ?? '') ?> 
                                        <span style="color:#ff4500;">&rarr;</span> 
                                        <?= htmlspecialchars($p['to_location'] ?? '') ?>
                                    </div>
                                <?php else: ?>
                                    <span style="color:#ccc;">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($p['price'] !== null): ?>
                                    <strong style="color:#ff4500; font-size:1.1rem;"><?= '€'.number_format($p['price'], 2) ?></strong>
                                <?php else: ?>
                                    –
                                <?php endif; ?>
                            </td>
                            <td class="action-links">
                                <a href="product-edit.php?id=<?= $p['id'] ?>" class="text-primary">Ndrysho</a>
                                <a href="product-delete.php?id=<?= $p['id'] ?>" onclick="return confirm('Fshi këtë produkt?');" class="text-danger">Fshi</a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                </div>
             </div>
        </div>

      <?php endif; ?>
    </div>
  </div>

</body>
</html>
