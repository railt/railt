--TEST--

Type name definition named like keyword will not throws an error.

--FILE--

type A {
    ID: ID
    Int: Int
    Bool: Bool
    Float: Float
    String: String


    null: Null
    true: True
    false: False
    on: On
    type: Type
    enum: Enum
    union: Union
    interface: Interface
    implements: Implements
    schema: Schema
    query: Query
    mutation: Mutation
    scalar: Scalar
    directive: Directive
    input: Input
    extend: Extend


    aa: aa
    bb: bb
    cc: cc
    dd: dd
    ee: ee
    ff: ff
    gg: gg
    hh: hh
    ii: ii
    jj: jj
    kk: kk
    ll: ll
    mm: mm
    nn: nn
    oo: oo
    pp: pp
    qq: qq
    rr: rr
    ss: ss
    tt: tt
    uu: uu
    vv: vv
    ww: ww
    xx: xx
    yy: yy
    zz: zz
}

--EXPECTF--

#Document
    #ObjectDefinition
        #Name
            token(T_NAME, A)
        #Field
            #Name
                token(T_SCALAR_ID, ID)
            #Type
                token(T_SCALAR_ID, ID)
        #Field
            #Name
                token(T_SCALAR_INTEGER, Int)
            #Type
                token(T_SCALAR_INTEGER, Int)
        #Field
            #Name
                token(T_NAME, Bool)
            #Type
                token(T_NAME, Bool)
        #Field
            #Name
                token(T_SCALAR_FLOAT, Float)
            #Type
                token(T_SCALAR_FLOAT, Float)
        #Field
            #Name
                token(T_SCALAR_STRING, String)
            #Type
                token(T_SCALAR_STRING, String)
        #Field
            #Name
                token(T_NULL, null)
            #Type
                token(T_NAME, Null)
        #Field
            #Name
                token(T_BOOL_TRUE, true)
            #Type
                token(T_NAME, True)
        #Field
            #Name
                token(T_BOOL_FALSE, false)
            #Type
                token(T_NAME, False)
        #Field
            #Name
                token(T_ON, on)
            #Type
                token(T_NAME, On)
        #Field
            #Name
                token(T_TYPE, type)
            #Type
                token(T_NAME, Type)
        #Field
            #Name
                token(T_ENUM, enum)
            #Type
                token(T_NAME, Enum)
        #Field
            #Name
                token(T_UNION, union)
            #Type
                token(T_NAME, Union)
        #Field
            #Name
                token(T_INTERFACE, interface)
            #Type
                token(T_NAME, Interface)
        #Field
            #Name
                token(T_TYPE_IMPLEMENTS, implements)
            #Type
                token(T_NAME, Implements)
        #Field
            #Name
                token(T_SCHEMA, schema)
            #Type
                token(T_NAME, Schema)
        #Field
            #Name
                token(T_SCHEMA_QUERY, query)
            #Type
                token(T_NAME, Query)
        #Field
            #Name
                token(T_SCHEMA_MUTATION, mutation)
            #Type
                token(T_NAME, Mutation)
        #Field
            #Name
                token(T_SCALAR, scalar)
            #Type
                token(T_NAME, Scalar)
        #Field
            #Name
                token(T_DIRECTIVE, directive)
            #Type
                token(T_NAME, Directive)
        #Field
            #Name
                token(T_INPUT, input)
            #Type
                token(T_NAME, Input)
        #Field
            #Name
                token(T_EXTEND, extend)
            #Type
                token(T_NAME, Extend)
        #Field
            #Name
                token(T_NAME, aa)
            #Type
                token(T_NAME, aa)
        #Field
            #Name
                token(T_NAME, bb)
            #Type
                token(T_NAME, bb)
        #Field
            #Name
                token(T_NAME, cc)
            #Type
                token(T_NAME, cc)
        #Field
            #Name
                token(T_NAME, dd)
            #Type
                token(T_NAME, dd)
        #Field
            #Name
                token(T_NAME, ee)
            #Type
                token(T_NAME, ee)
        #Field
            #Name
                token(T_NAME, ff)
            #Type
                token(T_NAME, ff)
        #Field
            #Name
                token(T_NAME, gg)
            #Type
                token(T_NAME, gg)
        #Field
            #Name
                token(T_NAME, hh)
            #Type
                token(T_NAME, hh)
        #Field
            #Name
                token(T_NAME, ii)
            #Type
                token(T_NAME, ii)
        #Field
            #Name
                token(T_NAME, jj)
            #Type
                token(T_NAME, jj)
        #Field
            #Name
                token(T_NAME, kk)
            #Type
                token(T_NAME, kk)
        #Field
            #Name
                token(T_NAME, ll)
            #Type
                token(T_NAME, ll)
        #Field
            #Name
                token(T_NAME, mm)
            #Type
                token(T_NAME, mm)
        #Field
            #Name
                token(T_NAME, nn)
            #Type
                token(T_NAME, nn)
        #Field
            #Name
                token(T_NAME, oo)
            #Type
                token(T_NAME, oo)
        #Field
            #Name
                token(T_NAME, pp)
            #Type
                token(T_NAME, pp)
        #Field
            #Name
                token(T_NAME, qq)
            #Type
                token(T_NAME, qq)
        #Field
            #Name
                token(T_NAME, rr)
            #Type
                token(T_NAME, rr)
        #Field
            #Name
                token(T_NAME, ss)
            #Type
                token(T_NAME, ss)
        #Field
            #Name
                token(T_NAME, tt)
            #Type
                token(T_NAME, tt)
        #Field
            #Name
                token(T_NAME, uu)
            #Type
                token(T_NAME, uu)
        #Field
            #Name
                token(T_NAME, vv)
            #Type
                token(T_NAME, vv)
        #Field
            #Name
                token(T_NAME, ww)
            #Type
                token(T_NAME, ww)
        #Field
            #Name
                token(T_NAME, xx)
            #Type
                token(T_NAME, xx)
        #Field
            #Name
                token(T_NAME, yy)
            #Type
                token(T_NAME, yy)
        #Field
            #Name
                token(T_NAME, zz)
            #Type
                token(T_NAME, zz)
