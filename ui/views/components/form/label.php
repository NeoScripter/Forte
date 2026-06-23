<?php

$class = $class ?? '';
$attrs = $attrs ?? [];
$slot  = $slot  ?? '';

$base = 'text-sm ml-0.5 leading-none font-medium select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50';

$final_class = trim("$base $class");

$attr_string = serialize_attrs($attrs);
?>
<label data-slot="label" class="<?= $final_class ?>" <?= $attr_string ?>>
    <?= $slot ?>
</label>
