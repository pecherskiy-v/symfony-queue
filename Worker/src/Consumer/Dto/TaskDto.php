<?php

declare(strict_types=1);

namespace App\Consumer\Dto;

final class TaskDto
{
    public $userId;
    public $message;
    public $timeshtamp;

    public function __construct(?string $toObject = null)
    {
        if (!empty($toObject)) {
            foreach (json_decode($toObject, true) as $name => $value) {
                if (property_exists($this, $name)) {
                    $this->$name = $value;
                }
            }
        }
    }
}
