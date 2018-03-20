//
// Unions
//

#UnionDefinition:
        Documentation()?
    ::T_UNION:: TypeName()
        Directive()*
    ::T_EQUAL:: ::T_OR::? __unionDefinitionTargets()+

__unionDefinitionTargets:
    TypeName() (::T_OR:: TypeName())*
