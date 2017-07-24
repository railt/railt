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

%skip  T_IGNORE                 [\xfeff\x20\x09\x0a\x0d]+
%skip  T_COMMENT                #.*

//
// --------------------------------------------------------------------------
// GraphQL Punctuators
// --------------------------------------------------------------------------
//
//  GraphQL documents include punctuation in order to describe structure.
//  GraphQL is a data description language and not a programming language,
//  therefore GraphQL lacks the punctuation often used to describe
//  mathematical expressions.
//
//  @see http://facebook.github.io/graphql/#sec-Punctuators
//

%token T_NON_NULL               !
%token T_VAR                    \$
%token T_PARENTHESIS_OPEN       \(
%token T_PARENTHESIS_CLOSE      \)
%token T_THREE_DOTS             \.\.\.
%token T_COLON                  :
%token T_EQUAL                  =
%token T_DIRECTIVE_AT           @
%token T_BRACKET_OPEN           \[
%token T_BRACKET_CLOSE          \]
%token T_BRACE_OPEN             {
%token T_BRACE_CLOSE            }
%token T_OR                     \|

%token T_COMMA                  ,

//
// --------------------------------------------------------------------------
//  GraphQL Scalar Types
// --------------------------------------------------------------------------
//
// A GraphQL object type has a name and fields, but at some point
// those fields have to resolve to some concrete data.
// That's where the scalar types come in:
// they represent the leaves of the query.
//
// @see http://graphql.org/learn/schema/#scalar-types
//  - Int:      http://facebook.github.io/graphql/#sec-Int
//  - Float:    http://facebook.github.io/graphql/#sec-Float
//  - String:   http://facebook.github.io/graphql/#sec-String
//  - Boolean:  http://facebook.github.io/graphql/#sec-Boolean
//  - ID:       http://facebook.github.io/graphql/#sec-ID
//

%token T_SCALAR_INTEGER         Int
%token T_SCALAR_FLOAT           Float
%token T_SCALAR_STRING          String
%token T_SCALAR_BOOLEAN         Boolean
%token T_SCALAR_ID              ID

%token T_NUMBER                 \-?(0|[1-9][0-9]*)(\.[0-9]+)?([eE][\+\-]?[0-9]+)?
%token T_BOOL_TRUE              true
%token T_BOOL_FALSE             false
%token T_NULL                   null

// String definition
%token  T_QUOTE_OPEN            "       -> T_STRING
%token  T_STRING:T_STRING       [^"]+
%token  T_STRING:T_QUOTE_CLOSE  "       -> default

// Keywords

%token T_SCHEMA                 schema
%token T_TYPE                   type
%token T_ENUM                   enum
%token T_UNION                  union
%token T_INTERFACE              interface
%token T_IMPLEMENTS             implements
%token T_NAME                   ([_A-Za-z][_0-9A-Za-z]*)


#Document:
    TypeDefinition()*

#TypeDefinition:
    ::T_TYPE:: <T_NAME> Implements()? ::T_BRACE_OPEN:: Field()* ::T_BRACE_CLOSE::

Implements:
    ::T_IMPLEMENTS:: Interface()+

#Interface:
    <T_NAME> ::T_COMMA::?

#Directive:
    ::T_DIRECTIVE_AT:: <T_NAME> DirectiveArguments()?

DirectiveArguments:
    ::T_PARENTHESIS_OPEN:: DirectiveArgument()* ::T_PARENTHESIS_CLOSE::

#DirectiveArgument:
    (<T_NAME> | StringValue()) ::T_COLON:: Value() ::T_COMMA::?

Value:
    <T_NUMBER>      |
    <T_BOOL_TRUE>   |
    <T_BOOL_FALSE>  |
    <T_NULL>        |
    <T_NAME>        |
    StringValue()   |
    ObjectValue()

ObjectValue:
    ::T_BRACE_OPEN:: DirectiveArgument()* ::T_BRACE_CLOSE::

StringValue:
    ::T_QUOTE_OPEN:: <T_STRING> ::T_QUOTE_CLOSE::


#Field:
    <T_NAME> ::T_COLON:: FieldValue() Directive()* ::T_COMMA::?

#FieldValue:
    FieldSingleValue() <T_NON_NULL>? |
    FieldListValue() <T_NON_NULL>?

FieldListValue:
    <T_BRACKET_OPEN>
        FieldSingleValue() <T_NON_NULL>?
    <T_BRACKET_CLOSE>

FieldSingleValue:
    <T_SCALAR_INTEGER>  |
    <T_SCALAR_FLOAT>    |
    <T_SCALAR_STRING>   |
    <T_SCALAR_BOOLEAN>  |
    <T_SCALAR_ID>       |
    <T_NAME>


