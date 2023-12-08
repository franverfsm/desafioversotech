<?php

namespace app\data;

final class UserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public array $colors,
    ) {}

    public function toArray(): array
    {
        return (array) $this;
    }
}