<div class="<?= $prtClass ?? 'relative' ?>">
    <div
        class="absolute inset-0 size-full"
        aria-hidden="true">
        <video
            autoplay
            loop
            muted
            playsinline
            preload="none"
            class="size-full object-cover object-center <?= $videoClass ?? '' ?>"
            data-lazy>
            <source
                data-src="/assets/videos/<?= $src ?>.av1.webm"
                type='video/webm; codecs="av01"' />
            <source data-src="/assets/videos/<?= $src ?>.webm" type="video/webm; codecs=vp9" />
        </video>
    </div>

    <div class="absolute bg-black/25 inset-0 bg-[url(/assets/imgs/shared/overlay-texture.webp)]">
    </div>
</div>
