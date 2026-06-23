<section class="app-section text-white bg-nav full-bleed px-4">
    <h1 class="font-bold tracking-wider text-center text-[clamp(2rem,9vw,3rem)] leading-[1.25em] uppercase text-gray-50 lg:text-6xl xl:text-7xl"><?= $heading ?></h1>

    <?php if (isset(\Base::instance()->subheading)) : ?>
        <p class="text-balance w-4/5 sm:w-full lg:text-2xl sm:text-center mt-[calc(var(--py)/2)] mx-auto"><?= \Base::instance()->subheading ?></p>
    <?php endif; ?>
</section>
