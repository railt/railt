<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Queries;

use Serafim\Railgun\AbstractQuery;
use Serafim\Railgun\Tests\Types\Article;
use Serafim\Railgun\Contracts\TypeDefinitionInterface;

/**
 * Class ArticlesQuery
 * @package Serafim\Railgun\Tests\Queries
 */
class ArticlesQuery extends AbstractQuery
{
    /**
     * @return TypeDefinitionInterface
     */
    public function getType(): TypeDefinitionInterface
    {
        return $this->listOf(Article::class);
    }

    /**
     * @param $value
     * @param array $arguments
     * @return array
     */
    public function resolve($value, array $arguments = [])
    {
        return [
            [
                'id'       => 23,
                'comments' => [
                    ['id' => 1, 'content' => 'first content'],
                    ['id' => 2, 'content' => 'second content'],
                    ['id' => 3, 'content' => 'third content'],
                ],
            ],
        ];
    }
}
