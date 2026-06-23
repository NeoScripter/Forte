<?php

$class = $class ?? '';
$attrs = $attrs ?? [];

$base = 'border-input selection:bg-primary selection:text-primary-foreground file:text-foreground placeholder:text-muted-foreground flex h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-sm shadow-xs transition-[border,color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm'
    . ' focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]'
    . ' aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40';

$final_class = trim("$base $class");

$attr_string = serialize_attrs($attrs);
?>
<input data-slot="input" class="<?= $final_class ?>" <?= $attr_string ?>>
