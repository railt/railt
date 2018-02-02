#EnumDefinition:
    Documentation()?
    ::T_ENUM:: TypeName() Directive()*
    ::T_BRACE_OPEN::
        __enumDefinitionValue()+
    ::T_BRACE_CLOSE::

__enumDefinitionValue:
    Documentation()?
    NameExceptValues()
    Directive()*
    #EnumValue
