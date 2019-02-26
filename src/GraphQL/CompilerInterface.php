<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL;

use Railt\Io\Readable;

/**
 * Interface CompilerInterface
 */
interface CompilerInterface
{
    /**
     * @var string
     */
    public const VERSION = '1.3.0';

    /**
     * @param Readable $schema
     * @return mixed
     */
    public function compile(Readable $schema);

    /**
     * @param Readable $schema
     * @return mixed
     */
    public function preload(Readable $schema);

    /**
     * @param TypeLoaderInterface $loader
     * @return void
     */
    public function autoload(TypeLoaderInterface $loader): void;
}
