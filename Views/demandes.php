<?php
$pageTitle = 'Demandes';
$currentPage = 'demandes';
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
<section class="container py-5">
  <h1 class="section-title mb-4">Demandes de <span>contact</span></h1>
  <p>Les établissements de santé autorisés peuvent contacter les volontaires ; vous acceptez ou refusez chaque demande.</p>
  <table class="table table-hover"><thead><tr><th>Établissement</th><th>Date</th><th>Statut</th><th></th></tr></thead><tbody>
    <tr><td>Hôpital Charles Nicolle</td><td>10/01/2026</td><td><span class="badge text-bg-warning">En attente</span></td><td><button class="btn btn-sm btn-lb">Accepter</button> <button class="btn btn-sm btn-outline-secondary">Refuser</button></td></tr>
  </tbody></table>
</section>
<!-- ===== Fin du contenu spécifique ===== -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
