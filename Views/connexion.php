<?php
$pageTitle = 'Inscription / Connexion';
$currentPage = 'connexion';
// Variables fournies par AuthController : $errors, $old, $tab, $expired, $csrfToken
$selectedRole = (isset($old['role']) ? $old['role'] : 'citoyen');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<?php require_once __DIR__ . '/includes/head.php'; ?>
</head>
<body>
<?php require_once __DIR__ . '/includes/spinner.php'; ?>
<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<!-- ===== Contenu spécifique à la page ===== -->
<section class="container py-5"><div class="row justify-content-center"><div class="col-lg-9"><div class="row g-0 shadow rounded-4 overflow-hidden">
  <div class="col-md-5 auth-side d-flex flex-column justify-content-center"><h3>Rejoignez LifeBridge</h3><p>Ensemble, pour une société plus solidaire</p></div>
  <div class="col-md-7 p-5">

    <?php if (!empty($expired)): ?>
      <div class="alert alert-warning">Votre session a expiré (30 minutes d'inactivité). Veuillez vous reconnecter.</div>
    <?php endif; ?>
    <?php if (!empty($_GET['registered'])): ?>
      <div class="alert alert-success">Compte créé avec succès, vous pouvez maintenant vous connecter.</div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?><div><?= e($error) ?></div><?php endforeach; ?>
      </div>
    <?php endif; ?>

    <ul class="nav nav-pills mb-4">
      <li class="nav-item"><button type="button" class="nav-link <?= $tab === 'login' ? 'active' : '' ?>" data-bs-toggle="pill" data-bs-target="#login">Connexion</button></li>
      <li class="nav-item"><button type="button" class="nav-link <?= $tab === 'register' ? 'active' : '' ?>" data-bs-toggle="pill" data-bs-target="#signup">Inscription</button></li>
    </ul>

    <div class="tab-content">
      <!-- ===== Connexion ===== -->
      <div class="tab-pane fade <?= $tab === 'login' ? 'show active' : '' ?>" id="login">
        <form method="POST" action="index.php?page=connexion" data-validate="login" novalidate>
          <div class="alert alert-danger js-errors" hidden></div>
          <input type="hidden" name="form_type" value="login">
          <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
          <input type="email" name="email" class="form-control mb-3" placeholder="Adresse e-mail" required autocomplete="email">
          <input type="password" name="password" class="form-control mb-3" placeholder="Mot de passe" required autocomplete="current-password">
          <button type="submit" class="btn btn-lb w-100">Se connecter</button>
        </form>
      </div>

      <!-- ===== Inscription ===== -->
      <div class="tab-pane fade <?= $tab === 'register' ? 'show active' : '' ?>" id="signup">
        <form method="POST" action="index.php?page=connexion" data-validate="register" data-password-required="1"
              novalidate>
          <div class="alert alert-danger js-errors" hidden></div>
          <input type="hidden" name="form_type" value="register">
          <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

          <select name="role" class="form-select mb-3" data-role-toggle>
            <?php foreach (AccessController::$LABELS as $key => $label): ?>
              <option value="<?= e($key) ?>" <?= $selectedRole === $key ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
          <div data-code-group>
            <input type="text" name="code" class="form-control mb-3" placeholder="Code d'inscription (Agent de santé / Sécurité)" autocomplete="off">
          </div>

          <input name="nom" class="form-control mb-3" placeholder="Nom complet" required value="<?= e((isset($old['nom']) ? $old['nom'] : '')) ?>">
          <input type="email" name="email" class="form-control mb-3" placeholder="Adresse e-mail" required value="<?= e((isset($old['email']) ? $old['email'] : '')) ?>" autocomplete="email">
          <input type="password" name="password" class="form-control mb-3" placeholder="Mot de passe (8 caractères minimum)" required autocomplete="new-password">
          <input type="password" name="password_confirm" class="form-control mb-3" placeholder="Confirmer le mot de passe" required autocomplete="new-password">
          <button type="submit" class="btn btn-lb w-100">Créer un compte</button>
        </form>
      </div>
    </div>
  </div>
</div></div></div></section>
<!-- ===== Fin du contenu spécifique ===== -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
