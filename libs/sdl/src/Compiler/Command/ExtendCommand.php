<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command;

use Railt\SDL\Node\Statement\Statement;

/**
 * @template TStatementNode of Statement
 *
 * @template-extends DefineCommand<TStatementNode>
 */
abstract class ExtendCommand extends DefineCommand implements ExtendCommandInterface
{
}
