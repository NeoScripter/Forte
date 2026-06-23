<?php
$id    = $id    ?? '';
$name  = $name  ?? '';
$email = $email ?? '';
?>
<div
    id="<?= $id ?>"
    class="bg-background border-muted divide-muted pointer-events-none absolute bottom-13 left-0 z-10 w-[max(100%,14rem)] origin-bottom-right scale-90 divide-y border opacity-0 shadow-sm transition-[opacity,scale] md:rounded-lg"
    data-account-menu
>
    <ul class="divide-muted divide-y">
        <li class="flex items-center gap-3 px-3 py-2">
            <?= component('ui/monogram', ['first_name' => $name]) ?>
            <div>
                <div class="text-sm font-bold"><?= $name ?></div>
                <div class="text-muted-foreground text-xs"><?= $email ?></div>
            </div>
        </li>
        <?= component('ui/sidebar-link', [
            'url'   => '/admin/settings/profile',
            'label' => 'Settings',
        ]) ?>
        <?= component('ui/sidebar-delete-btn', [
            'url'   => "/logout",
            'label' => 'Log out',
        ]) ?>
    </ul>
</div>
