//
// Input definition
//

#InputDefinition:
    ::T_INPUT:: TypeName()
        Directive()*
    ::T_BRACE_OPEN::
        __inputDefinitionField()*
    ::T_BRACE_CLOSE::

__inputDefinitionField:
    ArgumentDefinition()
        Directive()*
    #InputField

