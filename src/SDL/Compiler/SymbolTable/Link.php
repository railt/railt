<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\SymbolTable;
use Railt\Io\Readable;

/**
 * Class Link
 */
class Link
{
    /**
     * @var Readable
     */
    private $from;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $module;

    /**
     * Link constructor.
     * @param Readable $from
     * @param string $type
     * @param string $module
     */
    public function __construct(Readable $from, string $type, string $module)
    {
        $this->from = $from;
        $this->type = $type;
        $this->module = $module;
    }
}
