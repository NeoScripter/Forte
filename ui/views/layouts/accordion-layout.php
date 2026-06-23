<?php

$label = $label ?? '';
$icon  = $icon  ?? '';
$show  = $show  ?? false;
$slot  = $slot  ?? '';
$class = $class ?? '';

$uid = uniqid('accordion_');
$btn_id   = $uid . '_btn';
$panel_id = $uid . '_panel';
$inner_id = $uid . '_inner';
?>
<div class="<?= $class ?>">
    <?= component('ui/button', [
        'variant' => 'outline',
        'class'   => 'w-fit !px-5',
        'attrs'   => [
            'id'             => $btn_id,
            'type'           => 'button',
            'aria-expanded'  => $show ? 'true' : 'false',
            'aria-controls'  => $panel_id,
            'onclick'        => "
                (function(btn, panel, inner) {
                    var open = btn.getAttribute('aria-expanded') === 'true';
                    open = !open;
                    btn.setAttribute('aria-expanded', open);
                    if (open) {
                        panel.classList.remove('grid-rows-[0fr]');
                        panel.classList.add('grid-rows-[1fr]', 'border-input', 'mb-2', 'border', 'rounded-md', 'p-4');
                        panel.removeAttribute('inert');
                    } else {
                        panel.classList.add('grid-rows-[0fr]');
                        panel.classList.remove('grid-rows-[1fr]', 'border-input', 'mb-2', 'border', 'rounded-md', 'p-4');
                        panel.setAttribute('inert', '');
                    }
                    btn.querySelector('.icon-show').classList.toggle('hidden', !open);
                    btn.querySelector('.icon-hide').classList.toggle('hidden', open);
                })(
                    document.getElementById('" . $btn_id . "'),
                    document.getElementById('" . $panel_id . "'),
                    document.getElementById('" . $inner_id . "')
                )
            ",
        ],
        'slot' => '
            <span class="icon-hide ' . ($show ? '' : 'hidden') . ' contents">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4"><path d="M12 17V3"/><path d="m6 11 6-8 6 8"/><path d="M19 21H5"/></svg>
                Hide
            </span>
            <span class="icon-show ' . ($show ? 'hidden' : '') . ' contents">
                ' . $icon . '
                ' . $label . '
            </span>
        ',
    ]) ?>

    <div
        id="<?= $panel_id ?>"
        <?= !$show ? 'inert' : '' ?>
        class="mt-4 grid transition-[grid-template-rows,padding] duration-500 ease-in-out <?= $show ? 'border-input mb-2 grid-rows-[1fr] rounded-md border p-4' : 'grid-rows-[0fr]' ?>"
    >
        <div id="<?= $inner_id ?>" class="overflow-hidden">
            <?= $slot ?>
        </div>
    </div>
</div>
