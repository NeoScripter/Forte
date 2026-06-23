<?php

$name = $name ?? '';
$label = $label ?? '';
$error = \Flash::instance()->getKey("errors.{$name}") ?? '';
$files = $value ?? \Flash::instance()->getKey("values.{$name}") ?? [];
$class = $class ?? '';
$attrs = $attrs ?? [];
$uid = uniqid('file_input_');

$files = array_filter($files);

if (! empty($name) && array_key_exists('multiple', $attrs) && $attrs['multiple'] === true) {
    $name .= '[]';
}

$attrs = array_merge(
    ['aria-invalid' => $error ? 'true' : 'false'],
    ['name' => $name],
    $attrs,
);
$required = isset($attrs['required']) && $attrs['required'] === true;

$attr_string = serialize_attrs($attrs);
?>
<div class="grid gap-2">
    <?php if ($label): ?>
        <?= component('form/label', [
            'slot'  => 'Add ' . $label . ($required ? '<span class="text-orange-500">*</span>' : ''),
            'attrs' => ['for' => $uid, 'id' => $uid],
        ]) ?>
    <?php endif ?>

    <file-pond>
        <input id="<?= $uid ?>" type="file" class="<?= $class ?>" <?= $attr_string ?> />
    </file-pond>

    <ul class="grid gap-2 grid-cols-[repeat(auto-fill,minmax(12rem,1fr))]"></ul>

    <template>
        <li class="aspect-square rounded-sm overflow-clip">
            <img class="size-full object-cover object-center" src="" alt="">
        </li>
    </template>

    <?php if (! empty($files)) : ?>
        <?php if ($label): ?>
            <?= component('form/label', [
                'slot'  => 'Current Files',
                'class' => 'mt-2'
            ]) ?>
        <?php endif ?>

        <ul class="grid gap-2 grid-cols-[repeat(auto-fill,minmax(12rem,1fr))]">
            <?php foreach ($files as $path) : ?>
                <?php if (! empty($path)) : ?>
                    <li class="aspect-square rounded-sm overflow-clip">
                        <img class="size-full object-cover object-center" src="<?= $path .  "-mb.webp" ?>" alt="">
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?= component('form/input-error', ['message' => $error]) ?>
</div>
