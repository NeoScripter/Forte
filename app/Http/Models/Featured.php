<?php

namespace Http\Models;

use PDOException;
use Support\ErrorHandler;

class Featured
{
    public static function get()
    {
        $hive = \Base::instance();

        try {
            $rows = $hive->DB->exec("
            SELECT f.id, f.title, f.subtitle, f.body, f.html, f.shown, i.id as img_id, i.imageable_id, i.imageable_type, i.src, i.alt
            FROM featured_sections f
            LEFT JOIN images i ON i.imageable_id = f.id AND i.imageable_type = 'featured' LIMIT 1");
        } catch (PDOException $e) {
            ErrorHandler::handle($e);
        }

        if (! empty($rows)) {
            return $rows[0];
        }

        return null;
    }

    public static function update(array $data)
    {
        $hive = \Base::instance();

        add_markdown_field($data, 'body', 'html');

        $section = $hive->get('_FEATURED_SECTIONS');
        $section->load();

        $section->copyFrom($data);
        $section->save();

        if (! $section->dry()) {
            if (isset($data['image'])) {
                Image::delete_morphs(imgble_id: $section->id, imgble_type: 'featured');

                Image::attach_to(imgble_id: $section->id, imgble_type: 'featured', sizes: ['mb' => 540], files: $data['image'], qnt: 1);
            }
        }
    }
}
