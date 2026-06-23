<!doctype html>
<html
    lang="en"
    class="overflow-x-clip">

<head>
    <meta charset="UTF-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0" />
    <?= vite_tags('ui/ts/app.ts') ?>
    <link rel="icon" href="/favicon.webp" type="image/webp">
    <title><?= "Swat Relief Initiative" . (isset($title) ? " - {$title}" : '') ?></title>
</head>

<body class="overflow-x-clip min-h-screen font-sans max-w-480 mx-auto">
    <?= component('layout/header') ?>
    <main class="full-bleed-wrapper">
        <?= isset($heading) ? component('layout/hero', compact('heading')) : '' ?>
        <?= ($show_banner ?? false) ? component('ui/banner') : '' ?>
        <?= view($inc, $props ?? []) ?>
    </main>
    <?= component('layout/footer') ?>
    <?= component('layout/nav-popup') ?>
</body>

</html>
