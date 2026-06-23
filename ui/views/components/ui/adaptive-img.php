<?php
$uid = 'aimg-' . uniqid();
// $path = preg_replace();
//         $path = preg_replace('/\.webp/', ' ', $exception->getMessage());

?>
<?php if (! empty($path)) : ?>
    <div
        id="<?= $uid ?>"
        class="overflow-clip adaptive-img bg-cover bg-center bg-no-repeat relative <?= $prtClass ?? '' ?>">
        <style>
            #<?= $uid ?> {
                background-image: <?= "url({$path}-mb-tiny.webp)" ?>;
            }

            <?php if (($avif ?? false) && str_contains($sizes, 'tb')): ?>@media screen and (min-width: 31.25rem) {
                #<?= $uid ?> {
                    background-image: <?= "url({$path}-tb-tiny.webp)" ?>;
                }
            }

            <?php endif ?><?php if (($avif ?? false) && str_contains($sizes, 'dk')): ?>@media screen and (min-width: 64rem) {
                #<?= $uid ?> {
                    background-image: <?= "url({$path}-dk-tiny.webp)" ?>;
                }
            }

            <?php endif ?>
        </style>

        <picture>
            <?php if (($avif ?? false) && str_contains($sizes, 'dk')): ?>
                <source
                    type="image/avif"
                    srcset="<?= build_src_set($path, 'dk', 'avif') ?>"
                    media="(min-width: 64rem)" />
            <?php endif ?>

            <?php if (str_contains($sizes, 'dk')): ?>
                <source
                    type="image/webp"
                    srcset="<?= build_src_set($path, 'dk', 'webp') ?>"
                    media="(min-width: 64rem)" />
            <?php endif ?>

            <?php if (($avif ?? false) && str_contains($sizes, 'tb')): ?>
                <source
                    type="image/avif"
                    srcset="<?= build_src_set($path, 'tb', 'avif') ?>"
                    media="(min-width: 31.25rem)" />
            <?php endif ?>

            <?php if (str_contains($sizes, 'tb')): ?>
                <source
                    type="image/webp"
                    srcset="<?= build_src_set($path, 'tb', 'webp') ?>"
                    media="(min-width: 31.25rem)" />
            <?php endif ?>

            <?php if (($avif ?? false) && str_contains($sizes, 'mb')): ?>
                <source
                    type="image/avif"
                    srcset="<?= build_src_set($path, 'mb', 'avif') ?>" />
            <?php endif ?>

            <img
                srcset="<?= build_src_set($path, 'mb', 'webp') ?>"
                loading="lazy"
                alt="<?= $alt ?? '' ?>"
                class="block transition-opacity duration-500 ease-in-out size-full object-cover object-center <?= $imgClass ?? '' ?>" />
        </picture>

        <div
            aria-hidden="true"
            class="animate-loading adaptive-overlay absolute inset-0 size-full bg-white/15 <?= $overlayClass ?? '' ?>"></div>

    </div>
<?php endif; ?>
