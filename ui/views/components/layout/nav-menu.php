<?php
$nav_links = [
    ['url' => '/', 'label' => 'Home'],
    // ['url' => '/projects', 'label' => 'Project'],
    ['url' => '/history', 'label' => 'History'],
    ['url' => '/memberships', 'label' => 'Memberships'],
    ['url' => '/volunteers', 'label' => 'Volunteers'],
    // ['url' => '/events', 'label' => 'Events'],
    // ['url' => '/media', 'label' => 'Media'],
    // ['url' => '/alliances', 'label' => 'Alliances'],
    // ['url' => '/faqs', 'label' => 'FAQ'],
    ['url' => '/donate', 'label' => 'Donate'],
]; ?>

<nav class="<?= $class ?? '' ?>">
    <ol class="flex flex-col md:flex-row md:flex-wrap md:items-baseline text-lg md:text-base xl:text-lg gap-4 md:gap-8 xl:gap-10 justify-end">
        <?php foreach ($nav_links as $link) : ?>
            <?= view('components/layout/nav-link', $link) ?>
        <?php endforeach; ?>
    </ol>
</nav>
