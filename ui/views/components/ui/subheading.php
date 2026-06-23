<?php

$title       = $title       ?? '';
$description = $description ?? '';
$class       = $class       ?? '';
?>
<header class="<?= $class ?>">
    <h3 class="motion-safe:animate-fade-in mb-0.5 text-base font-medium hyphens-auto">
        <?= $title ?>
    </h3>
    <?php if ($description): ?>
        <p class="text-muted-foreground motion-safe:animate-fade-in text-sm">
            <?= $description ?>
        </p>
    <?php endif ?>
</header>
