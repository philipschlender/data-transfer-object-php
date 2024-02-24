<?php

namespace PhilipSchlender\DataTransferObject\Tests;

use PhilipSchlender\DataTransferObject\Models\DataTransferObject;

class ParentDataTransferObject extends DataTransferObject
{
    public function getString(): string
    {
        $this->checkRequired('string');

        return $this->data['string'];
    }

    public function getStringOptional(): ?string
    {
        return $this->data['stringOptional'] ?? null;
    }

    public function setString(string $string): static
    {
        $this->data['string'] = $string;

        return $this;
    }

    public function getChildDataTransferObject(): ChildDataTransferObject
    {
        $this->checkRequired('childDataTransferObject');

        /** @var ChildDataTransferObject $childDataTransferObject */
        $childDataTransferObject = $this->getObject('childDataTransferObject', ChildDataTransferObject::class);

        return $childDataTransferObject;
    }

    public function getChildDataTransferObjectOptional(): ?ChildDataTransferObject
    {
        /** @var ?ChildDataTransferObject $childDataTransferObject */
        $childDataTransferObject = $this->getObject('childDataTransferObject', ChildDataTransferObject::class);

        return $childDataTransferObject;
    }

    public function getChildDataTransferObjectStdClass(): ?\stdClass
    {
        /** @var ?\stdClass $stdClass */
        $stdClass = $this->getObject('stdClass', \stdClass::class);

        return $stdClass;
    }

    public function setChildDataTransferObject(ChildDataTransferObject $childDataTransferObject): static
    {
        $this->data['childDataTransferObject'] = $childDataTransferObject;

        return $this;
    }

    /**
     * @return array<string,ChildDataTransferObject>
     */
    public function getChildDataTransferObjects(): array
    {
        $this->checkRequired('childDataTransferObjects');

        /** @var array<string,ChildDataTransferObject> $childDataTransferObjects */
        $childDataTransferObjects = $this->getObjects('childDataTransferObjects', ChildDataTransferObject::class);

        return $childDataTransferObjects;
    }

    /**
     * @return array<string,ChildDataTransferObject>|null
     */
    public function getChildDataTransferObjectsOptional(): ?array
    {
        /** @var ?array<string,ChildDataTransferObject> $childDataTransferObjects */
        $childDataTransferObjects = $this->getObjects('childDataTransferObjects', ChildDataTransferObject::class);

        return $childDataTransferObjects;
    }

    /**
     * @return array<string,\stdClass>|null
     */
    public function getChildDataTransferObjectsStdClasses(): ?array
    {
        /** @var ?array<string,\stdClass> $stdClasses */
        $stdClasses = $this->getObjects('stdClass', \stdClass::class);

        return $stdClasses;
    }

    /**
     * @param array<string,ChildDataTransferObject> $childDataTransferObjects
     */
    public function setChildDataTransferObjects(array $childDataTransferObjects): static
    {
        $this->data['childDataTransferObjects'] = $childDataTransferObjects;

        return $this;
    }

    public function addChildDataTransferObject(string $identifier, ChildDataTransferObject $childDataTransferObject): static
    {
        $this->data['childDataTransferObjects'][$identifier] = $childDataTransferObject;

        return $this;
    }
}
