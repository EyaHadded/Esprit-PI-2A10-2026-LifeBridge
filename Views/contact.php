<?php
$pageTitle = 'Contact';
$currentPage = 'contact';
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
<section class="container py-5"><div class="row g-5">
  <div class="col-lg-6">
    <h1 class="section-title">Contactez-nous <br><span>Une question ? Une suggestion ?</span></h1>
    <p class="my-4">Notre équipe est à votre écoute pour répondre à toutes vos demandes.</p>
    <p><i class="bi bi-telephone text-danger"></i> +216 71 123 456</p>
    <p><i class="bi bi-envelope text-danger"></i> contact@lifebridge.tn</p>
    <p><i class="bi bi-geo-alt text-danger"></i> Tunis, Tunisie</p>
  </div>
  <div class="col-lg-6"><form class="feature-card text-start">
    <input class="form-control mb-3" placeholder="Nom complet"><input type="email" class="form-control mb-3" placeholder="Adresse e-mail">
    <select class="form-select mb-3"><option>Don de sang</option><option>Don d'organes</option><option>Autre</option></select>
    <textarea class="form-control mb-3" rows="4" placeholder="Votre message..."></textarea>
    <button type="button" class="btn btn-lb w-100"><i class="bi bi-send"></i> Envoyer le message</button>
  </form></div>
</div></section>
<!-- ===== Fin du contenu spécifique ===== -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
