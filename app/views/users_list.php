<?php
/**
 * app/views/users_list.php
 * Attend : $user (admin connecte), $users (UserModel[]), $search (string),
 *          $flash (array|null), $csrfToken (string)
 */
ob_start();
$search = $search ?? '';
$currentId = (int) ($_SESSION['user_id'] ?? 0);
?>
<?php if (!empty($flash)): ?>
  <div class="alert <?= $flash['type'] === 'success' ? 'alert-success' : 'alert-error' ?>">
    <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
  </div>
<?php endif; ?>

<div class="card">
  <div class="toolbar">
    <form method="GET" action="index.php" class="search-form">
      <input type="hidden" name="action" value="users">
      <input type="text" name="q" placeholder="Search by username or email"
             value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
      <button type="submit" class="btn btn-secondary">Search</button>
      <?php if ($search !== ''): ?>
        <a class="btn btn-link" href="index.php?action=users">Reset</a>
      <?php endif; ?>
    </form>
    <a class="btn btn-primary" href="index.php?action=user_add">Add user</a>
  </div>

  <table>
    <thead>
      <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Created at</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php if (empty($users)): ?>
        <tr><td colspan="6">No user found.</td></tr>
      <?php endif; ?>
      <?php foreach ($users as $u): /** @var UserModel $u */ ?>
        <?php $isSelf = $u->getId() === $currentId; ?>
        <tr>
          <td><?= $u->getId() ?></td>
          <td><?= htmlspecialchars($u->getUsername(), ENT_QUOTES, 'UTF-8') ?><?= $isSelf ? ' (you)' : '' ?></td>
          <td><?= htmlspecialchars($u->getEmail(), ENT_QUOTES, 'UTF-8') ?></td>
          <td>
            <span class="badge <?= $u->getRole() === 'admin' ? 'badge-admin' : 'badge-user' ?>">
              <?= htmlspecialchars($u->getRole(), ENT_QUOTES, 'UTF-8') ?>
            </span>
          </td>
          <td><?= htmlspecialchars($u->getCreatedAt(), ENT_QUOTES, 'UTF-8') ?></td>
          <td>
            <div class="actions">
              <a class="btn btn-secondary btn-sm" href="index.php?action=user_edit&amp;id=<?= $u->getId() ?>">Edit</a>

              <?php if (!$isSelf): ?>
                <form method="POST" action="index.php?action=user_role&amp;id=<?= $u->getId() ?>">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                  <input type="hidden" name="role" value="<?= $u->getRole() === 'admin' ? 'user' : 'admin' ?>">
                  <button type="submit" class="btn btn-secondary btn-sm">
                    <?= $u->getRole() === 'admin' ? 'Make user' : 'Make admin' ?>
                  </button>
                </form>

                <form method="POST" action="index.php?action=user_delete&amp;id=<?= $u->getId() ?>"
                      data-confirm="Delete this user? This cannot be undone.">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php
$content = ob_get_clean();
$title = 'Users';
$activePage = 'users';
require __DIR__ . '/layout_admin.php';
