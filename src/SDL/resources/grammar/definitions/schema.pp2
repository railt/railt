#SchemaDefinition:
    <T_SCHEMA> TypeName()?
    ::T_BRACE_OPEN::
        __schemaField()*
    ::T_BRACE_CLOSE::

__schemaField:
    NameWithReserved() ::T_COLON:: TypeName()
    #SchemaField
