#DirectiveDefinition:
    Documentation()?
    ::T_DIRECTIVE:: ::T_DIRECTIVE_AT:: TypeName()
    __directiveDefinitionArguments()?
    ::T_ON:: __directiveDefinitionLocations()

__directiveDefinitionArguments:
    ::T_PARENTHESIS_OPEN::
        __directiveDefinitionArgument()*
    ::T_PARENTHESIS_CLOSE::

__directiveDefinitionArgument:
     ArgumentDefinition()
     #DirectiveArgument

__directiveDefinitionLocations:
    ::T_OR::? __directiveDefinitionLocation()+
    #DirectiveLocations

__directiveDefinitionLocation:
    NameWithReserved() (::T_OR:: NameWithReserved())*
