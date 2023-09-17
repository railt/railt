<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command;

use Railt\SDL\Node\Statement\Statement;

/**
 * @template TStatementNode of Statement
 *
 * @template-extends DefineCommand<TStatementNode>
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
abstract class ExtendCommand extends DefineCommand implements ExtendCommandInterface {}
