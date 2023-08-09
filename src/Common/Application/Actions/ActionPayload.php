<?php declare(strict_types=1);

namespace App\Common\Application\Actions;

use JsonSerializable;

class ActionPayload implements JsonSerializable
{
    public function __construct(
        private readonly int $statusCode = 200,
        private readonly object|array|null $data = null,
    )
    {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getData(): object|array|null
    {
        return $this->data;
    }

    public function jsonSerialize(): array
    {
        $payload = [
            'statusCode' => $this->statusCode,
        ];

        if ($this->data !== null) {
            $payload['data'] = $this->data;
        }

        return $payload;
    }
}
