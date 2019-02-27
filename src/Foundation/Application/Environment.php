<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Application;

/**
 * Class Environment
 */
class Environment implements EnvironmentInterface
{
    /**
     * @var string[]
     */
    protected const TEST_FRAMEWORKS = [
        'phpunit',
    ];

    /**
     * @var string[]
     */
    protected const TEST_ENV = [
        'testing',
        'test',
    ];

    /**
     * @var string[]
     */
    protected const CLI_SAPI = [
        'cli',
        'phpdbg'
    ];

    /**
     * @var string[]
     */
    protected const CLI_ENV = [
        'APP_RUNNING_IN_CONSOLE'
    ];

    /**
     * @return bool
     */
    public function isRunningInTests(): bool
    {
        return $this->fromEnvironment(['RAILT_ENV', 'APP_ENV'], static::TEST_ENV, function () {
            if ($this->isRunningInConsole()) {
                foreach (static::TEST_FRAMEWORKS as $bin) {
                    if (\strpos($_SERVER['argv'][0] ?? '', $bin) !== false) {
                        return true;
                    }
                }
            }

            return false;
        });
    }

    /**
     * @param array|string[] $variables
     * @param array|string[] $values
     * @param \Closure|null $otherwise
     * @return bool|mixed
     */
    protected function fromEnvironment(array $variables, array $values, \Closure $otherwise = null)
    {
        foreach ([$_ENV, $_SERVER] as $context) {
            foreach ($variables as $variable) {
                if (isset($context[$variable])) {
                    return \in_array(\strtolower($context[$variable]), $values, true);
                }
            }
        }

        if ($otherwise === null) {
            return false;
        }

        return $otherwise();
    }

    /**
     * @return bool
     */
    public function isRunningInConsole(): bool
    {
        return $this->fromEnvironment(self::CLI_ENV, ['true'], function() {
            return \in_array(\strtolower(\PHP_SAPI), static::CLI_SAPI, true);
        });
    }
}
