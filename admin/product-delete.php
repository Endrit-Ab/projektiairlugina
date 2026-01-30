<?php
require_once __DIR__ . '/../init.php';
$auth = new Auth();
$auth->requireAdmin();

$id = (int)($_GET['id'] ?? 0);
if ($id) {
  $productModel = new Product();
  $productModel->delete($id);
}
header('Location: dashboard.php?tab=products');
exit;
