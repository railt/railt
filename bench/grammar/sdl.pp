
/**
 * --------------------------------------------------------------------------
 *              GraphQL Schema Definition Language (SDL) Grammar
 * --------------------------------------------------------------------------
 *
 * This file is part of Railt package and provides PP2 Language for
 * GraphQL SDL. A full description of the basic grammar can be found on
 * this page: https://hoa-project.net/En/Literature/Hack/Compiler.html
 * However, some features may vary.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://github.com/facebook/graphql/pull/90
 * @see https://www.graph.cool/docs/faq/graphql-sdl-schema-definition-language-kr84dktnp0
 */

%pragma lexer.unicode true
%pragma lexer.multiline false
%pragma parser.entry Document
%pragma parser.lookahead 30

/**
 * Set of tokens for lexical text analysis.
 */
%include lexer.pp

/**
 * Auxiliary grammatical structures.
 */
%include common/names.pp
%include common/values.pp
%include common/definitions.pp
%include common/invocations.pp


#Document:
    NamespaceDefinition()?
    DocumentImports()*
    DocumentDefinitions()*

DocumentImports
    : ImportDefinition()
    | Invocation()

DocumentDefinitions
    : Definition()

/*
Extension
    : ScalarExtension()
    | ObjectExtension()
    | InterfaceExtension()
    | UnionExtension()
    | EnumExtension()
    | InputExtension()
*/
