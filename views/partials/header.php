<?php
/** views/partials/header.php : barre de navigation (dépend de la session) */
$roleKey = $_SESSION['role'] ?? '';
?>
<nav class="topnav">
    <strong>MonProjet</strong>
    <span>
        <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="index.php?action=home">Mon espace</a>
            <a href="index.php?action=logout">Déconnexion</a>
        <?php else: ?>
            <a href="index.php?action=login">Connexion</a>
            <a href="index.php?action=register">Créer un compte</a>
        <?php endif; ?>
    </span>
</nav>
