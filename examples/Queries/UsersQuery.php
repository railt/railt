<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Example\Queries;

use Serafim\Railgun\AbstractQuery;
use Serafim\Railgun\Example\Types\UserType;
use Serafim\Railgun\Schema\BelongsTo;
use Serafim\Railgun\Schema\Creators\CreatorInterface;

/**
 * Class UsersQuery
 * @package Serafim\Railgun\Example\Queries
 */
class UsersQuery extends AbstractQuery
{
    /**
     * @param BelongsTo $schema
     * @return CreatorInterface
     */
    public function getType(BelongsTo $schema): CreatorInterface
    {
        return $schema->typeOf(UserType::class);
    }

    /**
     * @param array $arguments
     * @return array
     */
    public function resolve(array $arguments = [])
    {
        return [
            'id'       => random_int(0, PHP_INT_MAX),
            'name'     => random_int(0, PHP_INT_MAX),
            'comments' => [
                'id'   => random_int(0, PHP_INT_MAX),
                'body' => random_int(0, PHP_INT_MAX),
            ],
        ];
    }
}
