<?php
$class = $class ?? '';

$final_class = trim('inline-flex gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800 ' . $class);

$tabs = [
    [
        'value' => 'light',
        'label' => 'Light',
        'icon'  => 'sun',
    ],
    [
        'value' => 'dark',
        'label' => 'Dark',
        'icon'  => 'moon',
    ],
    [
        'value' => 'system',
        'label' => 'System',
        'icon'  => 'monitor',
    ],
];
?>
<div class="<?= $final_class ?>" data-appearance-tabs>
    <?php foreach ($tabs as $tab): ?>
        <button
            type="button"
            data-theme="<?= $tab['value'] ?>"
            class="flex items-center rounded-md px-3.5 py-1.5 transition-colors text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60">
            <?= svg($tab['icon']) ?>
            <span class="ml-1.5 text-sm"><?= $tab['label'] ?></span>
        </button>
    <?php endforeach ?>
</div>
