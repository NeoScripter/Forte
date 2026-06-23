<?php $slot = $slot ?? ''; ?>

<main
    class='text-sidebar-foreground bg-sidebar h-full min-h-svh text-sm md:flex md:items-start md:p-2' id="admin">
    <?= component('layout/sidebar', [
        'links' => [
            [
                'url'   => '/admin',
                'label' => 'Dashboard',
                'icon'  => 'layout-grid',
            ],
            [
                'url'   => '/admin/featured',
                'label' => 'Featured Section',
                'icon'  => 'feather',
            ],
        ],
    ]) ?>

    <div class="bg-background border-muted w-full border shadow-sm md:rounded-lg">
        <header
            class='border-muted flex items-center gap-3 border-b px-4 py-4'>
            <button 
                data-open-sidebar-btn
                type='button'
                class="rounded-sm p-1.5">
                <?= svg('panel-left-icon') ?>
            </button>
            <span><?= $page_title ?? '' ?></span>
        </header>
        <?= $slot ?>
    </div>
</main>
