<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Example\Types;

use Serafim\Railgun\Schema\Fields;
use Serafim\Railgun\Types\AbstractObjectType;

/**
 * Class UserType
 * @package Serafim\Railgun\Example\Types
 */
class UserType extends AbstractObjectType
{
    /**
     * @param Fields $fields
     * @return iterable
     */
    public function getFields(Fields $fields): iterable
    {
        yield 'id'       => $fields->id();
        yield 'name'     => $fields->string();
        yield 'comments' => $fields->hasMany(CommentType::class);
    }
}
