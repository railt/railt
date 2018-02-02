#ScalarDefinition:
        Documentation()?
    ::T_SCALAR:: TypeName()
        __scalarExtends()?
        Directive()*

__scalarExtends:
    ::T_EXTENDS:: TypeName()
        #Extends

