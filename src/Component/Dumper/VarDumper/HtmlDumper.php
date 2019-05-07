<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Dumper\VarDumper;

use Symfony\Component\VarDumper\Dumper\HtmlDumper as SymfonyHtmlDumper;

/**
 * Class HtmlDumper
 */
class HtmlDumper extends SymfonyHtmlDumper
{
    /**
     * HtmlDumper constructor.
     *
     * @param null $output
     * @param string|null $charset
     * @param int $flags
     * @throws \InvalidArgumentException
     */
    public function __construct($output = null, string $charset = null, int $flags = 0)
    {
        parent::__construct($output, $charset, $flags);

        static::$themes['railt'] = [
            'default'   => 'background-color:#fff; color:#222; line-height:1.2em; font-weight:normal; font:12px Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:100000',
            'num'       => 'color:#a71d5d',
            'const'     => 'color:#654299',
            'str'       => 'color:#d04e4e',
            'cchr'      => 'color:#222',
            'note'      => 'color:#0d86e7',
            'ref'       => 'color:#a0a0a0',
            'public'    => 'color:#654299',
            'protected' => 'color:#654299',
            'private'   => 'color:#654299',
            'meta'      => 'color:#b729d9',
            'key'       => 'color:#df5000',
            'index'     => 'color:#a71d5d',
        ];

        $this->setTheme('railt');
    }
}
