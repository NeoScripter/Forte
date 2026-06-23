<?php

/**
 * Monogram Component
 *
 * Props:
 * @var string      $first_name  The name to derive the monogram from
 * @var string|null $class       Additional CSS classes
 */

$first_name = $first_name ?? '';
$class      = $class      ?? '';
?>

<span class="<?= trim("bg-sidebar-accent flex size-8 shrink-0 items-center justify-center rounded-sm p-1 $class") ?>">
    <?= mb_substr($first_name, 0, 1) ?>
</span>
