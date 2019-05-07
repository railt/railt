<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Schema;

use Phplrt\Io\Readable;
use Railt\SDL\Contracts\Document;

/**
 * Class CompilerInterface
 */
interface CompilerInterface
{
    /**
     * @param Readable $readable
     * @return Document
     */
    public function compile(Readable $readable): Document;

    /**
     * @param Document $document
     * @return CompilerInterface
     */
    public function add(Document $document): self;

    /**
     * @param \Closure $then
     * @return CompilerInterface
     */
    public function autoload(\Closure $then): self;
}
