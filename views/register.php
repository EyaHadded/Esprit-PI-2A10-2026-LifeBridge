<?php
/** views/register.php — Attend : $errors (array), $old (array), $csrfToken (string) */
ob_start();
$old = $old ?? [];
$selectedRole = $old['role'] ?? 'citoyen';
?>
<h1>Créer un compte</h1>

<?php if (!empty($errors)): ?>
    <div class="alert-error">
        <?php foreach ($errors as $error): ?>
            <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="index.php?action=register">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

    <label for="role">Type de compte</label>
    <select id="role" name="role">
        <?php foreach (AuthController::LABELS as $key => $label): ?>
            <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"
                <?= $selectedRole === $key ? 'selected' : '' ?>>
                <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="code">Code d'inscription (obligatoire pour Agent de santé et Sécurité)</label>
    <input type="text" id="code" name="code" autocomplete="off">

    <label for="username">Nom d'utilisateur</label>
    <input type="text" id="username" name="username" required
           value="<?= htmlspecialchars($old['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

    <label for="email">Adresse email</label>
    <input type="email" id="email" name="email" required
           value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

    <label for="password">Mot de passe (8 caractères minimum)</label>
    <input type="password" id="password" name="password" required autocomplete="new-password">

    <label for="password_confirm">Confirmer le mot de passe</label>
    <input type="password" id="password_confirm" name="password_confirm" required autocomplete="new-password">

    <button type="submit">Créer mon compte</button>
</form>

<p>Déjà un compte ? <a href="index.php?action=login">Se connecter</a></p>
<?php
$content = ob_get_clean();
$title = 'Inscription - MonProjet';
require __DIR__ . '/layout.php';
