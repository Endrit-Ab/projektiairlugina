<?php
require_once __DIR__ . '/init.php';
$auth = new Auth();
$contactModel = new Contact();

$errors = [];
$success = false;
$old = $_POST;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $errors = Validator::contact($_POST);
  if (empty($errors)) {
    $contactModel->saveMessage(
      trim($_POST['name']),
      trim($_POST['email']),
      trim($_POST['subject']),
      trim($_POST['message'])
    );
    $success = true;
    $old = [];
  }
}

$pageTitle = 'Contact Us';
$heroSubtitle = 'Contact';
$heroTitle = 'Contact Us';
$heroDesc = 'Dërgoju një mesazh.';

require __DIR__ . '/includes/header.php';
?>
<div class="content-wrap" style="max-width: 600px; margin: 2rem auto; padding: 1rem;">
  <h1>Contact Us</h1>
  <?php if ($success): ?>
    <p class="success-msg" style="color: green;">Mesazhi u dërgua. Do të ju përgjigjemi sa më shpejt!</p>
  <?php endif; ?>
  <form method="post" action="" id="contactForm">
    <div style="margin-bottom: 1rem;">
      <label for="name">Emri *</label>
      <input type="text" id="name" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required style="width: 100%; padding: 0.5rem;">
      <?php if (!empty($errors['name'])): ?>
        <span class="field-error"><?= htmlspecialchars($errors['name']) ?></span>
      <?php endif; ?>
    </div>
    <div style="margin-bottom: 1rem;">
      <label for="email">Email *</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required style="width: 100%; padding: 0.5rem;">
      <?php if (!empty($errors['email'])): ?>
        <span class="field-error"><?= htmlspecialchars($errors['email']) ?></span>
      <?php endif; ?>
    </div>
    <div style="margin-bottom: 1rem;">
      <label for="subject">Subjekti *</label>
      <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($old['subject'] ?? '') ?>" required style="width: 100%; padding: 0.5rem;">
      <?php if (!empty($errors['subject'])): ?>
        <span class="field-error"><?= htmlspecialchars($errors['subject']) ?></span>
      <?php endif; ?>
    </div>
    <div style="margin-bottom: 1rem;">
      <label for="message">Mesazhi *</label>
      <textarea id="message" name="message" rows="5" required style="width: 100%; padding: 0.5rem;"><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
      <?php if (!empty($errors['message'])): ?>
        <span class="field-error"><?= htmlspecialchars($errors['message']) ?></span>
      <?php endif; ?>
    </div>
    <button type="submit" class="submit-btn">Dërgo</button>
  </form>
</div>
<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
  var name = document.getElementById('name').value.trim();
  var email = document.getElementById('email').value.trim();
  var subject = document.getElementById('subject').value.trim();
  var message = document.getElementById('message').value.trim();
  if (!name) { e.preventDefault(); alert('Emri është i detyrueshëm.'); return false; }
  if (!email) { e.preventDefault(); alert('Email-i është i detyrueshëm.'); return false; }
  if (!subject) { e.preventDefault(); alert('Subjekti është i detyrueshëm.'); return false; }
  if (!message) { e.preventDefault(); alert('Mesazhi është i detyrueshëm.'); return false; }
});
</script>
<?php require __DIR__ . '/includes/footer.php'; ?>
