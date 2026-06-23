<?php
$slot = $slot ?? '';
$page_title = $page_title ?? '';

$path = \Base::instance()->PATH;

$nav_items = [
    ['title' => 'Profile',    'href' => '/admin/settings/profile'],
    ['title' => 'Password',   'href' => '/admin/settings/password'],
    ['title' => 'Appearance', 'href' => '/admin/settings/appearance'],
];
?>

<?php ob_start() ?>

<div class="px-4 py-6">
    <?= component('ui/heading', [
        'title'       => 'Profile',
        'description' => 'Manage your profile and account settings',
        'class'       => '[&>h2,&>p]:animate-none',
    ]) ?>

    <div class="flex flex-col space-y-8 lg:flex-row lg:space-y-0 lg:space-x-12">
        <aside class="w-full max-w-xl lg:w-48">
            <nav class="flex flex-col space-y-1 space-x-0">
                <?php foreach ($nav_items as $item): ?>
                    <?= component('ui/auth-button', [
                        'size'    => 'sm',
                        'variant' => 'ghost',
                        'slot'    => '<a href="' . $item['href'] . '" class="absolute inset-0 z-10"></a>' . $item['title'],
                        'attrs'   => ['tabindex' => '-1'],
                        'class'   => 'relative w-full justify-start' . ($path === $item['href'] ? ' bg-muted' : ''),
                    ]) ?>
                <?php endforeach ?>
            </nav>
        </aside>

        <hr class="my-6 xl:hidden">

        <div class="flex-1 md:max-w-2xl">
            <section class="max-w-xl space-y-12">
                <?= $slot ?>
            </section>
        </div>
    </div>
</div>

<?php $slot = ob_get_clean() ?>

<?= view('layouts/admin-layout', compact('slot', 'page_title')) ?>
