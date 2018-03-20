
#Directive:
    ::T_DIRECTIVE_AT:: TypeName()
        __directiveInvocationArguments()?

__directiveInvocationArguments:
    ::T_PARENTHESIS_OPEN::
        __directiveInvocationArgument()*
    ::T_PARENTHESIS_CLOSE::

__directiveInvocationArgument:
     ArgumentInvocation()
        #DirectiveArgument

