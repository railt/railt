//
// Unions
//

#UnionDefinition:
    Documentation()?
    ::T_UNION:: TypeName()
    __unionBody()

__unionBody:
    ::T_EQUAL:: (::T_OR::)? TypeName()
    (::T_OR:: TypeName())+

//
// Input unions
//

#InputUnionDefinition:
    Documentation()?
    ::T_INPUT_UNION:: TypeName()
    __unionBody()

__unionBody:
    ::T_EQUAL:: (::T_OR::)? TypeName()
    (::T_OR:: TypeName())+
