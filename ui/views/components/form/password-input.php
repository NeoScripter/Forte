<?php

/**
 * PasswordInput Component
 *
 * Props:
 * @var string|null $class  Additional CSS classes for the wrapper div
 * @var array|null  $attrs  Extra HTML attributes for the input
 */

$class = $class ?? '';
$attrs = $attrs ?? [];

$base = 'border-input selection:bg-primary selection:text-primary-foreground file:text-foreground placeholder:text-muted-foreground flex h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm'
    . ' focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]'
    . ' aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40';

$wrapper_class = trim('relative h-9 ' . $class);

$uid = uniqid('pw_');

$attr_string = serialize_attrs($attrs);
?>
<div class="<?= $wrapper_class ?>">
    <input
        id="<?= $uid ?>"
        type="password"
        data-slot="input"
        class="<?= $base ?>"
        <?= $attr_string ?>>
    <button
        type="button"
        data-password-input-btn
        class="absolute top-1/2 right-4 size-4 -translate-y-1/2">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden icon-eye size-full">
            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
            <circle cx="12" cy="12" r="3" />
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-full icon-eye-off">
            <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49" />
            <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
            <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143" />
            <path d="m2 2 20 20" />
        </svg>
    </button>
</div>
