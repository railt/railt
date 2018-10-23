<p align="center">
    <img src="https://railt.org/images/logo-dark.svg" width="200" alt="Railt" />
</p>

<p align="center">
    <a href="https://travis-ci.org/railt/compiler"><img src="https://travis-ci.org/railt/compiler.svg?branch=master" alt="Travis CI" /></a>
    <a href="https://scrutinizer-ci.com/g/railt/compiler/?branch=master"><img src="https://scrutinizer-ci.com/g/railt/compiler/badges/coverage.png?b=master" alt="Code coverage" /></a>
    <a href="https://scrutinizer-ci.com/g/railt/compiler/?branch=master"><img src="https://scrutinizer-ci.com/g/railt/compiler/badges/quality-score.png?b=master" alt="Scrutinizer CI" /></a>
    <a href="https://packagist.org/packages/railt/compiler"><img src="https://poser.pugx.org/railt/compiler/version" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/railt/compiler"><img src="https://poser.pugx.org/railt/compiler/v/unstable" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/railt/compiler/master/LICENSE.md"><img src="https://poser.pugx.org/railt/compiler/license" alt="License MIT"></a>
</p>

# Compiler

This is almost a completely rewritten implementation 
of [Hoa\Compiler](https://github.com/hoaproject/Compiler).

The documentation and the way of using it can be found on the 
[pages of the Hoa Community](https://hoa-project.net/En/Literature/Hack/Compiler.html). 

In contrast to the original implementation, this implementation 
contains several differences.

> Note: All questions and issues please send 
to [https://github.com/railt/railt/issues](https://github.com/railt/railt/issues)

## Lexer

The lexer contains two types of runtime:
1) [`Stateless`](#stateless) - Set of algorithms for starting from scratch.
2) [`Statful`](#statful) - Set of algorithms for run the compiled sources.

### Stateless

#### Native

Native implementation is based on the built-in php PCRE functions and faster 
than the original Hoa [more than **140 times**](https://github.com/hoaproject/Compiler/issues/81).

```php
use Railt\Compiler\Lexer\NativeStateless;
use Railt\Io\File;

$lexer = new NativeStateless();
$lexer->add('T_WHITESPACE', '\s+')->skip('T_WHITESPACE');
$lexer->add('T_DIGIT', '\d+');

foreach ($lexer->lex(File::fromSources('23 42')) as $token) {
    echo $token->name() . ' -> ' . $token->value() . ' at ' . $token->offset() . "\n";
}

// Outputs:
// T_DIGIT -> 23 at 0
// T_DIGIT -> 42 at 3
```

#### Lexertl

Experimental lexer based on the 
[C++ lexertl library](https://github.com/BenHanson/lexertl). To use it, you 
need support for [Parle extension](http://php.net/manual/en/book.parle.php).
Note that this implementation is 1.5 to 3 times **slower** than the native 
PHP implementation.

```php
use Railt\Compiler\Lexer\ParleStateless;
use Railt\Io\File;

$lexer = new ParleStateless();
$lexer->add('T_WHITESPACE', '\s+')->skip('T_WHITESPACE');
$lexer->add('T_DIGIT', '\d+');

foreach ($lexer->lex(File::fromSources('23 42')) as $token) {
    echo $token->name() . ' -> ' . $token->value() . ' at ' . $token->offset() . "\n";
}

// Outputs:
// T_DIGIT -> 23 at 0
// T_DIGIT -> 42 at 3
```

> Be careful: The library is not fully compatible with the PCRE regex 
syntax. See the [official documentation](http://www.benhanson.net/lexertl.html).

### Stateful

### Native

Native implementation is based on the built-in php PCRE functions.

```php
use Railt\Compiler\Lexer\Common\PCRECompiler;
use Railt\Compiler\Lexer\NativeStateful;
use Railt\Io\File;

$compiler = new PCRECompiler();
$compiler->addToken('T_WHITESPACE', '\s+');
$compiler->addToken('T_DIGIT', '\d+');

$lexer = new NativeStateful($compiler->compile(), ['T_WHITESPACE']);
//                          ^ Compiled PCRE       ^ Skipped tokens

foreach ($lexer->lex(File::fromSources('23 42')) as $token) {
    echo $token->name() . ' -> ' . $token->value() . ' at ' . $token->offset() . "\n";
}

// Outputs:
// T_DIGIT -> 23 at 0
// T_DIGIT -> 42 at 3
```

## Parser

The parser is based on the [LL(k) algorithm](https://en.wikipedia.org/wiki/LL_parser) 
and almost does not differ from the Hoa realization.

### From Grammar File

```php
use Railt\Compiler\Parser;
use Railt\Io\File;

$parser = Parser::fromGrammar(File::fromPathname(__DIR__ . '/grammar.pp'));

/** @var \Railt\Compiler\Parser\Ast\NodeInterface $ast */
$ast = $parser->parse(File::fromPathname(__DIR__ . '/sources.txt'));

echo $ast; 
```

## Grammar compilation

In order to collect the grammar files into the executable code, 
you need to install:

- `zendframework/zend-code: ~3.0` 
- `twig/twig: ~2.0`

```php
use Railt\Compiler\Grammar\Reader;
use Railt\Compiler\Generator\LexerGenerator;
use Railt\Compiler\Generator\ParserGenerator;
use Railt\Io\File;

/** @var \Railt\Compiler\Grammar\ParsingResult $reader */
$reader = (new Reader)->read(File::fromPathname(__DIR__ . '/grammar.pp'));

(new LexerGenerator($reader->getLexer()))
    ->class('GeneratedLexer') // Output class name
    ->build()
    ->saveTo(__DIR__); // Output directory
    
(new ParserGenerator($reader))
    ->class('GeneratedParser')
    ->build()
    ->saveTo(__DIR__);
``` 

## Differences from Hoa

### Removed

- Railt grammar does not support the namespaces

### Added

- Added directive `%include file/name` for connecting external files.
- Comments can be specified anywhere in the grammar, not just at the beginning of the lines.
- Added support for multi-line comments like `/* comment text here */`
- Added full unicode support
- Added the ability to specify the root production rule: `%pragma root RootRuleName`
- Added an `offset` support of AST leafs and non-terminal rules 
- Added a dump of AST nodes: `echo $ast`
- Added support of lexeme groups

## Grammar

The PP2 language, based on Hoa PP language, standing for PHP Parser, 
allows to express algebraic grammars.

A grammar is composed of lexemes and rules. 
The declaration of a lexeme has the following syntax: 
`%token name value`, where name represents the name of the lexeme, 
value represents its value, expressed with the PCRE format 
(take care to not match an empty value, in which case an exception will be thrown). 
For example number describing a number composed of digits from 0 to 9:

```antlrv4
%token T_NUMBER \d+
```

A `%skip` declaration is similar to `%token` excepts that it represents a 
lexeme to skip, it means to not consider. 
A common example of `%skip` lexemes are spaces:

```antlrv4
%skip T_WHITESPACE \s+
```

To explain rules, we will use the JSON grammar as an example, which is a 
softly simplified version of the JSON language (please, see the RFC4627):

```antlrv4
/**
 * --------------------------------------------------------------------------
 *  JSON RFC4627 Grammar
 * --------------------------------------------------------------------------
 *
 * This file provides JSON language grammar
 * @see https://www.ietf.org/rfc/rfc4627.txt
 */

%pragma root Object
%pragma lexer.unicode true
%pragma parser.lookahead 1024

/**
 * The declaration of a lexemes
 */

// Scalars
%token T_TRUE           true\b
%token T_FALSE          false\b
%token T_NULL           null\b

// Strings
%token T_STRING         "[^"\\]+(\\.[^"\\]*)*"

// Objects
%token T_BRACE_OPEN     {
%token T_BRACE_CLOSE    }

// Arrays
%token T_BRACKET_OPEN   \[
%token T_BRACKET_CLOSE  \]

// Rest
%token  T_COLON          :
%token  T_COMMA          ,
%token  T_NUMBER         \d+

// Skipped tokens
%skip   T_WHITESPACE     \s+

/**
 * The declaration of a productions
 */

Value
    : <T_TRUE>
    | <T_FALSE>
    | <T_NULL>
    | String()
    | Object()
    | Array()
    | Number()

String
    : <T_STRING>

Number
    : <T_NUMBER>

#Object
    : ::T_BRACE_OPEN:: Pair() ( ::T_COMMA:: Pair() )* ::T_BRACE_CLOSE::

#Pair
    : String() ::T_COLON:: Value()

#Array
    : ::T_BRACKET_OPEN:: Value() (::T_COMMA:: Value())* ::T_BRACKET_CLOSE::
```

- `%token NAME PCRE` to create a token definition
- `%skip NAME PCRE` to create a token definition which will not be taken into account during parsing
- `%pragma name value` specifies parsing and lexical analysis settings
- `%include file` includes an external file inside the grammar
- `#Rule:` to create a rule in the resulting AST
- `Rule:` to create a rule without specifying in the resulting AST
- `Rule()` to call a rule from production
- `<TOKEN>` to call a lexeme from production
- `::TOKEN::` to call a lexeme from production without specifying the terminal in the resulting AST
- `|` for a disjunction (a choice)
- `(…)` for a group
- `e?` to say that e is optional
- `e+` to say that e can be present 1 or more times
- `e*` to say that e can be present 0 or more times
- `e{x,y}` to say that e can be present between x and y times (\*`x` and `y` are optional)

Few constructions but largely enough.
