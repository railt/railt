<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Railt\Parser\Rule\Alternation;
use Railt\Parser\Rule\Concatenation;
use Railt\Parser\Rule\Repetition;
use Railt\Parser\Rule\Terminal;

$result = [];

foreach ($this->getGrammar()->getRules() as $rule) {
    $args = [];

    switch (true) {
        case $rule instanceof Terminal:
            $args = [
                $this->render($rule->getTokenName()),
                $this->render($rule->isKept()),
            ];
            break;

        case $rule instanceof Alternation:
            $args = [
                $this->render($rule->getChildren()),
                $this->render($rule->getNodeId()),
            ];
            break;

        case $rule instanceof Repetition:
            $args = [
                $this->render($rule->getMin()),
                $this->render($rule->getMax()),
                $this->render($rule->getChildren()),
                $this->render($rule->getNodeId()),
            ];
            break;

        case $rule instanceof Concatenation:
            $args = [
                $this->render($rule->getChildren()),
                $this->render($rule->getNodeId()),
            ];
            break;
    }

    $params = [
        \class_basename($rule),
        $this->render($rule->getName()),
        \implode(', ', $args),
    ];

    $result[] = $rule->getDefaultId() === null
        ? \vsprintf('new %s(%s, %s)', $params)
        : \vsprintf('(new %s(%s, %s))->setDefaultId(%s)', \array_merge($params, [
            $this->render($rule->getDefaultId()),
        ]));
}

return $result;
