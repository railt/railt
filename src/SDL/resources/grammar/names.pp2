//
// Any name without reserved keywords.
//
NameWithoutReserved:
    <T_NAME>
        #Name

//
// Any name includes reserved keywords.
//
NameWithReserved:
    (
        <T_NAME>        |
        <T_TRUE>        |
        <T_FALSE>       |
        <T_NULL>        |
        <T_NAMESPACE>   |
        <T_IMPORT>      |
        <T_IMPORT_FROM> |
        <T_EXTENDS>     |
        <T_IMPLEMENTS>  |
        <T_ON>          |
        <T_TYPE>        |
        <T_ENUM>        |
        <T_UNION>       |
        <T_INPUT_UNION> |
        <T_INTERFACE>   |
        <T_SCHEMA>      |
        <T_SCALAR>      |
        <T_DIRECTIVE>   |
        <T_INPUT>       |
        <T_EXTEND>      |
        <T_FRAGMENT>
    )
        #Name

//
// Any name includes reserved keywords but except values: NULL, TRUE and FALSE.
//
NameExceptValues:
    (
            <T_NAME>        |
            <T_NAMESPACE>   |
            <T_IMPORT>      |
            <T_IMPORT_FROM> |
            <T_EXTENDS>     |
            <T_IMPLEMENTS>  |
            <T_ON>          |
            <T_TYPE>        |
            <T_ENUM>        |
            <T_UNION>       |
            <T_INPUT_UNION> |
            <T_INTERFACE>   |
            <T_SCHEMA>      |
            <T_SCALAR>      |
            <T_DIRECTIVE>   |
            <T_INPUT>       |
            <T_EXTEND>      |
            <T_FRAGMENT>
    )

//
// Variable
//
Variable:
    <T_VARIABLE>
        #Variable

//
// Fully qualified name is an unambiguous name that specifies
// which object, function, or variable a call refers to without
// regard to the context of the call.
//
#TypeName:
    TypeNamespace()? NameWithReserved()

//
// FQN Namespace part
//
#TypeNamespace:
    (NameWithReserved() ::T_NAMESPACE_SEPARATOR::)+
