<li>
    <a
        href="<?= $url ?>"
        class="flex items-center justify-center isolate relative <?= $url === \Base::instance()->PATH ? 'text-nav-background font-semibold md:before:absolute md:before:-inset-x-2 md:before:-inset-y-0.5 md:before:bg-gray-50 md:before:rounded-sm md:before:-z-1' : 'hover:underline underline-offset-4' ?>">
        <?= $label ?>
    </a>
</li>
