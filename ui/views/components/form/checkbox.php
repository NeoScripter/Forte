<?php

$name = $name ?? '';
$class   = $class   ?? '';
$label   = $label   ?? '';
$error = \Flash::instance()->getKey("errors.{$name}") ?? '';
$attrs   = $attrs   ?? [];

$attrs = array_merge(
    ['name' => $name],
    $attrs,
);


$base = 'relative inline-flex size-4 shrink-0 items-center justify-center rounded-sm border border-input shadow-xs transition-shadow outline-none'
    . ' focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50'
    . ' aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40'
    . ' cursor-pointer disabled:cursor-not-allowed disabled:opacity-50';


$final_class = trim("$base $class");

$attr_string = serialize_attrs($attrs);
?>

<div class="grid gap-2">
    <label class="flex items-center select-none cursor-pointer w-fit gap-2">
        <span data-checkbox-input class="<?= $final_class ?>">
            <input type="checkbox" class="peer sr-only" <?= $attr_string ?>>
            <span data-checkbox-checkmark class="flex items-center justify-center text-current transition-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5">
                    <path d="M20 6 9 17l-5-5" />
                </svg>
            </span>
        </span>
        <span class="text-sm font-medium">
            <?= $label ?>
        </span>
    </label>

    <?= component('form/input-error', ['message' => $error]) ?>
</div>
