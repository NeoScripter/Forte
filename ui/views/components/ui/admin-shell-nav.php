<?php

$href = $href ?? '';
?>
<nav class="mb-2">
    <?= component('ui/auth-button', [
        'href'    => $href,
        'variant' => 'default',
        'slot'    => 'Create New',
        'class'   => 'h-9 rounded-sm text-sm',
    ]) ?>
</nav>
