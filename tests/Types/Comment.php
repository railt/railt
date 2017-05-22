<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Types;

use Serafim\Railgun\Types\AbstractObjectType;
use Serafim\Railgun\Types\Schemas\Fields;

/**
 * Class Comment
 * @package Serafim\Railgun\Tests\Types
 */
class Comment extends AbstractObjectType
{
    /**
     * @param Fields $field
     * @return iterable
     */
    public function getFields(Fields $field): iterable
    {
        yield 'id' => $field->id();
        yield 'content' => $field->string();
    }
}
