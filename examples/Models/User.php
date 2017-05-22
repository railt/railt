<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Example\Models;

use Illuminate\Support\Collection;

/**
 * Class User
 * @package Serafim\Railgun\Example\Models
 */
class User
{
    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var Collection|Comment[]
     */
    private $comments;

    /**
     * User constructor.
     * @param string $name
     * @param string $email
     */
    public function __construct(string $name, string $email = 'example@email.com')
    {
        $this->id = random_int(1, PHP_INT_MAX);

        $this->comments = new Collection();

        $this->name = $name;
        $this->email = $email;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment)
    {
        $this->comments->push($comment);

        return $this;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }
}
