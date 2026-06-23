<?php
$title       = $title       ?? '';
$description = $description ?? '';
$class       = $class       ?? '';

$final_class = trim('mb-8 space-y-0.5 ' . $class);
?>
<div class="<?= $final_class ?>">
    <h2 class="motion-safe:animate-fade-in text-xl font-semibold tracking-tight hyphens-auto">
        <?= $title ?>
    </h2>
    <?php if ($description): ?>
        <p class="text-muted-foreground motion-safe:animate-fade-in text-sm">
            <?= $description ?>
        </p>
    <?php endif ?>
</div>
