<?php
/** views/citoyen_space.php — Attend : $user (UserModel) */
ob_start();
?>
<h1>Page Citoyen</h1>
<p>Bienvenue, <?= htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8') ?>.</p>
<p>Rôle : Citoyen</p>
<p>Email : <?= htmlspecialchars($user->getEmail(), ENT_QUOTES, 'UTF-8') ?></p>
<?php
$content = ob_get_clean();
$title = 'Espace Citoyen - MonProjet';
require __DIR__ . '/layout.php';
