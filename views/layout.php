<?php
/**
 * views/layout.php
 * Assemble les partials dans le bon ordre et injecte $content.
 * Attend : $title (string), $content (string)
 */
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/header.php';
?>
<main class="container">
    <?= $content ?>
</main>
<?php require_once __DIR__ . '/partials/footer.php'; ?>
