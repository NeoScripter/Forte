<?php

$name = $name ?? '';
$label = $label ?? '';
$error = \Flash::instance()->getKey("errors.{$name}") ?? '';
$value = $attrs['value'] ?? \Flash::instance()->getKey("values.{$name}") ?? '';
$class = $class ?? '';
$attrs = $attrs ?? [];
$uid = uniqid('textarea_');

$attrs = array_merge(
    ['aria-invalid' => $error ? 'true' : 'false'],
    ['name' => $name],
    ['id' => $uid],
    $attrs,
);
$required = isset($attrs['required']) && $attrs['required'] === true;

?>
<div class="grid gap-2">
    <?php if ($label): ?>
        <?= component('form/label', [
            'slot'  => $label . ($required ? '<span class="text-orange-500">*</span>' : ''),
            'attrs' => ['for' => $uid],
        ]) ?>
    <?php endif ?>

    <?= component('form/textarea', [
        'class' => $class,
        'slot' => $value,
        'attrs' => $attrs,
    ]) ?>

    <?= component('form/input-error', ['message' => $error]) ?>
</div>
