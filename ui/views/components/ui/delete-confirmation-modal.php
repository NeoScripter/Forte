<?php

$item_name = $item_name ?? '';
$modal_id  = $modal_id  ?? '';
$class     = $class     ?? '';

$final_class = trim('space-y-6 ' . $class);
?>
<div class="<?= $final_class ?>">
    <div class="space-y-2">
        <h2 class="text-2xl font-semibold">Delete <?= $item_name ?></h2>
        <p class="text-muted-foreground">
            Are you sure you want to delete this <?= strtolower($item_name) ?>? This action cannot be undone.
        </p>
    </div>
    <div class="flex items-center justify-end gap-4">
        <?= component('ui/auth-button', [
            'variant' => 'destructive',
            'size'    => 'lg',
            'class'   => 'w-fit text-base',
            'slot'    => 'Delete',
            'attrs'   => ['type' => 'submit'],
        ]) ?>
        <?= component('ui/auth-button', [
            'variant' => 'default',
            'size'    => 'lg',
            'class'   => 'w-fit text-base',
            'slot'    => 'Cancel',
            'attrs'   => [
                'type'    => 'button',
                'onclick' => "window.closeModal('{$modal_id}')",
            ],
        ]) ?>
    </div>
</div>
