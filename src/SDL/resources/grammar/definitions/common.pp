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
TypeDefinition:
    __listTypeDefinition() |
    __typeDefinition()

__listTypeDefinition:
    (
        ::T_BRACKET_OPEN::
            __typeDefinition()
        ::T_BRACKET_CLOSE::
    ) <T_NON_NULL>?
    #TypeList

__typeDefinition:
    (
        TypeName() __typeArguments()? |
        Variable()
    ) <T_NON_NULL>?
    #Type

//
// ( key: val )
//
__typeArguments:
    ::T_PARENTHESIS_OPEN::
        ArgumentDefinition()*
    ::T_PARENTHESIS_CLOSE::
    #TypeArguments

ArgumentDefinition:
    Documentation()?
    NameWithReserved() ::T_COLON:: TypeDefinition()
    DefaultValue()?
    #TypeArgument

//
// Generic arguments
//
GenericArguments:
    ::T_PARENTHESIS_OPEN::
        __genericArgument()*
    ::T_PARENTHESIS_CLOSE::
    #GenericArguments

__genericArgument:
    Variable() ::T_COLON:: TypeName()
    #GenericArgument


//
// Documentation
//

Documentation:
    String() #Description
