<?php

declare(strict_types=1);

function convert_to_plural($word)
{
    $word = strtolower($word);

    // Already plural
    if (str_ends_with($word, 's')) {
        return $word;
    }

    // Consonant + Y → change to IES
    if (preg_match('/[^aeiou]y$/', $word)) {
        return substr($word, 0, -1) . 'ies';
    }

    // Vowel + Y or anything else → just add S
    return $word . 's';
}

function convert_to_snake_case($word)
{
    return strtolower(preg_replace('/(?<!^)(?=[A-Z])/', '_', $word));
}

function convert_to_kebab_case($word)
{
    return strtolower(preg_replace('/(?<!^)(?=[A-Z])/', '-', $word));
}

function to_wildcards(array $arr, ?string $placeholder = '?')
{
    return implode(
        ',',
        array_fill(0, count($arr), $placeholder)
    );
}


