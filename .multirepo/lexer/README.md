<p align="center">
    <img src="https://railt.org/images/logo-dark.svg" width="200" alt="Railt" />
</p>

<p align="center">
    <a href="https://travis-ci.org/railt/lexer"><img src="https://travis-ci.org/railt/lexer.svg?branch=1.4.x" alt="Travis CI" /></a>
    <a href="https://scrutinizer-ci.com/g/railt/lexer/?branch=1.4.x"><img src="https://scrutinizer-ci.com/g/railt/lexer/badges/quality-score.png?b=master" alt="Scrutinizer CI" /></a>
    <a href="https://packagist.org/packages/railt/lexer"><img src="https://poser.pugx.org/railt/lexer/version" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/railt/lexer"><img src="https://poser.pugx.org/railt/lexer/v/unstable" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/railt/lexer/master/LICENSE.md"><img src="https://poser.pugx.org/railt/lexer/license" alt="License MIT"></a>
</p>

# Lexer

> Note: All questions and issues please send 
to [https://github.com/railt/railt/issues](https://github.com/railt/railt/issues)

> Note: Tests can not always pass correctly. This may be due to the inaccessibility of 
PPA servers for updating gcc and g++. The lexertl build requires the support of a modern 
compiler inside Travis CI. In this case, a gray badge will be displayed with the message "Build Error".

In order to quickly understand how it works - just write ~4 lines of code:

```php
$lexer = Railt\Lexer\Factory::create(['T_WHITESPACE' => '\s+', 'T_DIGIT' => '\d+'], ['T_WHITESPACE']);

foreach ($lexer->lex(Railt\Io\File::fromSources('23 42')) as $token) {
    echo $token . "\n";
}
```

This example will read the source text and return the set of tokens from which it is composed:
1) `T_DIGIT` with value "23"
2) `T_DIGIT` with value "42"

The second argument to the `Factory` class is the list of token names that are ignored in the `lex` method result. 
That's why we only got two significant tokens `T_DIGIT`. Although this is not entirely true,
the answer contains a `T_EOI` (End Of Input) token which can also be removed from the output 
by adding an array of the second argument of `Factory` class.

...and now let's try to understand more!

The lexer contains two types of runtime:
1) [`Basic`](#basic) - Set of algorithms with one state.
2) [`Multistate`](#multistate) - Set of algorithms with the possibility of state transition between tokens.

> In connection with the fact that there were almost no differences in 
speed between several implementations (Stateful vs Stateless) of the same algorithm, 
it was decided to abandon the immutable stateful lexers.

```php
use Railt\Lexer\Factory;

/**
 * List of available tokens in format "name => pcre"
 */
$tokens = ['T_DIGIT' => '\d+', 'T_WHITESPACE' => '\s+'];

/**
 * List of skipped tokens
 */
$skip   = ['T_WHITESPACE'];

/**
 * Options:
 *   0 - Nothing.
 *   2 - With PCRE lookahead support.
 *   4 - With multistate support.
 */
$flags = Factory::LOOKAHEAD | Factory::MULTISTATE;

/**
 * Create lexer and tokenize sources. 
 */
$lexer = Factory::create($tokens, $skip, $flags);
```

In order to tokenize the source text, you must use the method `->lex(...)`, which returns 
iterator of the `TokenInterface` objects.

```php
foreach ($lexer->lex(File::fromSources('23 42')) as $token) {
    echo $token . "\n";
}
```

A `TokenInterface` provides a convenient API to obtain information about a token:

```php
interface TokenInterface
{
    public function getName(): string;
    public function getOffset(): int;
    public function getValue(int $group = 0): ?string;
    public function getGroups(): iterable;
    public function getBytes(): int;
    public function getLength(): int;
}
```

## Drivers

The factory returns one of the available implementations, however you can create it yourself.

### Basic

#### NativeRegex

`NativeRegex` implementation is based on the built-in php PCRE functions.

```php
use Railt\Lexer\Driver\NativeRegex;
use Railt\Io\File;

$lexer = new NativeRegex(['T_WHITESPACE' => '\s+', 'T_DIGIT' => '\d+'], ['T_WHITESPACE', 'T_EOI']);

foreach ($lexer->lex(File::fromSources('23 42')) as $token) {
    echo $token->getName() . ' -> ' . $token->getValue() . ' at ' . $token->getOffset() . "\n";
}

// Outputs:
// T_DIGIT -> 23 at 0
// T_DIGIT -> 42 at 3
```

#### Lexertl

Experimental lexer based on the 
[C++ lexertl library](https://github.com/BenHanson/lexertl). To use it, you 
need support for [Parle extension](http://php.net/manual/en/book.parle.php).

```php
use Railt\Lexer\Driver\ParleLexer;
use Railt\Io\File;

$lexer = new ParleLexer(['T_WHITESPACE' => '\s+', 'T_DIGIT' => '\d+'], ['T_WHITESPACE', 'T_EOI']);

foreach ($lexer->lex(File::fromSources('23 42')) as $token) {
    echo $token->getName() . ' -> ' . $token->getValue() . ' at ' . $token->getOffset() . "\n";
}

// Outputs:
// T_DIGIT -> 23 at 0
// T_DIGIT -> 42 at 3
```

> Be careful: The library is not fully compatible with the PCRE regex 
syntax. See the [official documentation](http://www.benhanson.net/lexertl.html).


### Multistate

This functionality is not yet implemented.
