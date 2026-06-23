<?php

$label = $label ?? '';
$url   = $url   ?? '';
$icon  = $icon  ?? '';
$class = $class ?? '';

$link_class = trim(implode(' ', array_filter([
    'active:bg-sidebar-accent hover:bg-sidebar-accent ease flex items-center rounded-sm transition-colors duration-200 w-full px-3 py-2',
    $class,
])));
?>
<li>
    <form action="<?= $url ?>" method="post" class="my-0.5 mx-1">
        <input type="hidden" name="_method" value="delete">
        <?= csrf() ?>

        <button type="submit" class="<?= $link_class ?>">
            <?= svg($icon) ?>
            <span class="ease overflow-x-clip whitespace-nowrap transition-[max-width] duration-300 max-w-64">
                <?= $label ?>
            </span>
        </button>
    </form>
</li>
