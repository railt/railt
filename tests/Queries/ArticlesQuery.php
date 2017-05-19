<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Queries;

use Serafim\Railgun\Tests\Types\Article;
use Serafim\Railgun\Types\Definitions\FieldDefinition;

/**
 * Class ArticlesQuery
 * @package Serafim\Railgun\Tests\Queries
 */
class ArticlesQuery extends FieldDefinition
{
    /**
     * ArticlesQuery constructor.
     */
    public function __construct()
    {
        parent::__construct(Article::class);

        $this->many()->then(function () {
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
        });
    }
}
