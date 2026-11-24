<?php
/**
 * views/administration_space.php
 * Attend : $user, $allUsers (UserModel[]), $totalCitoyens, $totalSante, $totalSecurite
 */
ob_start();
?>
<h1>Page Administration</h1>
<p>Bienvenue, <?= htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8') ?>.</p>
<p>Rôle : Sécurité (administration)</p>

<p>
    Citoyens : <?= (int) $totalCitoyens ?> |
    Agents de santé : <?= (int) $totalSante ?> |
    Sécurité : <?= (int) $totalSecurite ?>
</p>

<h2>Tous les utilisateurs</h2>
<table>
    <thead>
        <tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Créé le</th></tr>
    </thead>
    <tbody>
    <?php foreach ($allUsers as $u): ?>
        <tr>
            <td><?= $u->getId() ?></td>
            <td><?= htmlspecialchars($u->getUsername(), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($u->getEmail(), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars(AuthController::LABELS[$u->getRole()] ?? $u->getRole(), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($u->getCreatedAt(), ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php
$content = ob_get_clean();
$title = 'Espace Administration - MonProjet';
require __DIR__ . '/layout.php';
