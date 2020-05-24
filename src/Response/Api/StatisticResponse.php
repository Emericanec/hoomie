<?php

declare(strict_types=1);

namespace App\Response\Api;

class StatisticResponse implements ApiResponseInterface
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this->data as $data)
        {
            if (isset($data['index'])) {
                $result[$data['index']] = [
                    'label' => $data['label'],
                    'count' => $data['count'],
                ];
            }
        }
        return $result;
    }
}
