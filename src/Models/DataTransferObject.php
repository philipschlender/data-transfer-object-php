<?php

namespace PhilipSchlender\DataTransferObject\Models;

use PhilipSchlender\DataTransferObject\Exceptions\DataTransferObjectException;

abstract class DataTransferObject implements DataTransferObjectInterface
{
    /**
     * @var array<string,mixed>
     */
    protected array $data;

    /**
     * @param array<string,mixed> $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $data = [];

        foreach ($this->data as $identifier => $value) {
            if ($value instanceof DataTransferObjectInterface) {
                $data[$identifier] = $value->toArray();
            } elseif (is_array($value)) {
                foreach ($value as $identifier2 => $value2) {
                    if ($value2 instanceof DataTransferObjectInterface) {
                        $data[$identifier][$identifier2] = $value2->toArray();
                    } else {
                        $data[$identifier][$identifier2] = $value2;
                    }
                }
            } else {
                $data[$identifier] = $value;
            }

            ksort($data);
        }

        return $data;
    }

    /**
     * @throws DataTransferObjectException
     */
    public function toJson(): string
    {
        try {
            return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new DataTransferObjectException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @throws DataTransferObjectException
     */
    protected function checkRequired(string $identifier): void
    {
        if (!isset($this->data[$identifier])) {
            throw new DataTransferObjectException(sprintf('The property \'%s\' is required.', $identifier));
        }
    }

    /**
     * @throws DataTransferObjectException
     */
    protected function getObject(string $identifier, string $class): ?object
    {
        if (!isset($this->data[$identifier])) {
            return null;
        }

        if (!class_exists($class) || !in_array(DataTransferObjectInterface::class, class_implements($class))) {
            throw new DataTransferObjectException(sprintf('The argument \'class\' must implement \'%s\'.', DataTransferObjectInterface::class));
        }

        if (!$this->data[$identifier] instanceof DataTransferObjectInterface) {
            $object = new $class($this->data[$identifier]);

            $this->data[$identifier] = $object;
        }

        return $this->data[$identifier];
    }

    /**
     * @return array<string|object>|null
     *
     * @throws DataTransferObjectException
     */
    protected function getObjects(string $identifier, string $class): ?array
    {
        if (!isset($this->data[$identifier]) || !is_array($this->data[$identifier])) {
            return null;
        }

        if (!class_exists($class) || !in_array(DataTransferObjectInterface::class, class_implements($class))) {
            throw new DataTransferObjectException(sprintf('The argument \'class\' must implement \'%s\'.', DataTransferObjectInterface::class));
        }

        foreach ($this->data[$identifier] as $identifier2 => $data) {
            if (!$data instanceof DataTransferObjectInterface) {
                $object = new $class($data);

                $this->data[$identifier][$identifier2] = $object;
            }
        }

        return $this->data[$identifier];
    }
}
