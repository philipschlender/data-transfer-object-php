<?php

namespace PhilipSchlender\DataTransferObject\Tests;

use PhilipSchlender\DataTransferObject\Models\DataTransferObject;

class ChildDataTransferObject extends DataTransferObject
{
    public function getString(): string
    {
        $this->checkRequired('string');

        return $this->data['string'];
    }

    public function setString(string $string): static
    {
        $this->data['string'] = $string;

        return $this;
    }

    public function getStringOptional(): ?string
    {
        $this->checkRequired('string');

        return $this->data['string'] ?? null;
    }
}
