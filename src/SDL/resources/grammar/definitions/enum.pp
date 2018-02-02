#EnumDefinition:
    Documentation()?
    ::T_ENUM:: TypeName()
    __enumValues()

//
// ========= [ENUM VALUES] =========
//

__enumValues:
    ::T_BRACE_OPEN::
        __enumValue()+
    ::T_BRACE_CLOSE::

__enumValue:
    Documentation()?
    NameExceptValues()
