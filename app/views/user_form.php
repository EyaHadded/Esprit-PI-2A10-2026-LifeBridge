<?php
/**
 * app/views/user_form.php (ajout ET modification)
 * Attend : $user (admin connecte), $mode ('add'|'edit'), $errors (array),
 *          $old (array), $target (UserModel|null), $csrfToken (string)
 */
ob_start();
$isEdit = ($mode ?? 'add') === 'edit';
$old = $old ?? [];
$isSelf = $isEdit && $target && $target->getId() === (int) ($_SESSION['user_id'] ?? 0);
$currentRole = $old['role'] ?? ($target ? $target->getRole() : 'user');
$action = $isEdit
    ? 'index.php?action=user_edit&id=' . (int) $target->getId()
    : 'index.php?action=user_add';
?>
<div class="card form-card">
  <h2><?= $isEdit ? 'Edit user' : 'Add a user' ?></h2>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <?php foreach ($errors as $error): ?>
        <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>"
        data-validate="<?= $isEdit ? 'user-edit' : 'user-add' ?>" novalidate>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required
             value="<?= htmlspecialchars($old['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="form-group">
      <label for="email">Email address</label>
      <input type="email" id="email" name="email" required
             value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="form-group">
      <label for="password"><?= $isEdit ? 'New password (leave empty to keep the current one)' : 'Password (min. 8 characters)' ?></label>
      <input type="password" id="password" name="password" autocomplete="new-password" <?= $isEdit ? '' : 'required' ?>>
    </div>

    <div class="form-group">
      <label for="password_confirm">Confirm password</label>
      <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password" <?= $isEdit ? '' : 'required' ?>>
    </div>

    <div class="form-group">
      <label for="role">Role</label>
      <select id="role" name="role" <?= $isSelf ? 'disabled' : '' ?>>
        <option value="user" <?= $currentRole === 'user' ? 'selected' : '' ?>>user</option>
        <option value="admin" <?= $currentRole === 'admin' ? 'selected' : '' ?>>admin</option>
      </select>
      <?php if ($isSelf): ?>
        <div class="hint">You cannot change your own role.</div>
      <?php endif; ?>
    </div>

    <div class="actions">
      <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Save changes' : 'Create user' ?></button>
      <a class="btn btn-link" href="index.php?action=users">Cancel</a>
    </div>
  </form>
</div>
<?php
$content = ob_get_clean();
$title = $isEdit ? 'Edit user' : 'Add user';
$activePage = 'users';
require __DIR__ . '/layout_admin.php';
