<?php
/** views/login.php — Attend : $errors (array), $csrfToken (string) */
ob_start();
?>
<h1>Connexion</h1>

<?php if (!empty($errors)): ?>
    <div class="alert-error">
        <?php foreach ($errors as $error): ?>
            <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['registered'])): ?>
    <div class="alert-success">Compte créé avec succès, vous pouvez maintenant vous connecter.</div>
<?php endif; ?>

<form method="POST" action="index.php?action=login">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

    <label for="email">Adresse email</label>
    <input type="email" id="email" name="email" required autocomplete="email">

    <label for="password">Mot de passe</label>
    <input type="password" id="password" name="password" required autocomplete="current-password">

    <button type="submit">Se connecter</button>
</form>

<p>Pas encore de compte ? <a href="index.php?action=register">Créer un compte</a></p>
<?php
$content = ob_get_clean();
$title = 'Connexion - MonProjet';
require __DIR__ . '/layout.php';
