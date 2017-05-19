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

/**
 * Class Article
 * @package Serafim\Railgun\Tests\Types
 */
class Article extends AbstractObjectType
{
    /**
     * @return iterable
     */
    public function getFields(): iterable
    {
        yield 'id' => $this->id();
        yield 'comments' => $this->hasMany(Comment::class);
    }
}
