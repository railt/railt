GenericArgumentsDefinition:
    ::T_PARENTHESIS_OPEN::
        __genericArgumentDefinition()*
    ::T_PARENTHESIS_CLOSE::

__genericArgumentDefinition:
    Variable() ::T_COLON:: TypeName()
    #GenericArgument
