<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Reflection\Contracts\Definition;
use Railt\SDL\Compiler\Stages\Building;
use Railt\SDL\Compiler\Stages\Coercion;
use Railt\SDL\Compiler\Stages\Stage;
use Railt\SDL\Compiler\Stages\Validation;
use Railt\SDL\Compiler\SymbolTable\Record;

/**
 * Class DefinitionBuilder
 */
class Pipeline
{
    public const STAGE_BUILDING = 0x00;
    public const STAGE_COERCION = 0x01;
    public const STAGE_VALIDATION = 0x02;

    /**
     * @var \SplFixedArray|Stage[]
     */
    private $stages;

    /**
     * Pipeline constructor.
     */
    public function __construct()
    {
        $this->stages = \SplFixedArray::fromArray([
            self::STAGE_BUILDING   => new Building(),
            self::STAGE_COERCION   => new Coercion(),
            self::STAGE_VALIDATION => new Validation(),
        ]);
    }

    /**
     * @param Record $record
     * @return Definition
     */
    public function make(Record $record): Definition
    {
        $result = $record;

        foreach ($this->stages as $stage) {
            $result = $stage->resolve($result);
        }

        return $result;
    }
}
