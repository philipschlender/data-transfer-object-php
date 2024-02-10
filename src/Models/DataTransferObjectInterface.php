<?php

namespace PhilipSchlender\DataTransferObject\Models;

interface DataTransferObjectInterface
{
    /**
     * @return array<string,mixed>
     */
    public function toArray(): array;

    public function toJson(): string;
}
