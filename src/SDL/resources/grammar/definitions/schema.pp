#SchemaDefinition:
    ::T_SCHEMA:: TypeName()?
    ::T_BRACE_OPEN::
        __schemaBody()
    ::T_BRACE_CLOSE::

__schemaBody:
    NameWithReserved() ::T_COLON:: TypeName()
