
#ObjectDefinition:
    Documentation()?
    ::T_TYPE:: TypeName()
    GenericArguments()?
    __objectImplements()?
    __objectFields()

//
// ========= [OBJECT IMPLEMENTS] =========
//

__objectImplements:
    ::T_IMPLEMENTS:: TypeName()+

//
// ========= [OBJECT FIELDS] =========
//

__objectFields:
    ::T_BRACE_OPEN::
        __objectField()*
    ::T_BRACE_CLOSE::

__objectField:
    Documentation()?
    NameWithReserved()
    __objectFieldArguments()?
    ::T_COLON::
        TypeDefinition()
    #ObjectField

//
// ========= [OBJECT FIELD ARGUMENTS] =========
//

__objectFieldArguments:
    ::T_PARENTHESIS_OPEN::
        ArgumentDefinition()*
    ::T_PARENTHESIS_CLOSE::
    #FieldArguments
