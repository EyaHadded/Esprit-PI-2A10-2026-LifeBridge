<?php
$nomAdmin = isset($_SESSION['nom']) ? $_SESSION['nom'] : 'Admin';
$initiales = function_exists('mb_substr') ? mb_strtoupper(mb_substr($nomAdmin, 0, 2)) : strtoupper(substr($nomAdmin, 0, 2));
?>
<header class="topbar d-flex justify-content-between align-items-center px-4 py-3 bg-white shadow-sm">
  <div class="d-flex align-items-center gap-3">
    <button id="menu-toggle" class="btn btn-outline-secondary btn-sm"><i class="bi bi-list"></i></button>
    <strong>Back Office - LifeBridge</strong>
  </div>
  <div class="d-flex align-items-center gap-3">
    <span class="position-relative"><i class="bi bi-bell fs-5"></i><span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">3</span></span>
    <span class="avatar"><?= e($initiales) ?></span> <span><?= e($nomAdmin) ?></span>
    <a href="../index.php?page=home" class="btn btn-outline-secondary btn-sm">Voir le site</a>
  </div>
</header>
