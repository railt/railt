<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Response;

/**
 * Interface Renderable
 */
interface Renderable extends \JsonSerializable
{
    /**
     * @param int|null $options
     * @return string
     */
    public function render(int $options = null): string;

    /**
     * @param int $options
     * @return Renderable
     */
    public function withJsonOptions(int $options): self;

    /**
     * @param int $options
     * @return Renderable
     */
    public function setJsonOptions(int $options): self;

    /**
     * @return int
     */
    public function getJsonOptions(): int;

    /**
     * @return void
     */
    public function send(): void;

    /**
     * An alias of render() method.
     *
     * @return string
     */
    public function __toString(): string;
}
