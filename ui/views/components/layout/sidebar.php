<?php
$links = $links ?? [];
$user = \Base::instance()->get('SESSION.user');
?>
<div id="admin-sidebar" class="md:bg-sidebar pointer-events-none md:pointer-events-auto fixed inset-0 z-20 md:static md:w-full md:shrink-0 transition-colors duration-500 ease-in-out md:self-stretch md:max-w-62">
    <aside class="bg-sidebar inset-y-0 left-0 flex min-h-full w-full max-w-72 flex-col -translate-x-full md:translate-x-0 transition-transform duration-500 ease-in-out px-3 py-2 md:fixed md:max-w-62">

        <header class="relative flex items-center select-none m-2 gap-4">
            <a href="/" class="absolute inset-0"></a>
            <div class="text-sidebar-primary-foreground flex size-10 shrink-0 items-center justify-center rounded-xs">
                <?= view('components/ui/auth-logo') ?>
            </div>
            <div class="ease overflow-x-clip font-medium whitespace-nowrap transition-[max-width] duration-300 max-w-64">
                Admin Panel
            </div>
        </header>

        <div>
            <div class="text-sidebar-foreground/70 mx-2 pt-4 pb-1.5 text-xs">
                Platform
            </div>
            <ul class="text-sidebar-accent-foreground/70">
                <?php foreach ($links as $link): ?>
                    <?= component('ui/sidebar-link', [
                        'url'   => $link['url'],
                        'label' => $link['label'],
                        'icon'  => $link['icon'],
                    ]) ?>
                <?php endforeach ?>
            </ul>
        </div>

        <footer class="relative mt-auto mb-2">
            <?= component('layout/account-menu', ['name' => $user['name'] ?? '']) ?>

            <button data-account-menu-trigger class="text-sidebar-foreground active:bg-sidebar-accent hover:bg-sidebar-accent ease flex items-center gap-2 rounded-sm transition-all duration-200 w-full px-3 py-2">
                <?= component('ui/monogram', ['first_name' => $user['name'] ?? '']) ?>
                <span><?= $user['name'] ?? '' ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto size-4">
                    <path d="m7 15 5 5 5-5" />
                    <path d="m7 9 5-5 5 5" />
                </svg>
            </button>
        </footer>

    </aside>
</div>
