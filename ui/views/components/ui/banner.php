<?php
$is_donate = \Base::instance()->URI === '/donate'; ?>
<div class="bg-nav text-gray-50 full-bleed sm:justify-around md:z-1000 py-6 xl:py-8 border-t border-gray-50/30 px-(--px) flex flex-col sm:flex-row gap-8">
    <div class="mx-auto <?= $is_donate ? '' : 'lg:max-w-[85ch]' ?> text-balance uppercase">
        <p>
            <?= $is_donate ?
                'Swat Relief initiative is a 501(c)(3) non-profit organization, so your donation is tax-deductible. Our tax ID is 27-1940612.' :
                '<strong>100%</strong> of donations go directly toward programs for disadvantaged communities. SRI’s board members pay for all overhead costs from their personal funds.' ?>
        </p>
    </div>

    <?php if (! $is_donate) : ?>
        <div class="self-center">
            <a href="/donate" class="gradient-button flex items-center justify-center uppercase tracking-widest font-bold leading-[1em]">
                donate
            </a>
        </div>
    <?php endif; ?>
</div>
