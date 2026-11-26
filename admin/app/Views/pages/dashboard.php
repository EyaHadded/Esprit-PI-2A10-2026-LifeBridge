<section class="p-4">
  <h2 class="fw-bold mb-1">Bienvenue, <span class="text-danger"><?= e((isset($_SESSION['nom']) ? $_SESSION['nom'] : 'Admin')) ?> !</span></h2>
  <p class="text-muted">Ensemble, sauvons des vies.</p>
  <div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stat-card"><div class="text-muted">Total des dons de sang</div><div class="value"><?= number_format($stats['sang']) ?></div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="text-muted">Total des dons d'organes</div><div class="value"><?= number_format($stats['organes']) ?></div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="text-muted">Utilisateurs inscrits</div><div class="value"><?= number_format($stats['users']) ?></div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="text-muted">Campagnes actives</div><div class="value"><?= $stats['campagnes'] ?></div></div></div>
  </div>
  <div class="row g-3 mb-4">
    <?php foreach ($roles as $label => $count): ?>
    <div class="col-md-4"><div class="stat-card"><div class="text-muted"><?= e($label) ?></div><div class="value"><?= (int) $count ?></div></div></div>
    <?php endforeach; ?>
  </div>
  <div class="panel mb-4"><h5>Statistiques générales</h5><canvas id="mainChart" height="90"></canvas></div>
  <div class="row g-3">
    <div class="col-lg-6"><div class="panel"><h5>Derniers utilisateurs inscrits</h5>
      <table class="table table-sm"><thead><tr><th>Nom</th><th>Email</th><th>Date</th><th>Rôle</th></tr></thead><tbody>
      <?php foreach ($users as $u): ?><tr><td><?= e($u[0]) ?></td><td><?= e($u[1]) ?></td><td><?= e($u[2]) ?></td><td><span class="badge text-bg-secondary"><?= e($u[3]) ?></span></td></tr><?php endforeach; ?>
      </tbody></table></div></div>
    <div class="col-lg-6"><div class="panel"><h5>Dons récents</h5>
      <table class="table table-sm"><thead><tr><th>Type</th><th>Donneur</th><th>Date</th><th>Lieu</th></tr></thead><tbody>
      <?php foreach ($dons as $d): ?><tr><td><?= e($d[0]) ?></td><td><?= e($d[1]) ?></td><td><?= e($d[2]) ?></td><td><?= e($d[3]) ?></td></tr><?php endforeach; ?>
      </tbody></table></div></div>
  </div>
</section>
