#DirectiveDefinition:
    Documentation()?
    ::T_DIRECTIVE_AT:: TypeName()
        __directiveArguments()?
    ::T_ON:: __directiveLocation()+

__directiveArguments:
    ::T_PARENTHESIS_OPEN::
        ArgumentDefinition()*
    ::T_PARENTHESIS_CLOSE::
    #DirectiveArguments

__directiveLocations:
    __directiveLocation()+
    #DirectiveLocations

__directiveLocation:
    NameWithReserved()
    (::T_OR:: NameWithReserved())*
