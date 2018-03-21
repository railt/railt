//
// Type definition:
// <code>
//      - Type
//      - Type(...)
//      - Type!
//      - Type(...)!
//      - [Type!]
//      - [Type(...)!]
//      - [Type]!
//      - [Type(...)]!
//      - [Type!]!
//      - [Type(...)!]!
// </code>
//
#ReturnTypeDefinition:
    __returnListDefinition() |
    __returnTypeDefinition()

__returnTypeNonNullModifier:
    <T_NON_NULL>
    #NonNull

__returnListDefinition:
    ::T_BRACKET_OPEN::
        __returnTypeDefinition()
    ::T_BRACKET_CLOSE::
        __returnTypeNonNullModifier()?
    #List

__returnTypeDefinition:
    (
        TypeName() __returnTypeDefinitionArguments()? |
        Variable()
    )
    __returnTypeNonNullModifier()?
    #Type

__returnTypeDefinitionArguments:
    ::T_PARENTHESIS_OPEN::
        ArgumentDefinition()*
    ::T_PARENTHESIS_CLOSE::
    #TypeArguments

