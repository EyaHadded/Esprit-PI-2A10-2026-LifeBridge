<?php
/** views/sante_space.php — Attend : $user (UserModel) */
ob_start();
?>
<h1>Page Agent de santé</h1>
<p>Bienvenue, <?= htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8') ?>.</p>
<p>Rôle : Agent de santé</p>
<p>Email : <?= htmlspecialchars($user->getEmail(), ENT_QUOTES, 'UTF-8') ?></p>
<?php
$content = ob_get_clean();
$title = 'Espace Agent de santé - MonProjet';
require __DIR__ . '/layout.php';
