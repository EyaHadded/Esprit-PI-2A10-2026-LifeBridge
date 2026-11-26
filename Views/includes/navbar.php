<?php
// $currentPage est défini dans la page appelante (ex: $currentPage = 'home';)
if (!isset($currentPage)) { $currentPage = ''; }
function navActive($page, $currentPage) { return $page === $currentPage ? 'active' : ''; }
?>
<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top px-4 px-lg-5">
  <a href="index.php?page=home" class="navbar-brand d-flex align-items-center">
    <span class="brand-mark me-2"><i class="bi bi-droplet-fill"></i></span>
    <span class="brand-text">Life<span>Bridge</span></span>
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navCollapse">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navCollapse">
    <div class="navbar-nav ms-auto py-3 py-lg-0 align-items-lg-center">
      <a href="index.php?page=home"        class="nav-item nav-link <?= navActive('home', $currentPage) ?>">Accueil</a>
      <a href="index.php?page=don-sang"    class="nav-item nav-link <?= navActive('don-sang', $currentPage) ?>">Don de sang</a>
      <a href="index.php?page=don-organes" class="nav-item nav-link <?= navActive('don-organes', $currentPage) ?>">Don d'organes</a>
      <a href="index.php?page=campagnes"   class="nav-item nav-link <?= navActive('campagnes', $currentPage) ?>">Campagnes</a>
      <a href="index.php?page=demandes"    class="nav-item nav-link <?= navActive('demandes', $currentPage) ?>">Demandes</a>
      <a href="index.php?page=contact"     class="nav-item nav-link <?= navActive('contact', $currentPage) ?>">Contact</a>
      <?php if (!empty($_SESSION['user_id'])): ?>
        <a href="<?= e(AccessController::homeUrl((isset($_SESSION['role']) ? $_SESSION['role'] : null))) ?>" class="btn btn-lb ms-lg-3 mt-2 mt-lg-0"><i class="bi bi-person"></i> Mon espace</a>
        <form method="POST" action="index.php?page=deconnexion" class="d-inline ms-lg-2 mt-2 mt-lg-0">
          <input type="hidden" name="csrf_token" value="<?= e(Csrf::token()) ?>">
          <button type="submit" class="btn btn-outline-lb"><i class="bi bi-box-arrow-right"></i> Déconnexion</button>
        </form>
      <?php else: ?>
        <a href="index.php?page=connexion"   class="btn btn-lb ms-lg-3 mt-2 mt-lg-0"><i class="bi bi-person"></i> Connexion</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<!-- Navbar End -->
