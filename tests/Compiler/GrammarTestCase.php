<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler;

use Railt\Compiler\Exception\UnexpectedTokenException;
use Railt\Compiler\Exception\UnrecognizedTokenException;
use Railt\Compiler\Grammar\Reader;
use Railt\Io\File;

/**
 * Class GrammarTestCase
 */
class GrammarTestCase extends AbstractParserTestCase
{
    private const EXPECTED_TOKENS = [
        'T_NON_NULL'            => ['!', true],
        'T_VAR'                 => ['\$', true],
        'T_PARENTHESIS_OPEN'    => ['\(', true],
        'T_PARENTHESIS_CLOSE'   => ['\)', true],
        'T_THREE_DOTS'          => ['\.\.\.', true],
        'T_COLON'               => [':', true],
        'T_EQUAL'               => ['=', true],
        'T_DIRECTIVE_AT'        => ['@', true],
        'T_BRACKET_OPEN'        => ['\[', true],
        'T_BRACKET_CLOSE'       => ['\]', true],
        'T_BRACE_OPEN'          => ['{', true],
        'T_BRACE_CLOSE'         => ['}', true],
        'T_OR'                  => ['\|', true],
        'T_AND'                 => ['\&', true],
        'T_ON'                  => ['on\b', true],
        'T_NUMBER_VALUE'        => ['\-?(0|[1-9][0-9]*)(\.[0-9]+)?([eE][\+\-]?[0-9]+)?\b', true],
        'T_BOOL_TRUE'           => ['true\b', true],
        'T_BOOL_FALSE'          => ['false\b', true],
        'T_NULL'                => ['null\b', true],
        'T_MULTILINE_STRING'    => ['"""(?:\\\\"""|(?!""").|\s)+"""', true],
        'T_STRING'              => ['"[^"\\\\]+(\\\\.[^"\\\\]*)*"', true],
        'T_TYPE'                => ['type\b', true],
        'T_TYPE_IMPLEMENTS'     => ['implements\b', true],
        'T_ENUM'                => ['enum\b', true],
        'T_UNION'               => ['union\b', true],
        'T_INTERFACE'           => ['interface\b', true],
        'T_SCHEMA'              => ['schema\b', true],
        'T_SCHEMA_QUERY'        => ['query\b', true],
        'T_SCHEMA_MUTATION'     => ['mutation\b', true],
        'T_SCHEMA_SUBSCRIPTION' => ['subscription\b', true],
        'T_SCALAR'              => ['scalar\b', true],
        'T_DIRECTIVE'           => ['directive\b', true],
        'T_INPUT'               => ['input\b', true],
        'T_EXTEND'              => ['extend\b', true],
        'T_NAME'                => ['([_A-Za-z][_0-9A-Za-z]*)', true],
        'T_WHITESPACE'          => ['[\xfe\xff|\x20|\x09|\x0a|\x0d]+', false],
        'T_COMMENT'             => ['#[^\n]*', false],
        'T_COMMA'               => [',', false],
    ];

    private const EXPECTED_RULES = [
        '#Document'                       => ' Directive()* Definition()*',
        'Definition'                      => ' ObjectDefinition() | InterfaceDefinition() | EnumDefinition() | UnionDefinition() | SchemaDefinition() | ScalarDefinition() | InputDefinition() | ExtendDefinition() | DirectiveDefinition()',
        'ValueKeyword'                    => ' <T_BOOL_TRUE> | <T_BOOL_FALSE> | <T_NULL>',
        'Keyword'                         => ' <T_ON> | <T_TYPE> | <T_TYPE_IMPLEMENTS> | <T_ENUM> | <T_UNION> | <T_INTERFACE> | <T_SCHEMA> | <T_SCHEMA_QUERY> | <T_SCHEMA_MUTATION> | <T_SCALAR> | <T_DIRECTIVE> | <T_INPUT> | <T_EXTEND>',
        'Number'                          => ' <T_NUMBER_VALUE>',
        'Nullable'                        => ' <T_NULL>',
        'Boolean'                         => ' <T_BOOL_TRUE> | <T_BOOL_FALSE>',
        'String'                          => ' <T_MULTILINE_STRING> | <T_STRING>',
        'Word'                            => ' <T_NAME> | ValueKeyword()',
        'Name'                            => ' Word() #Name',
        'Key'                             => ' ( String() | Word() | Keyword() ) #Name',
        'Value'                           => ' ( String() | Number() | Nullable() | Keyword() | Object() | List() | Word() ) #Value',
        'ValueDefinition'                 => ' ValueDefinitionResolver()',
        'ValueDefinitionResolver'         => ' (ValueListDefinition() <T_NON_NULL>? #List) | (ValueScalarDefinition() <T_NON_NULL>? #Type)',
        'ValueListDefinition'             => ' ::T_BRACKET_OPEN:: (ValueScalarDefinition() <T_NON_NULL>? #Type) ::T_BRACKET_CLOSE::',
        'ValueScalarDefinition'           => ' Keyword() | Word()',
        'Object'                          => ' ::T_BRACE_OPEN:: ObjectPair()* ::T_BRACE_CLOSE:: #Object',
        'ObjectPair'                      => ' Key() ::T_COLON:: Value() #ObjectPair',
        'List'                            => ' ::T_BRACKET_OPEN:: Value()* ::T_BRACKET_CLOSE:: #List',
        'Documentation'                   => ' <T_MULTILINE_STRING> #Description',
        '#SchemaDefinition'               => ' Documentation()? <T_SCHEMA> Name()? Directive()* ::T_BRACE_OPEN:: SchemaDefinitionBody() ::T_BRACE_CLOSE::',
        'SchemaDefinitionBody'            => ' ( SchemaDefinitionQuery() | SchemaDefinitionMutation() | SchemaDefinitionSubscription() )*',
        'SchemaDefinitionQuery'           => ' Documentation()? ::T_SCHEMA_QUERY:: ::T_COLON:: SchemaDefinitionFieldValue() #Query',
        'SchemaDefinitionMutation'        => ' Documentation()? ::T_SCHEMA_MUTATION:: ::T_COLON:: SchemaDefinitionFieldValue() #Mutation',
        'SchemaDefinitionSubscription'    => ' Documentation()? ::T_SCHEMA_SUBSCRIPTION:: ::T_COLON:: SchemaDefinitionFieldValue() #Subscription',
        'SchemaDefinitionFieldValue'      => ' ValueDefinition() Directive()*',
        '#ScalarDefinition'               => ' Documentation()? ::T_SCALAR:: Name() Directive()*',
        '#InputDefinition'                => ' Documentation()? ::T_INPUT:: Name() Directive()* ::T_BRACE_OPEN:: InputDefinitionField()* ::T_BRACE_CLOSE::',
        'InputDefinitionField'            => ' Documentation()? ( Key() ::T_COLON:: ValueDefinition() InputDefinitionDefaultValue()? Directive()* ) #Argument',
        'InputDefinitionDefaultValue'     => ' ::T_EQUAL:: Value()',
        '#ExtendDefinition'               => ' Documentation()? ::T_EXTEND:: ( ObjectDefinition() | InterfaceDefinition() | EnumDefinition() | UnionDefinition() | SchemaDefinition() | ScalarDefinition() | InputDefinition() | DirectiveDefinition() )',
        '#DirectiveDefinition'            => ' Documentation()? ::T_DIRECTIVE:: ::T_DIRECTIVE_AT:: Name() DirectiveDefinitionArguments()* ::T_ON:: DirectiveDefinitionTargets()+',
        'DirectiveDefinitionArguments'    => ' ::T_PARENTHESIS_OPEN:: DirectiveDefinitionArgument()* ::T_PARENTHESIS_CLOSE::',
        'DirectiveDefinitionArgument'     => ' Documentation()? Key() ::T_COLON:: ValueDefinition() DirectiveDefinitionDefaultValue()? Directive()* #Argument',
        'DirectiveDefinitionTargets'      => ' Key() (::T_OR:: Key())* #Target',
        'DirectiveDefinitionDefaultValue' => ' ::T_EQUAL:: Value()',
        '#ObjectDefinition'               => ' Documentation()? ::T_TYPE:: Name() ObjectDefinitionImplements()? Directive()* ::T_BRACE_OPEN:: ObjectDefinitionField()* ::T_BRACE_CLOSE::',
        'ObjectDefinitionImplements'      => ' ::T_TYPE_IMPLEMENTS:: Key()* ( ::T_AND:: Key() )? #Implements',
        'ObjectDefinitionField'           => ' Documentation()? ( Key() Arguments()? ::T_COLON:: ObjectDefinitionFieldValue() ) #Field',
        'ObjectDefinitionFieldValue'      => ' ValueDefinition() Directive()*',
        '#InterfaceDefinition'            => ' Documentation()? ::T_INTERFACE:: Name() Directive()* ::T_BRACE_OPEN:: InterfaceDefinitionBody()* ::T_BRACE_CLOSE::',
        'InterfaceDefinitionBody'         => ' ( InterfaceDefinitionFieldKey() ::T_COLON:: ValueDefinition() Directive()* ) #Field',
        'InterfaceDefinitionFieldKey'     => ' Documentation()? Key() Arguments()?',
        '#EnumDefinition'                 => ' Documentation()? ::T_ENUM:: Name() Directive()* ::T_BRACE_OPEN:: EnumField()* ::T_BRACE_CLOSE::',
        'EnumField'                       => ' Documentation()? ( EnumValue() Directive()* ) #Value',
        'EnumValue'                       => ' ( <T_NAME> | Keyword() ) #Name',
        '#UnionDefinition'                => ' Documentation()? ::T_UNION:: Name() Directive()* ::T_EQUAL:: UnionBody()',
        'UnionBody'                       => ' ::T_OR::? UnionUnitesList()+ #Relations',
        'UnionUnitesList'                 => ' Name() (::T_OR:: Name())*',
        'Arguments'                       => ' ::T_PARENTHESIS_OPEN:: ArgumentPair()* ::T_PARENTHESIS_CLOSE::',
        'ArgumentPair'                    => ' Documentation()? Key() ::T_COLON:: ValueDefinition() ArgumentDefaultValue()? Directive()* #Argument',
        'ArgumentValue'                   => ' ValueDefinition() #Type',
        'ArgumentDefaultValue'            => ' ::T_EQUAL:: Value()',
        '#Directive'                      => ' ::T_DIRECTIVE_AT:: Name() DirectiveArguments()?',
        'DirectiveArguments'              => ' ::T_PARENTHESIS_OPEN:: DirectiveArgumentPair()* ::T_PARENTHESIS_CLOSE::',
        'DirectiveArgumentPair'           => ' Key() ::T_COLON:: Value() #Argument',
    ];

    private const EXPECTED_PRAGMA = [
        'lexer.unicode'            => true,
        'parser.lookahead'         => 1024,
        'error.unexpected_token'   => UnexpectedTokenException::class,
        'error.unrecognized_token' => UnrecognizedTokenException::class,
    ];

    /**
     * @return void
     */
    public function testTokensParsing(): void
    {
        $file   = $this->getGrammarFile();
        $parser = new Reader(File::fromPathname($file));

        $this->assertSame(self::EXPECTED_TOKENS, $parser->getTokens());
    }

    /**
     * @return void
     */
    public function testRulesParsing(): void
    {
        $file   = $this->getGrammarFile();
        $parser = new Reader(File::fromPathname($file));

        $this->assertSame(self::EXPECTED_RULES, $parser->getRules());
    }

    /**
     * @return void
     */
    public function testPragmaParsing(): void
    {
        $file   = $this->getGrammarFile();
        $parser = new Reader(File::fromPathname($file));

        $this->assertSame(self::EXPECTED_PRAGMA, $parser->getPragmas());
    }
}
