<section class="p-4">
  <h2 class="fw-bold mb-3"><?= e($heading) ?></h2>
  <div class="panel"><table class="table table-hover"><thead><tr>
    <?php foreach ($columns as $c): ?><th><?= e($c) ?></th><?php endforeach; ?>
  </tr></thead><tbody>
    <?php foreach ($rows as $r): ?><tr><?php foreach ($r as $cell): ?><td><?= e($cell) ?></td><?php endforeach; ?></tr><?php endforeach; ?>
  </tbody></table></div>
</section>
