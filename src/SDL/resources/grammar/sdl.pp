%pragma root Document
%pragma lexer.unicode true
%pragma parser.lookahead 6

//
// --------------------------------------------------------------------------
//  GraphQL Schema Definition Language (SDL) Grammar
// --------------------------------------------------------------------------
//
// This file provides PP language grammar for GraphQL SDL
//
// @see https://github.com/facebook/graphql/pull/90
// @see https://www.graph.cool/docs/faq/graphql-sdl-schema-definition-language-kr84dktnp0
//

//
// ==========================================================================
//                                  TOKENS
// ==========================================================================
//
// --------------------------------------------------------------------------
// GraphQL Punctuators and Keywords
// --------------------------------------------------------------------------
//
//  GraphQL documents include punctuation in order to describe structure.
//  GraphQL is a data description language and not a programming language,
//  therefore GraphQL lacks the punctuation often used to describe
//  mathematical expressions.
//
//  @see http://facebook.github.io/graphql/#sec-Punctuators
//

%token T_PARENTHESIS_OPEN       \(
%token T_PARENTHESIS_CLOSE      \)
%token T_BRACKET_OPEN           \[
%token T_BRACKET_CLOSE          \]
%token T_BRACE_OPEN             {
%token T_BRACE_CLOSE            }
%token T_NON_NULL               !
%token T_THREE_DOTS             \.\.\.
%token T_COLON                  :
%token T_EQUAL                  =
%token T_DIRECTIVE_AT           @
%token T_AND                    \&
%token T_OR                     \|

//
// Values
//
%token T_NUMBER                 \-?(0|[1-9][0-9]*)(\.[0-9]+)?([eE][\+\-]?[0-9]+)?\b
%token T_TRUE                   true\b
%token T_FALSE                  false\b
%token T_NULL                   null\b
%token T_BLOCK_STRING           """(?:\\"""|(?!""").|\s)+"""
%token T_STRING                 "[^"\\]+(\\.[^"\\]*)*"

//
// Module system
//
%token T_NAMESPACE              namespace\b
%token T_NAMESPACE_SEPARATOR    /
%token T_IMPORT                 import\b
%token T_IMPORT_FROM            from\b

//
// Common
//
%token T_EXTENDS                extends\b
%token T_IMPLEMENTS             implements\b
%token T_ON                     on\b

//
// Type definitions
//
%token T_TYPE                   type\b
%token T_ENUM                   enum\b
%token T_UNION                  union\b
%token T_INPUT_UNION            inputUnion\b
%token T_INTERFACE              interface\b
%token T_SCHEMA                 schema\b
%token T_SCALAR                 scalar\b
%token T_DIRECTIVE              directive\b
%token T_INPUT                  input\b
%token T_EXTEND                 extend\b
%token T_FRAGMENT               fragment\b

// Common
%token T_VARIABLE               \$[_A-Za-z][_0-9A-Za-z]*
%token T_NAME                   [_A-Za-z][_0-9A-Za-z]*

//
// --------------------------------------------------------------------------
//  GraphQL Ignored Tokens
// --------------------------------------------------------------------------
//
//  Before and after every lexical token may be any amount of ignored
//  tokens including WhiteSpace and Comment. No ignored regions of a source
//  document are significant, however ignored source characters may appear
//  within a lexical token in a significant way, for example a String may
//  contain white space characters.
//
//  No characters are ignored while parsing a given token, as an example no white
//  space characters are permitted between the characters defining a FloatValue.
//
//  @see http://facebook.github.io/graphql/#sec-Source-Text.Ignored-Tokens
//  @see http://facebook.github.io/graphql/#sec-Appendix-Grammar-Summary.Ignored-Tokens
//
//
//                             [ BOM | WHITESPACE | HTAB | LF | CR ]
%skip T_WHITESPACE             [\xfe\xff|\x20|\x09|\x0a|\x0d]+
%skip T_COMMENT                #[^\n]*
%skip T_COMMA                  ,

//
// Includes
//
%include names.pp
%include values.pp
%include definitions.pp

// ==========================================================================
//                                  DOCUMENT
// ==========================================================================

#Document:
    NamespaceDefinition()?
    ImportDefinition()*
    // Directive()*
    DocumentBody()*

DocumentBody:
    // Extension()  |
    Definition()

Definition:
    SchemaDefinition()     |
    ScalarDefinition()     |
    ObjectDefinition()     |
    InterfaceDefinition()  |
    EnumDefinition()       |
    UnionDefinition()      |
    InputUnionDefinition() |
    DirectiveDefinition()
    // TODO InputDefinition

// Extension:
    // TODO ScalarExtension
    // TODO ObjectExtension
    // TODO InterfaceExtension
    // TODO UnionExtension
    // TODO EnumExtension
    // TODO InputExtension

// Directive:
    // TODO Directive (invocation)
