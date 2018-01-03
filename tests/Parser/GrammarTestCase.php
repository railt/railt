<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Parser;

use Railt\Parser\Grammar;
use Railt\Parser\Io\PhysicalFile;

/**
 * Class GrammarTestCase
 */
class GrammarTestCase extends AbstractParserTestCase
{
    private const EXPECTED_TOKENS = [
        'default'          => [
            'T_NON_NULL'                               => '!',
            'T_VAR'                                    => '\$',
            'T_PARENTHESIS_OPEN'                       => '\(',
            'T_PARENTHESIS_CLOSE'                      => '\)',
            'T_THREE_DOTS'                             => '\.\.\.',
            'T_COLON'                                  => ':',
            'T_EQUAL'                                  => '=',
            'T_DIRECTIVE_AT'                           => '@',
            'T_BRACKET_OPEN'                           => '\[',
            'T_BRACKET_CLOSE'                          => '\]',
            'T_BRACE_OPEN'                             => '{',
            'T_BRACE_CLOSE:default'                    => '}',
            'T_OR'                                     => '\|',
            'T_AND'                                    => '\&',
            'T_ON'                                     => 'on\b',
            'T_NUMBER_VALUE'                           => '\-?(0|[1-9][0-9]*)(\.[0-9]+)?([eE][\+\-]?[0-9]+)?\b',
            'T_BOOL_TRUE'                              => 'true\b',
            'T_BOOL_FALSE'                             => 'false\b',
            'T_NULL'                                   => 'null\b',
            'T_MULTILINE_STRING_OPEN:multiline_string' => '"""',
            'T_STRING_OPEN:string'                     => '"',
            'T_TYPE'                                   => 'type\b',
            'T_TYPE_IMPLEMENTS'                        => 'implements\b',
            'T_ENUM'                                   => 'enum\b',
            'T_UNION'                                  => 'union\b',
            'T_INTERFACE'                              => 'interface\b',
            'T_SCHEMA'                                 => 'schema\b',
            'T_SCHEMA_QUERY'                           => 'query\b',
            'T_SCHEMA_MUTATION'                        => 'mutation\b',
            'T_SCHEMA_SUBSCRIPTION'                    => 'subscription\b',
            'T_SCALAR'                                 => 'scalar\b',
            'T_DIRECTIVE'                              => 'directive\b',
            'T_INPUT'                                  => 'input\b',
            'T_EXTEND'                                 => 'extend\b',
            'T_NAME'                                   => '([_A-Za-z][_0-9A-Za-z]*)',
            'skip'                                     => '(?:(?:[\xfe\xff|\x20|\x09|\x0a|\x0d]+|#[^\n]*)|,)',
        ],
        'multiline_string' => [
            'T_MULTILINE_STRING'               => '(?:\\\\"""|(?!""").|\s)+',
            'T_MULTILINE_STRING_CLOSE:default' => '"""',
        ],
        'string'           => [
            'T_STRING'               => '[^"\\\\]+(\\\\.[^"\\\\]*)*',
            'T_STRING_CLOSE:default' => '"',
        ],
    ];

    private const EXPECTED_RULES = [
        '#Document'                       => ' Definitions()*',
        'Definitions'                     => ' ObjectDefinition() | InterfaceDefinition() | EnumDefinition() | UnionDefinition() | SchemaDefinition() | ScalarDefinition() | InputDefinition() | ExtendDefinition() | DirectiveDefinition()',
        'ValueKeyword'                    => ' <T_BOOL_TRUE> | <T_BOOL_FALSE> | <T_NULL>',
        'Keyword'                         => ' <T_ON> | <T_TYPE> | <T_TYPE_IMPLEMENTS> | <T_ENUM> | <T_UNION> | <T_INTERFACE> | <T_SCHEMA> | <T_SCHEMA_QUERY> | <T_SCHEMA_MUTATION> | <T_SCALAR> | <T_DIRECTIVE> | <T_INPUT> | <T_EXTEND>',
        'Number'                          => ' <T_NUMBER_VALUE>',
        'Nullable'                        => ' <T_NULL>',
        'Boolean'                         => ' <T_BOOL_TRUE> | <T_BOOL_FALSE>',
        'String'                          => ' (::T_MULTILINE_STRING_OPEN:: <T_MULTILINE_STRING> ::T_MULTILINE_STRING_CLOSE::) | (::T_STRING_OPEN:: <T_STRING> ::T_STRING_CLOSE::)',
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
        'Documentation'                   => ' ( ::T_MULTILINE_STRING_OPEN:: <T_MULTILINE_STRING> ::T_MULTILINE_STRING_CLOSE:: ) #Description',
        '#SchemaDefinition'               => ' Documentation()? ::T_SCHEMA:: Directive()* ::T_BRACE_OPEN:: SchemaDefinitionBody() ::T_BRACE_CLOSE::',
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
        'parser.lookahead' => '1024',
        'lexer.unicode'    => true,
    ];

    /**
     * @return void
     */
    public function testTokensParsing(): void
    {
        $file    = $this->getGrammarFile();
        $parser  = new Grammar(PhysicalFile::fromPathname($file));

        $this->assertSame(self::EXPECTED_TOKENS, $parser->getTokens());
    }

    /**
     * @return void
     */
    public function testRulesParsing(): void
    {
        $file    = $this->getGrammarFile();
        $parser  = new Grammar(PhysicalFile::fromPathname($file));

        $this->assertSame(self::EXPECTED_RULES, $parser->getRules());
    }

    /**
     * @return void
     */
    public function testPragmaParsing(): void
    {
        $file    = $this->getGrammarFile();
        $parser  = new Grammar(PhysicalFile::fromPathname($file));

        $this->assertSame(self::EXPECTED_PRAGMA, $parser->getPragma());
    }
}
