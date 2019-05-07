<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Discovery\Parser;

/**
 * Class Parser
 */
class Parser implements ParserInterface
{
    /**
     * @var string
     */
    protected const REPLACEMENT_PATTERN = '/\$\{([a-z0-9_\-\.]+)\}/ium';

    /**
     * @var array
     */
    private $variables = [];

    /**
     * @param mixed $data
     * @return mixed
     */
    public function bypass($data)
    {
        switch (true) {
            case \is_string($data):
                $data = $this->replace($data);
                break;

            case \is_iterable($data):
                $data = \array_map(function ($value) {
                    return $this->bypass($value);
                }, $data);
                break;
        }

        return $data;
    }

    /**
     * @param string $data
     * @return string
     */
    private function replace(string $data): string
    {
        $callback = \Closure::fromCallable([$this, 'replaceFound']);

        return \preg_replace_callback(self::REPLACEMENT_PATTERN, $callback, $data);
    }

    /**
     * @param iterable $variables
     * @return ParserInterface
     */
    public function defineAll(iterable $variables): ParserInterface
    {
        foreach ($variables as $name => $value) {
            $this->define($name, $value);
        }

        return $this;
    }

    /**
     * @param string $variable
     * @param mixed $value
     * @return ParserInterface
     */
    public function define(string $variable, $value): ParserInterface
    {
        $this->variables[$variable] = $value;

        return $this;
    }

    /**
     * @param array $item
     * @return mixed|null
     * @throws \InvalidArgumentException
     */
    private function replaceFound(array $item)
    {
        [$body, $variable] = [$item[0], \trim($item[1])];

        $result = $this->variables[$variable] ?? null;

        if ($result instanceof \Closure) {
            return $result();
        }

        if ($result !== null) {
            return $result;
        }

        $error = 'Unrecognized variable ${%s}';
        throw new \InvalidArgumentException(\sprintf($error, $variable));
    }
}
