<?php

$message = $message ?? '';
$class   = $class   ?? '';
$attrs   = $attrs   ?? [];

if (!$message) return;

$base = 'text-xs ml-1 font-medium text-red-600 dark:text-red-400 input-error';

$final_class = trim("$base $class");

$attr_string = serialize_attrs($attrs);
?>
<p data-input-error class="<?= $final_class ?>" <?= $attr_string ?>><?= $message ?></p>
