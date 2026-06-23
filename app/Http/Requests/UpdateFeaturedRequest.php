<?php

namespace Http\Requests;

use Http\Request;

class UpdateFeaturedRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => [
                'filter'   => 'trim|capitalize',
                'validate' => 'required|min_len:10|max_len:200',
            ],
            'subtitle' => [
                'filter'   => 'trim',
                'validate' => 'required|min_len:10|max_len:500',
            ],
            'body' => [
                'filter'   => 'trim|trim_spaces',
                'validate' => 'required|min_len:10|max_len:2000',
            ],
            'shown' => [
                'filter'   => 'boolean',
                'validate' => 'required|boolean',
            ],
            // 'alt' => [
            //     'filter'   => 'trim',
            //     'validate' => 'required|max_len:250',
            // ],
            'image' => [
                'filter'   => 'file',
                'validate' => 'image:webp,jpg,jpeg,png|max_size:2048',
            ],
        ];
    }

    protected function prepare_data(): array
    {
        return array_merge($this->data, ['alt' => 'example']);
    }

    protected function on_failure(): void
    {
        set_values([
            'title'    => $this->hive->POST['title']              ?? '',
            'subtitle' => $this->hive->POST['subtitle']           ?? '',
            'body'     => $this->hive->POST['body']               ?? '',
            'shown'    => (bool) ($this->hive->POST['shown']      ?? false),
        ]);

        $this->hive->reroute('@featured');
    }
}
