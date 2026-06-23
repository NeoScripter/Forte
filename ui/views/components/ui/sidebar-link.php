<?php

$label = $label ?? '';
$url   = $url   ?? '';
$icon  = $icon  ?? '';
$class = $class ?? '';

$path   = \Base::instance()->PATH;
$active = $path === $url || ($url !== '/admin' && str_contains($path, $url));

$link_class = trim(implode(' ', array_filter([
    'active:bg-sidebar-accent hover:bg-sidebar-accent ease my-0.5 flex items-center rounded-sm transition-colors duration-200 mx-1 gap-2 px-3 py-2',
    $active ? 'bg-sidebar-accent' : '',
    $class,
])));
?>
<li>
    <a href="<?= $url ?>" class="<?= $link_class ?>">
        <?= svg($icon) ?>
        <span class="ease overflow-x-clip whitespace-nowrap transition-[max-width] duration-300 max-w-64">
            <?= $label ?>
        </span>
    </a>
</li>
