<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5\Decoder;

use Railt\Parser\Ast\BuilderInterface;

/**
 * Class Parser
 */
class Parser extends BaseParser
{
    /**
     * @var int
     */
    private $options;

    /**
     * @var int
     */
    private $depth;

    /**
     * Parser constructor.
     *
     * @param int $options
     * @param int $depth
     */
    public function __construct(int $options, int $depth)
    {
        $this->depth = $depth;
        $this->options = $options;

        parent::__construct();
    }

    /**
     * @param array $trace
     * @return BuilderInterface
     */
    protected function getBuilder(array $trace): BuilderInterface
    {
        return new Builder($trace, $this->grammar, $this->options);
    }
}
