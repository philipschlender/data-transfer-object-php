<?php

namespace PhilipSchlender\DataTransferObject\Tests;

use PhilipSchlender\DataTransferObject\Exceptions\DataTransferObjectException;
use PhilipSchlender\DataTransferObject\Models\DataTransferObjectInterface;
use PhilipSchlender\Faker\Services\FakerService;

class DataTransferObjectTest extends TestCase
{
    /**
     * @param array<string,mixed> $expectedData
     *
     * @dataProvider dataProviderConstruct
     */
    public function testConstruct(array $expectedData): void
    {
        $childDataTransferObject = new ChildDataTransferObject($expectedData);

        $data = $childDataTransferObject->toArray();

        $this->assertIsArray($data);
        $this->assertEquals($expectedData, $data);
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public static function dataProviderConstruct(): array
    {
        $fakerService = new FakerService();

        return [
            [
                'expectedData' => [],
            ],
            [
                'expectedData' => [
                    'string' => $fakerService->randomString(),
                ],
            ],
        ];
    }

    /**
     * @param array<string,mixed> $expectedData
     *
     * @dataProvider dataProviderToArray
     */
    public function testToArray(array $expectedData, ParentDataTransferObject $parentDataTransferObject): void
    {
        $data = $parentDataTransferObject->toArray();

        $this->assertIsArray($data);
        $this->assertEquals($expectedData, $data);
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public static function dataProviderToArray(): array
    {
        $fakerService = new FakerService();

        $string1 = $fakerService->randomString();
        $string2 = $fakerService->randomString();
        $string3 = $fakerService->randomString();
        $string4 = $fakerService->randomString();

        $childDataTransferObject1 = new ChildDataTransferObject();
        $childDataTransferObject1->setString($string1);

        $childDataTransferObject2 = new ChildDataTransferObject();
        $childDataTransferObject2->setString($string2);

        $childDataTransferObject3 = new ChildDataTransferObject();
        $childDataTransferObject3->setString($string3);

        $parentDataTransferObject1 = new ParentDataTransferObject();
        $parentDataTransferObject1->setString($string4);
        $parentDataTransferObject1->setChildDataTransferObject($childDataTransferObject1);
        $parentDataTransferObject1->addChildDataTransferObject('2', $childDataTransferObject2);
        $parentDataTransferObject1->addChildDataTransferObject('3', $childDataTransferObject3);

        $expectedData = [
            'childDataTransferObject' => [
                'string' => $string1,
            ],
            'childDataTransferObjects' => [
                '2' => [
                    'string' => $string2,
                ],
                '3' => [
                    'string' => $string3,
                ],
            ],
            'string' => $string4,
        ];

        $parentDataTransferObject2 = new ParentDataTransferObject($expectedData);

        return [
            [
                'expectedData' => $expectedData,
                'parentDataTransferObject' => $parentDataTransferObject1,
            ],
            [
                'expectedData' => $expectedData,
                'parentDataTransferObject' => $parentDataTransferObject2,
            ],
        ];
    }

    /**
     * @dataProvider dataProviderToJson
     */
    public function testToJson(string $expectedJson, ParentDataTransferObject $parentDataTransferObject): void
    {
        $json = $parentDataTransferObject->toJson();

        $this->assertIsString($json);
        $this->assertEquals($expectedJson, $json);
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public static function dataProviderToJson(): array
    {
        $fakerService = new FakerService();

        $string1 = $fakerService->randomString();
        $string2 = $fakerService->randomString();
        $string3 = $fakerService->randomString();
        $string4 = $fakerService->randomString();

        $childDataTransferObject1 = new ChildDataTransferObject();
        $childDataTransferObject1->setString($string1);

        $childDataTransferObject2 = new ChildDataTransferObject();
        $childDataTransferObject2->setString($string2);

        $childDataTransferObject3 = new ChildDataTransferObject();
        $childDataTransferObject3->setString($string3);

        $parentDataTransferObject1 = new ParentDataTransferObject();
        $parentDataTransferObject1->setString($string4);
        $parentDataTransferObject1->setChildDataTransferObject($childDataTransferObject1);
        $parentDataTransferObject1->addChildDataTransferObject('2', $childDataTransferObject2);
        $parentDataTransferObject1->addChildDataTransferObject('3', $childDataTransferObject3);

        $expectedData = [
            'childDataTransferObject' => [
                'string' => $string1,
            ],
            'childDataTransferObjects' => [
                '2' => [
                    'string' => $string2,
                ],
                '3' => [
                    'string' => $string3,
                ],
            ],
            'string' => $string4,
        ];
        $expectedJson = json_encode($expectedData);

        $parentDataTransferObject2 = new ParentDataTransferObject($expectedData);

        return [
            [
                'expectedJson' => $expectedJson,
                'parentDataTransferObject' => $parentDataTransferObject1,
            ],
            [
                'expectedJson' => $expectedJson,
                'parentDataTransferObject' => $parentDataTransferObject2,
            ],
        ];
    }

    /**
     * @dataProvider dataProviderGetObject
     */
    public function testGetObject(ChildDataTransferObject $expectedChildDataTransferObject, ParentDataTransferObject $parentDataTransferObject): void
    {
        $childDataTransferObject = $parentDataTransferObject->getChildDataTransferObject();

        $this->assertInstanceOf(DataTransferObjectInterface::class, $childDataTransferObject);
        $this->assertEquals($expectedChildDataTransferObject, $childDataTransferObject);
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public static function dataProviderGetObject(): array
    {
        $childDataTransferObject1 = new ChildDataTransferObject();

        $parentDataTransferObject1 = new ParentDataTransferObject();
        $parentDataTransferObject1->setChildDataTransferObject($childDataTransferObject1);

        $expectedData = [
            'childDataTransferObject' => [],
        ];

        $parentDataTransferObject2 = new ParentDataTransferObject($expectedData);

        return [
            [
                'expectedChildDataTransferObject' => $childDataTransferObject1,
                'parentDataTransferObject' => $parentDataTransferObject1,
            ],
            [
                'expectedChildDataTransferObject' => $childDataTransferObject1,
                'parentDataTransferObject' => $parentDataTransferObject2,
            ],
        ];
    }

    public function testGetObjectPropertyIsRequired(): void
    {
        $this->expectException(DataTransferObjectException::class);
        $this->expectExceptionMessage('The property \'childDataTransferObject\' is required.');

        $parentDataTransferObject = new ParentDataTransferObject();

        $parentDataTransferObject->getChildDataTransferObject();
    }

    public function testGetObjectPropertyIsOptional(): void
    {
        $parentDataTransferObject = new ParentDataTransferObject();

        $childDataTransferObject = $parentDataTransferObject->getChildDataTransferObjectOptional();

        $this->assertNull($childDataTransferObject);
    }

    public function testGetObjectArgumentMustImplementDataTransferObjectInterface(): void
    {
        $this->expectException(DataTransferObjectException::class);
        $this->expectExceptionMessage('The argument \'class\' must implement \'PhilipSchlender\DataTransferObject\Models\DataTransferObjectInterface\'.');

        $data = [
            'stdClass' => [],
        ];

        $parentDataTransferObject = new ParentDataTransferObject($data);

        $parentDataTransferObject->getChildDataTransferObjectStdClass();
    }

    /**
     * @param array<string,ChildDataTransferObject> $expectedChildDataTransferObjects
     *
     * @dataProvider dataProviderGetObjects
     */
    public function testGetObjects(array $expectedChildDataTransferObjects, ParentDataTransferObject $parentDataTransferObject): void
    {
        $childDataTransferObjects = $parentDataTransferObject->getChildDataTransferObjects();

        $this->assertIsArray($childDataTransferObjects);

        foreach ($childDataTransferObjects as $identifier => $childDataTransferObject) {
            $this->assertInstanceOf(DataTransferObjectInterface::class, $childDataTransferObject);
            $this->assertEquals($expectedChildDataTransferObjects[$identifier], $childDataTransferObject);
        }
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public static function dataProviderGetObjects(): array
    {
        $childDataTransferObject1 = new ChildDataTransferObject();
        $childDataTransferObject2 = new ChildDataTransferObject();

        /** @var array<string,ChildDataTransferObject> $childDataTransferObjects */
        $childDataTransferObjects = [
            '1' => $childDataTransferObject1,
            '2' => $childDataTransferObject2,
        ];

        $parentDataTransferObject1 = new ParentDataTransferObject();
        $parentDataTransferObject1->setChildDataTransferObjects($childDataTransferObjects);

        $expectedData = [
            'childDataTransferObjects' => [
                '1' => [],
                '2' => [],
            ],
        ];

        $parentDataTransferObject2 = new ParentDataTransferObject($expectedData);

        return [
            [
                'expectedChildDataTransferObjects' => $childDataTransferObjects,
                'parentDataTransferObject' => $parentDataTransferObject1,
            ],
            [
                'expectedChildDataTransferObjects' => $childDataTransferObjects,
                'parentDataTransferObject' => $parentDataTransferObject2,
            ],
        ];
    }

    public function testGetObjectsPropertyIsRequired(): void
    {
        $this->expectException(DataTransferObjectException::class);
        $this->expectExceptionMessage('The property \'childDataTransferObjects\' is required.');

        $parentDataTransferObject = new ParentDataTransferObject();

        $parentDataTransferObject->getChildDataTransferObjects();
    }

    public function testGetObjectsPropertyIsOptional(): void
    {
        $parentDataTransferObject = new ParentDataTransferObject();

        $childDataTransferObjects = $parentDataTransferObject->getChildDataTransferObjectsOptional();

        $this->assertNull($childDataTransferObjects);
    }

    public function testGetObjectsArgumentMustImplementDataTransferObjectInterface(): void
    {
        $this->expectException(DataTransferObjectException::class);
        $this->expectExceptionMessage('The argument \'class\' must implement \'PhilipSchlender\DataTransferObject\Models\DataTransferObjectInterface\'.');

        $data = [
            'stdClass' => [],
        ];

        $parentDataTransferObject = new ParentDataTransferObject($data);

        $parentDataTransferObject->getChildDataTransferObjectsStdClasses();
    }
}
