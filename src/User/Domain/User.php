<?php declare(strict_types=1);

namespace App\User\Domain;

use DateTime;
use JsonSerializable;

class User implements JsonSerializable
{
    private UserId $id;
    private string $username;
    private string $first_name;
    private string $last_name;
    private ?DateTime $created_time = null;

    public function __construct(
        UserId $id,
        string $username,
        string $first_name,
        string $last_name
    )
    {
        $this->id = $id;
        $this->username = $username;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function getCreatedTime(): ?DateTime
    {
        return $this->created_time;
    }

    public function onPrePersist(): void
    {
        $this->created_time = new DateTime();
    }

    public function jsonSerialize()
    {
        return [
            'id' => (string) $this->id,
            'username' => $this->username,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'created_time' => $this->created_time,
        ];
    }
}
