<?php
$pageTitle = 'Mon profil';
$currentPage = 'profil';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<?php require_once __DIR__ . '/includes/head.php'; ?>
</head>
<body>
<?php require_once __DIR__ . '/includes/spinner.php'; ?>
<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<section class="container py-5">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h1 class="section-title">Mon <span>profil</span></h1>
      <p class="lead mb-0">Consultez et modifiez les informations de votre compte.</p>
    </div>
    <a href="<?= e(AccessController::homeUrl($user->getRole())) ?>" class="btn btn-outline-lb">
      <i class="bi bi-arrow-left me-1"></i> Retour à mon espace
    </a>
  </div>

  <?php if ($updated): ?>
    <div class="alert alert-success" role="alert">Votre profil a été modifié avec succès.</div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger" role="alert">
      <strong>Veuillez corriger les points suivants :</strong>
      <ul class="mb-0 mt-2">
        <?php foreach ($errors as $error): ?>
          <li><?= e($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-lg-7">
      <div class="feature-card text-start h-100">
        <h2 class="h4 section-title mb-3">Modifier mes informations</h2>
        <p class="text-muted">Votre rôle actuel : <strong><?= e($roleLabel) ?></strong>. Il ne peut pas être modifié depuis cette page.</p>

        <form method="POST" action="index.php?page=profil">
          <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
          <input type="hidden" name="profile_action" value="update">

          <div class="mb-3">
            <label for="nom" class="form-label">Nom complet</label>
            <input type="text" class="form-control" id="nom" name="nom" value="<?= e($user->getNom()) ?>" minlength="2" maxlength="100" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= e($user->getEmail()) ?>" maxlength="150" required>
          </div>

          <hr class="my-4">
          <h3 class="h6">Changer le mot de passe (facultatif)</h3>
          <p class="text-muted small">Laissez ces deux champs vides pour conserver votre mot de passe actuel.</p>

          <div class="mb-3">
            <label for="password" class="form-label">Nouveau mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" minlength="8" autocomplete="new-password">
          </div>

          <div class="mb-4">
            <label for="password_confirm" class="form-label">Confirmer le nouveau mot de passe</label>
            <input type="password" class="form-control" id="password_confirm" name="password_confirm" minlength="8" autocomplete="new-password">
          </div>

          <button type="submit" class="btn btn-lb">
            <i class="bi bi-check-circle me-1"></i> Enregistrer les modifications
          </button>
        </form>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="feature-card text-start h-100 border border-danger">
        <h2 class="h4 text-danger mb-3">Supprimer mon compte</h2>
        <p>Cette action supprimera définitivement votre compte LifeBridge. Vous serez ensuite déconnecté.</p>
        <div class="alert alert-warning small" role="alert">
          Cette opération est définitive. Vous devrez créer un nouveau compte pour utiliser à nouveau les services réservés aux membres.
        </div>

        <form method="POST" action="index.php?page=profil" data-confirm="Voulez-vous vraiment supprimer définitivement votre compte ?">
          <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
          <input type="hidden" name="profile_action" value="delete">

          <div class="mb-3">
            <label for="current_password" class="form-label">Votre mot de passe actuel</label>
            <input type="password" class="form-control" id="current_password" name="current_password" autocomplete="current-password" required>
          </div>

          <button type="submit" class="btn btn-danger">
            <i class="bi bi-trash me-1"></i> Supprimer mon compte
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
