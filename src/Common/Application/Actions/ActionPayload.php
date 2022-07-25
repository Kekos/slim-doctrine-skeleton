<?php declare(strict_types=1);

namespace App\Common\Application\Actions;

use JsonSerializable;

class ActionPayload implements JsonSerializable
{
    /**
     * @param int $statusCode
     * @param array|object|null $data
     */
    public function __construct(
        private int $statusCode = 200,
        private $data = null,
    )
    {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array|null|object
     */
    public function getData()
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
