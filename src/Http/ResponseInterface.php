<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;

/**
 * Interface ResponseInterface
 */
interface ResponseInterface extends Arrayable, Renderable
{
    /**
     * ResponseInterface constructor.
     * @param array $data
     * @param array $errors
     */
    public function __construct(array $data, array $errors = []);

    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return array[]
     */
    public function getErrors(): array;

    /**
     * @return \Traversable|\Throwable[]
     */
    public function getExceptions(): iterable;

    /**
     * @return void
     */
    public function send(): void;

    /**
     * @return bool
     */
    public function isSuccessful(): bool;

    /**
     * @return bool
     */
    public function hasErrors(): bool;
}
