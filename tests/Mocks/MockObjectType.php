<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Mocks;

use Serafim\Railgun\Schema\Fields;
use Serafim\Railgun\Support\InteractWithName;
use Serafim\Railgun\Types\ObjectTypeInterface;
use Serafim\Railgun\Schema\Creators\FieldDefinitionCreator;

/**
 * Class MockObjectType
 * @package Serafim\Railgun\Tests\Mocks
 */
class MockObjectType implements ObjectTypeInterface
{
    use InteractWithName;

    /**
     * @param Fields $fields
     * @return iterable|FieldDefinitionCreator[]
     */
    public function getFields(Fields $fields): iterable
    {
        yield $fields->typeOf('id')
            ->named('id')
            ->means('This is identifier of mock object');

        yield 'some' => $fields->string()->deprecated('This field is deprecated', '1.0.0');
    }

    /**
     * @return iterable
     */
    public function getInterfaces(): iterable
    {
        return [];
    }
}
