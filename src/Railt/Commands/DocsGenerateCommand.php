<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Commands;

/**
 * Class DevelopmentServerCommand
 * @package Railt\Commands
 */
class DocsGenerateCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $name = 'docs:generate';

    /**
     * @var string
     */
    protected $description = 'Generate a new API Documentation.';

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function handle(): void
    {
        $root = $this->projectRoot();
        $cmd = 'cd "%s" && php "%s/vendor/sami/sami/sami.php" update ./resources/docs.config.php';
        $cmd = sprintf($cmd, $root, $root);

        echo shell_exec($cmd);
    }
}
