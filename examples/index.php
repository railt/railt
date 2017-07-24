<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Serafim\Railgun\Compiler\Compiler;

require __DIR__ . '/../vendor/autoload.php';

$compiler = new Compiler();
$input = $_POST['input'] ?? 'type Relation {
    id: ID
    value: String
}

type Test {
    id: ID! @testDirective(value: 23)
    some: [Relation]!
}';
?><!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GraphQL IDL parser example</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Mono" rel="stylesheet" />
    <style>
        html, body {
            margin: 0;
            padding: 0;
            font-family: "Roboto", "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        .output,
        .input {
            left: 0;
            top: 0;
            box-sizing: border-box;
            position: absolute;
            width: 50%;
            height: 100%;
            padding: 30px 25px;
            font-size: 14px;
            font-family: "Roboto Mono", 'Consolas', 'Inconsolata', 'Droid Sans Mono', 'Monaco', monospace;
            color: #333;
            font-weight: bold;
            white-space: pre;
            overflow: auto;
            line-height: 18px;
        }

        .output {
            left: 50%;
            font-family: 'Consolas', 'Inconsolata', 'Droid Sans Mono', 'Monaco', monospace;
            color: #333;
        }

        .submit {
            position: absolute;
            top: 10px;
            left: 50%;
            display: block;
            width: 120px;
            padding: 10px 0;
            margin-left: -140px;
            z-index: 9999;
        }

        .keyword {
            color: #AB2525;
            font-weight: bold;
        }
        .char {
            color: #7c59af;
        }
    </style>
</head>
<body>
<form action="./" method="POST">
    <textarea name="input" class="input"><?= $input ?></textarea>
    <output class="output"><?php
        try {
            $definition = $compiler->parse($input);
            echo str_replace(
                [
                    '&gt;',
                    ' ',
                    'token',
                    '(',
                    ')',
                    ',',
                    '#',
                ],
                [
                    '&nbsp;&nbsp;',
                    '&nbsp;',
                    '<span class="keyword">token</span>',
                    '<span class="char">(</span>',
                    '<span class="char">)</span>',
                    '<span class="char">,</span>',
                    '<span class="char">#</span>',
                ],
                htmlspecialchars($definition->dump())
            );
        } catch (Throwable $e) {
            echo $e->getMessage();
        }
        ?></output>
    <input type="submit" value="Построить AST" class="submit"/>
</form>
</body>
</html>
