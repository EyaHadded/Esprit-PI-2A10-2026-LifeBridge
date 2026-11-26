<?php
$currentPage = isset($currentPage) ? $currentPage : 'dashboard';
$items = [
  'dashboard'    => ['Tableau de bord','bi-house-door'],
  'utilisateurs' => ['Utilisateurs','bi-people'],
  'donneurs'     => ['Donneurs','bi-droplet'],
  'receveurs'    => ['Receveurs','bi-heart-pulse'],
  'campagnes'    => ['Campagnes','bi-megaphone'],
  'dons-sang'    => ['Dons de sang','bi-droplet-half'],
  'dons-organes' => ["Dons d'organes",'bi-lungs'],
  'messages'     => ['Messages','bi-envelope'],
  'parametres'   => ['Paramètres','bi-gear'],
];
?>
<aside class="sidebar">
  <a class="sidebar-logo" href="index.php?page=dashboard"><i class="bi bi-droplet-fill"></i> Life<span>Bridge</span></a>
  <nav><ul class="list-unstyled mb-0">
  <?php foreach ($items as $key => $item): ?>
    <li><a href="index.php?page=<?= e($key) ?>" class="<?= isActive($key, $currentPage) ?>"><i class="bi <?= e($item[1]) ?>"></i> <?= e($item[0]) ?></a></li>
  <?php endforeach; ?>
    <li>
      <form method="POST" action="../index.php?page=deconnexion" class="px-3 py-2">
        <input type="hidden" name="csrf_token" value="<?= e(Csrf::token()) ?>">
        <button type="submit" class="btn btn-link text-decoration-none text-white p-0"><i class="bi bi-box-arrow-right"></i> Déconnexion</button>
      </form>
    </li>
  </ul></nav>
</aside>
<div class="overlay"></div>
