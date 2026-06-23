<?php

declare(strict_types=1);

namespace Support;

use Base;
use Exception;
use RuntimeException;
use Imagick;

class ImageHandler
{
    private $locked = false;

    public static function make(): static
    {
        return new self();
    }

    public function process(array $files, string $dir, array $sizes)
    {
        $result = [];

        foreach ($files as $file) {
            $filename = $file['name'];
            $upload_dir = $dir . '/' . uniqid();

            $result[] = $this->process_one($filename, $file['tmp_name'], $upload_dir, $sizes);
        }

        return $result;
    }

    private function process_one(string $filename, string $tmp_path, string $upload_dir, array $sizes)
    {
        if ($this->locked) {
            throw new Exception('The image handler is locked, finish processing the current batch first');
        }

        $this->locked = true;

        $dest = $this->move_uploaded_image($filename, $tmp_path, $upload_dir);
        $dest = $this->compress($dest);

        $this->resize_all($sizes, $dest);

        $this->locked = false;

        return $this->normalize_path($dest);
    }

    public function resize_all(array $sizes, string $dest)
    {
        $suffixes = $this->get_size_map($sizes);

        foreach ($suffixes as $suffix => $size) {
            $path = $this->resize_to($size, $dest, $suffix);
            $this->to_webp($path);
            $this->to_avif($path, true);
        }
    }


    public function move_uploaded_image(string $filename, string $tmp_path, string $upload_dir)
    {
        $dest = UPLOAD_DIR . "/{$upload_dir}/";
        $dest = str_replace('//', '/', $dest);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $new_name = sha1_file($tmp_path) . '.' . $ext;

        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        $dest = $dest . $new_name;

        if (!move_uploaded_file($tmp_path, $dest)) {
            throw new RuntimeException('Failed to move uploaded image');
        }

        return $dest;
    }

    public function compress(string $path)
    {
        try {
            $png_path = preg_replace('/\.[^.]+$/', '.png', $path);

            $img = new Imagick($path);
            $img->stripImage();
            $img->setImageFormat('png');
            $img->writeImage($png_path);
            $img->clear();

            if ($png_path !== $path) {
                unlink($path);
            }

            $cmd = sprintf('optipng -o1 -strip all %s 2>&1', escapeshellarg($png_path));

            exec($cmd, output: $output, result_code: $code);

            if ($code !== 0) {
                throw new RuntimeException('PNG compression failed: ' . implode("\n", $output));
            }

            return $png_path;
        } catch (Exception $e) {
            ErrorHandler::handle($e);
        }
    }

    public function to_webp(string $path, bool $cleanup = false)
    {
        $this->convert_to('webp', $path, 75, $cleanup);
    }

    public function to_avif(string $path, bool $cleanup = false)
    {
        $this->convert_to('avif', $path, 50, $cleanup);
    }

    public function resize_to(int $width, string $path, string $suffix)
    {
        try {
            $new_path = substr_replace($path, $suffix . '.', strrpos($path, '.'), 1);
            $img = new Imagick($path);

            $img->resizeImage($width, 0, Imagick::FILTER_LANCZOS, 1);

            $img->writeImage($new_path);
            $img->clear();

            return $new_path;
        } catch (Exception $e) {
            ErrorHandler::handle($e);
        }
    }

    private function convert_to(string $format, string $path, int $quality = 75, bool $cleanup = false)
    {
        if (! in_array($format, ['webp', 'avif', 'png', 'jpg', 'jpeg'])) {
            throw new RuntimeException('Invalid image format in for image conversion: ' . $format);
        }

        try {
            $new_path = preg_replace('/\.[^.]+$/', ".{$format}", $path);

            $img = new Imagick($path);
            $img->setImageFormat($format);
            $img->setImageCompressionQuality($quality);
            $img->writeImage($new_path);
            $img->clear();

            if ($cleanup === true && $new_path !== $path) {
                unlink($path);
            }

            return $new_path;
        } catch (Exception $e) {
            ErrorHandler::handle($e);
        }
    }

    public static function get_size_map(array $base_widths)
    {
        $suffixes = [];

        foreach ($base_widths as $size => $base_w) {
            $entries = explode(', ', build_src_set('', $size, ''));

            foreach ($entries as $entry) {
                [$suffix, $density] = explode('. ', $entry);

                $suffixes[$suffix] = match (true) {
                    str_contains($density, '3') => $base_w * 3,
                    str_contains($density, '2') => $base_w * 2,
                    default                     => $base_w,
                };
            }
        }

        $suffixes['-mb-tiny'] = 30;

        return $suffixes;
    }

    private static function normalize_path(string $path)
    {
        $path = str_replace('//', '/', $path);
        $path = preg_replace('/\.[^.]+$/', '', $path);
        return str_replace(APP_DIR . '/public/', Base::instance()->get('app_url'), $path);
    }
}
