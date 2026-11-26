<?php
/**
 * Layout principal : inclut les blocs communs et injecte $content.
 * Variables : $title, $currentPage, $content, $extraScripts (optionnel)
 */
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/sidebar.php';
?>
<main class="main-wrapper">
  <?php require_once __DIR__ . '/partials/header.php'; ?>

  <?php if ($flash = pullFlash()): ?>
    <div class="alert alert-<?= e($flash['type']) ?> mx-4 mt-3 mb-0"><?= e($flash['message']) ?></div>
  <?php endif; ?>

  <?= $content ?>

  <?php require_once __DIR__ . '/partials/footer.php'; ?>
</main>
<?php require_once __DIR__ . '/partials/scripts.php'; ?>
