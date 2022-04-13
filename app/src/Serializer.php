<?php

namespace Ramapriya;

class Serializer implements JsonSerializable
{
    public function __construct(
        private array|object $data,
        private bool $associative = false
    )
    {
    }

    /**
     * @return string|bool
     */
    public function jsonSerialize(): string|bool
    {
        return json_encode($this->data, JSON_UNESCAPED_UNICODE);
    }

    public function jsonUnserialize(string $data): object|array
    {
        return json_decode($data, $this->associative);
    }

    public static function serialize(array|object $data, bool $associative = false): bool|string
    {
        return (new static($data, $associative))->jsonSerialize();
    }

    public static function unserialize(string $data): object|array
    {
        return (new static([]))->jsonUnserialize($data);
    }
}