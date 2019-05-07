<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Dumper\Resolver;

/**
 * Class ObjectResolver
 */
class ObjectResolver extends Resolver
{
    /**
     * @var string
     */
    protected const VAR_DUMP_PATTERN = '/^class\h%s#(\d+)\h/isum';

    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return \is_object($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function type($value): string
    {
        return 'object';
    }

    /**
     * @param mixed $object
     * @return string
     */
    public function value($object): string
    {
        $hash = $this->getId($object);

        if ($this->isAnonymous($object)) {
            return 'class@anonymous#' . $hash;
        }

        return \get_class($object) . '#' . $hash;
    }

    /**
     * @param object $object
     * @return bool
     */
    private function isAnonymous($object): bool
    {
        return \preg_match('/^class@anonymous.+/isum', \get_class($object)) > 0;
    }

    /**
     * @param object $object
     * @return int
     */
    private function getObjectIdFromDump($object): int
    {
        \ob_start();
        \var_dump($object);
        $result = \ob_get_clean();

        \preg_match($this->getObjectPattern($object), $result, $matches);

        return (int)($matches[1] ?? 0);
    }

    /**
     * @param object $object
     * @return string
     */
    private function getObjectPattern($object): string
    {
        return \sprintf(self::VAR_DUMP_PATTERN, \preg_quote(\get_class($object), '/'));
    }

    /**
     * @param object $object
     * @return int
     */
    public function getId($object): int
    {
        if (\function_exists('\\spl_object_id')) {
            return \spl_object_id($object);
        }

        return $this->getObjectIdFromDump($object);
    }
}
