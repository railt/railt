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

%skip T_IGNORE                 [\xfe\xff|\x20|\x09|\x0a|\x0d]+
%skip T_COMMENT                (#.*)

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
//  A GraphQL object type has a name and fields, but at some point
//  those fields have to resolve to some concrete data.
//  That's where the scalar types come in:
//  they represent the leaves of the query.
//
//  @see http://graphql.org/learn/schema/#scalar-types
//      - Int:      http://facebook.github.io/graphql/#sec-Int
//      - Float:    http://facebook.github.io/graphql/#sec-Float
//      - String:   http://facebook.github.io/graphql/#sec-String
//      - Boolean:  http://facebook.github.io/graphql/#sec-Boolean
//      - ID:       http://facebook.github.io/graphql/#sec-ID
//

%token T_SCALAR_INTEGER         Int\b
%token T_SCALAR_FLOAT           Float\b
%token T_SCALAR_STRING          String\b
%token T_SCALAR_BOOLEAN         Boolean\b
%token T_SCALAR_ID              ID\b

//
// --------------------------------------------------------------------------
//  GraphQL Scalar Values
// --------------------------------------------------------------------------
//
//  Field and directive arguments accept input values of various literal
//  primitives; input values can be scalars, enumeration values, lists,
//  or input objects.
//
//  If not defined as constant (for example, in DefaultValue), input values
//  can be specified as a variable. List and inputs objects may also contain
//  variables (unless defined to be constant).
//
//  @see http://facebook.github.io/graphql/#sec-Input-Values
//

%token T_NUMBER_VALUE           \-?(0|[1-9][0-9]*)(\.[0-9]+)?([eE][\+\-]?[0-9]+)?
%token T_BOOL_TRUE              true
%token T_BOOL_FALSE             false
%token T_NULL                   null

%token T_QUOTE_OPEN             "   -> string
%token string:T_STRING          [^"\\]+(\\.[^"\\]*)*
%token string:T_QUOTE_CLOSE     "   -> default

//
// --------------------------------------------------------------------------
//  GraphQL Keywords
// --------------------------------------------------------------------------
//
// @see http://facebook.github.io/graphql/#sec-Type-System
//

%token T_TYPE                   type
%token T_ENUM                   enum
%token T_UNION                  union
%token T_INTERFACE              interface
%token T_IMPLEMENTS             implements

%token T_NAME                   ([_A-Za-z][_0-9A-Za-z]*)


//
// --------------------------------------------------------------------------
//  GraphQL Document Definition
// --------------------------------------------------------------------------
//

#Document:
    Definitions()*

Definitions:
    Type() | Interface() | Enum() | Union()



//
// --------------------------------------------------------------------------
//  GraphQL Common Structures
// --------------------------------------------------------------------------
//

Scalar:
    <T_SCALAR_INTEGER>
        |
    <T_SCALAR_FLOAT>
        |
    <T_SCALAR_STRING>
        |
    <T_SCALAR_BOOLEAN>
        |
    <T_SCALAR_ID>

Keyword:
    <T_BOOL_TRUE>
        |
    <T_BOOL_FALSE>
        |
    <T_NULL>
        |
    <T_TYPE>
        |
    <T_ENUM>
        |
    <T_UNION>
        |
    <T_INTERFACE>
        |
    <T_IMPLEMENTS>

Number:
    <T_NUMBER_VALUE>

Nullable:
    <T_NULL>

Boolean:
    <T_BOOL_TRUE> | <T_BOOL_FALSE>

String:
    ::T_QUOTE_OPEN:: <T_STRING> ::T_QUOTE_CLOSE::

Relation:
    <T_NAME>

Key:
    (
        Scalar()
            |
        Keyword()
            |
        Relation()
    ) #Name

Value:
    (
        String()
            |
        Number()
            |
        Nullable()
            |
        Keyword()
            |
        Scalar()
            |
        Relation()
            |
        Object()
    ) #Value

Object:
    ::T_BRACE_OPEN:: Pair()* ::T_COMMA::? ::T_BRACE_CLOSE::

Pair:
    Key() ::T_COLON:: Value()



//
// --------------------------------------------------------------------------
//  GraphQL Directives
// --------------------------------------------------------------------------
//
//  A schema file follows the SDL syntax and can contain additional static
//  and temporary GraphQL directives.
//
//  Static directives describe additional information about types or fields
//  in the GraphQL schema.
//
//  @see http://facebook.github.io/graphql/#sec-Language.Directives
//  @see https://www.graph.cool/docs/reference/schema/directives-aeph6oyeez/
//

#Directive:
    ::T_DIRECTIVE_AT:: DirectiveName() DirectiveArguments()?

DirectiveName:
    Key()

DirectiveArguments:
    ::T_PARENTHESIS_OPEN:: DirectiveArgument()* ::T_PARENTHESIS_CLOSE::
    #Arguments

DirectiveArgument:
    DirectivePair() (::T_COMMA:: DirectivePair())*

DirectivePair:
    Pair() #Pair

DirectiveArgumentName:
    Key()

DirectiveValue:
    (
        Value()
        DirectiveObjectValue()
    ) #Value

DirectiveObjectValue:
    ::T_BRACE_OPEN:: DirectiveArgument()* ::T_BRACE_CLOSE::

DirectiveStringValue:
    ::T_QUOTE_OPEN:: <T_STRING> ::T_QUOTE_CLOSE::



//
// --------------------------------------------------------------------------
// GraphQL Object (Type) Definitions
// --------------------------------------------------------------------------
//
//  GraphQL queries are hierarchical and composed, describing a tree
//  of information. While Scalar types describe the leaf values of
//  these hierarchical queries, Objects describe the intermediate levels.
//
//  GraphQL Objects represent a list of named fields, each of which yield
//  a value of a specific type. Object values should be serialized as
//  ordered maps, where the queried field names (or aliases) are the keys
//  and the result of evaluating the field is the value, ordered by the
//  order in which they appear in the query.
//
//  @see https://github.com/facebook/graphql/pull/90
//  @see https://www.graph.cool/docs/reference/schema/types-ij2choozae/
//  @see http://facebook.github.io/graphql/#sec-Objects
//

#Type:
    ::T_TYPE:: Key() TypeImplements()? Directive()* ::T_BRACE_OPEN:: TypeField()* ::T_BRACE_CLOSE::

TypeImplements:
    ::T_IMPLEMENTS:: (Key() ::T_COMMA::? #Interface)+

TypeField:
    (TypeFieldKey() ::T_COLON:: TypeFieldValue() Directive()* ::T_COMMA::?) #Field

TypeFieldKey:
    Key()

TypeFieldValue:
    (TypeFieldListValue() <T_NON_NULL>? #List)   |
    (TypeFieldScalar() <T_NON_NULL>?    #Scalar)

TypeFieldListValue:
    ::T_BRACKET_OPEN::
        (TypeFieldScalar() <T_NON_NULL>? #Scalar)
    ::T_BRACKET_CLOSE::

TypeFieldScalar:
    Keyword()
        |
    Scalar()
        |
    <T_NAME>



//
// --------------------------------------------------------------------------
//  GraphQL Interface Definitions
// --------------------------------------------------------------------------
//
//  GraphQL Interfaces represent a list of named fields and their arguments.
//  GraphQL objects can then implement an interface,
//  which guarantees that they will contain the specified fields.
//
//  Fields on a GraphQL interface have the same rules as fields on a
//  GraphQL object; their type can be Scalar, Object, Enum, Interface,
//  or Union, or any wrapping type whose base type is one of those five.
//
//  @see http://facebook.github.io/graphql/#sec-Interfaces
//  @see https://www.graph.cool/docs/faq/graphql-sdl-schema-definition-language-kr84dktnp0/#interface
//

#Interface:
    ::T_INTERFACE:: Key() ::T_BRACE_OPEN:: InterfaceBody() ::T_BRACE_CLOSE::

InterfaceBody:
    InterfaceField()*

InterfaceField:
    (InterfaceFieldKey() ::T_COLON:: InterfaceFieldValue() ::T_COMMA::?) #Field

InterfaceFieldKey:
    Key()

InterfaceFieldValue:
    (InterfaceFieldListValue() <T_NON_NULL>? #List)
        |
    (InterfaceFieldScalar() <T_NON_NULL>?    #Scalar)

InterfaceFieldListValue:
    ::T_BRACKET_OPEN::
        (InterfaceFieldScalar() <T_NON_NULL>? #Scalar)
    ::T_BRACKET_CLOSE::

InterfaceFieldScalar:
    Keyword()
        |
    Scalar()
        |
    <T_NAME>



//
// --------------------------------------------------------------------------
//  GraphQL Enum Definitions
// --------------------------------------------------------------------------
//
//  GraphQL Enums are a variant on the Scalar type, which represents one
//  of a finite set of possible values.
//
//  GraphQL Enums are not references for a numeric value, but are unique
//  values in their own right. They serialize as a string: the name
//  of the represented value.
//
//  @see http://facebook.github.io/graphql/#sec-Enums
//  @see https://www.graph.cool/docs/faq/graphql-sdl-schema-definition-language-kr84dktnp0/?r#enum
//

#Enum:
    ::T_ENUM:: Key() ::T_BRACE_OPEN:: EnumBody() ::T_BRACE_CLOSE::

EnumBody:
    EnumField()+ #Values

EnumField:
    Key() ::T_COMMA::?



//
// --------------------------------------------------------------------------
//  GraphQL Union Definitions
// --------------------------------------------------------------------------
//
//  GraphQL Unions represent an object that could be one of a list of
//  GraphQL Object types, but provides for no guaranteed fields between
//  those types. They also differ from interfaces in that Object types
//  declare what interfaces they implement, but are not aware of
//  what unions contain them.
//
//  With interfaces and objects, only those fields defined on the type can
//  be queried directly; to query other fields on an interface, typed
//  fragments must be used. This is the same as for unions, but unions
//  do not define any fields, so no fields may be queried on this
//  type without the use of typed fragments.
//
//  @see http://facebook.github.io/graphql/#sec-Unions
//

#Union:
    ::T_UNION:: Key() ::T_EQUAL:: UnionBody()

UnionBody:
    UnionUnites()+ #Relations

UnionUnites:
    Key() (::T_OR:: Key())*
