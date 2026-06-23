<?php
/**
 * Button Component
 *
 * Props:
 * @var string|null $variant  'default' | 'destructive' | 'outline' | 'secondary' | 'ghost' | 'link'
 * @var string|null $size     'default' | 'sm' | 'lg' | 'icon'
 * @var string|null $href     When set, renders an <a> tag instead of <button>
 * @var string|null $class    Additional CSS classes
 * @var array|null  $attrs    Extra HTML attributes as key => value pairs (e.g. ['type' => 'submit', 'disabled' => true])
 * @var mixed       $slot     Inner content (HTML string or text)
 */

$variant = $variant ?? 'default';
$size    = $size    ?? 'default';
$href    = $href    ?? null;
$class   = $class   ?? '';
$attrs   = $attrs   ?? [];
$slot    = $slot    ?? '';

$variant_classes = [
    'default'     => 'bg-primary text-primary-foreground shadow-xs hover:bg-primary/90',
    'destructive' => 'bg-destructive text-white shadow-xs hover:bg-destructive/90 focus-visible:ring-destructive/20 dark:focus-visible:ring-destructive/40',
    'outline'     => 'border border-input bg-background shadow-xs hover:bg-accent hover:text-accent-foreground',
    'secondary'   => 'bg-secondary text-secondary-foreground shadow-xs hover:bg-secondary/80',
    'ghost'       => 'hover:bg-accent hover:text-accent-foreground',
    'link'        => 'text-primary underline-offset-4 hover:underline',
];

$size_classes = [
    'default' => "h-9 px-4 py-2 has-[>svg]:px-3",
    'sm'      => "h-8 rounded-md px-3 has-[>svg]:px-2.5",
    'lg'      => "h-10 rounded-md px-6 has-[>svg]:px-4",
    'icon'    => 'size-9',
];

$base = 'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium'
      . ' transition-[color,box-shadow]'
      . ' disabled:pointer-events-none disabled:opacity-50'
      . " [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 [&_svg]:shrink-0"
      . " outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]"
      . ' aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive';

$final_class = trim(implode(' ', array_filter([
    $base,
    $variant_classes[$variant] ?? $variant_classes['default'],
    $size_classes[$size]       ?? $size_classes['default'],
    $class,
])));

$tag = $href ? 'a' : 'button';

$attr_string = serialize_attrs($attrs);
if ($href) {
    $attr_string .= " href=\"$href\"";
}
?>
<<?= $tag ?> data-slot="button" class="<?= $final_class ?>"<?= $attr_string ?>>
    <?= $slot ?>
</<?= $tag ?>>
