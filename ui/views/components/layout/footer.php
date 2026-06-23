<?php $items = include_once(APP_DIR . '/ui/data/FOOTER_SOCIALS.php'); ?>

<footer>
    <?php if (isset($items) && !empty($items)) : ?>
        <div class="app-section bg-neutral-800 px-(--px) text-white">
            <ul class="grid gap-6 md:gap-10 xl:gap-14 grid-cols-[repeat(auto-fit,minmax(min(32ch,100%),1fr))]">
                <?php foreach ($items as $idx => $item): ?>
                    <li>
                        <h4 class="uppercase"><?= $item['title'] ?></h4>
                        <hr class="my-4 md:my-6 xl:my-8 block h-[0.1em] bg-gray-50" />
                        <?php if (isset($item['links']) && !empty($item['links'])) : ?>
                            <ul class="space-y-3">
                                <?php foreach ($item['links'] as $link): ?>
                                    <li class="text-balance">
                                        <?php if (isset($link['url'])) : ?>
                                            <a class="hover:underline hover:motion-safe:animate-jump block underline-offset-4" href="<?= $link['url'] ?>">
                                                <?= $link['label'] ?>
                                            </a>
                                        <?php else: ?>
                                            <?= $link['label'] ?>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <section class="app-section text-gray-50 text-balance bg-neutral-900 px-(--px) py-[calc(var(--py)/2)]">
        <p>Copyright © Swat Relief Initiative, Inc. <?= date('Y') ?></p>
    </section>
</footer>
