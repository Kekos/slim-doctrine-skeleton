<?php declare(strict_types=1);

namespace App\Application\Actions;

use JsonSerializable;

class ActionPayload implements JsonSerializable
{
    private int $statusCode;
    /** @var array|object|null */
    private $data;

    /**
     * @param int $statusCode
     * @param array|object|null $data
     */
    public function __construct(
        int $statusCode = 200,
        $data = null
    ) {
        $this->statusCode = $statusCode;
        $this->data = $data;
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
