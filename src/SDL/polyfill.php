<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


\spl_autoload_register(function (string $alias): void {
    $shouldRewrite = \strpos(\trim($alias, '\\'), 'Railt\\Reflection') === 0;

    if ($shouldRewrite) {
        $original = \str_replace('Railt\\Reflection', 'Railt\\SDL', $alias);

        $error = \sprintf('Class %s is deprecated since Railt 1.2, please use %s instead', $alias, $original);
        @\trigger_error($error, \E_USER_DEPRECATED);

        \class_alias($original, $alias);
    }
}, false, true);
