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
 * @package Railt\Http
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
     * @return array
     */
    public function getData(): array;

    /**
     * @return iterable|array[]
     */
    public function getErrors(): iterable;

    /**
     * @return void
     */
    public function send(): void;
}
