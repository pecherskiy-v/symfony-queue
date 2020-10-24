<?php
declare(strict_types=1);

namespace App\Requests\Dto;

final class NewEventDto
{
    public int $userId;
    public string $event;
}
