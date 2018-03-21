#FieldDefinition:
        Documentation()?
    NameWithReserved()
        __fieldDefinitionArguments()?
    ::T_COLON:: ReturnTypeDefinition()
        Directive()*

__fieldDefinitionArguments:
    ::T_PARENTHESIS_OPEN::
        __fieldDefinitionArgument()*
    ::T_PARENTHESIS_CLOSE::

__fieldDefinitionArgument:
    ArgumentDefinition()
        Directive()*
