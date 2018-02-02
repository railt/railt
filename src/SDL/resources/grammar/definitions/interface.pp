
#InterfaceDefinition:
    Documentation()?
    ::T_INTERFACE:: TypeName()
    GenericArguments()?
    __interfaceImplements()?
    __interfaceFields()

//
// ========= [INTERFACE IMPLEMENTS] =========
//

__interfaceImplements:
    ::T_IMPLEMENTS:: TypeName()+

//
// ========= [INTERFACE FIELDS] =========
//

__interfaceFields:
    ::T_BRACE_OPEN::
        __interfaceField()*
    ::T_BRACE_CLOSE::

__interfaceField:
    Documentation()?
    NameWithReserved()
    __interfaceFieldArguments()?
    ::T_COLON::
        TypeDefinition()
    #InterfaceField

//
// ========= [INTERFACE FIELD ARGUMENTS] =========
//

__interfaceFieldArguments:
    ::T_PARENTHESIS_OPEN::
        ArgumentDefinition()*
    ::T_PARENTHESIS_CLOSE::
    #FieldArguments
