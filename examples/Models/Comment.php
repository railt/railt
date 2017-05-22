<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Example\Models;

/**
 * Class Comment
 * @package Serafim\Railgun\Example\Models
 */
class Comment
{
    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var string
     */
    private $body = '';

    /**
     * Comment constructor.
     * @param string $body
     */
    public function __construct(string $body)
    {
        $this->id = random_int(1, PHP_INT_MAX);

        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}
