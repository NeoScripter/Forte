<?php

$label    = $label    ?? '';
$id       = $id       ?? '';
$disabled = $disabled ?? false;

$base = 'flex h-fit w-fit cursor-pointer items-center gap-2 rounded-md bg-slate-800 px-6 py-3 text-sm text-white transition-opacity duration-200 focus-within:opacity-90 hover:opacity-90';

$final_class = trim($base . ($disabled ? ' cursor-not-allowed opacity-50' : ''));
?>
<label for="<?= $id ?>" class="<?= $final_class ?>">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4.5">
        <path d="M21 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6" />
        <path d="m21 3-9 9" />
        <path d="M15 3h6v6" />
    </svg>
    <?= $label ?>
</label>
