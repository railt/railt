--TEST--

Type name definition named like keyword will not throws an error.

--FILE--

type A {
    #
    # Fields list named like a GraphQL scalar
    #
    ID: ID          # Allowed name "ID"
    Int: Int        # Allowed name "Int"
    Bool: Bool      # Allowed name "Bool"
    Float: Float    # Allowed name "Float"
    String: String  # Allowed name "String"

    #
    # Fields named as keywords
    #
    null: Null
    enum: Enum
    type: Type
    true: True
    false: False
    union: Union
    interface: Interface
    implements: Implements

    #
    # List of fields in alphabetical order
    #
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
    #Type
        #Name
            token(T_NAME, A)
        #Field
            #Name
                token(T_SCALAR_ID, ID)
            #Scalar
                token(T_SCALAR_ID, ID)
        #Field
            #Name
                token(T_SCALAR_INTEGER, Int)
            #Scalar
                token(T_SCALAR_INTEGER, Int)
        #Field
            #Name
                token(T_NAME, Bool)
            #Scalar
                token(T_NAME, Bool)
        #Field
            #Name
                token(T_SCALAR_FLOAT, Float)
            #Scalar
                token(T_SCALAR_FLOAT, Float)
        #Field
            #Name
                token(T_SCALAR_STRING, String)
            #Scalar
                token(T_SCALAR_STRING, String)
        #Field
            #Name
                token(T_NULL, null)
            #Scalar
                token(T_NAME, Null)
        #Field
            #Name
                token(T_ENUM, enum)
            #Scalar
                token(T_NAME, Enum)
        #Field
            #Name
                token(T_TYPE, type)
            #Scalar
                token(T_NAME, Type)
        #Field
            #Name
                token(T_BOOL_TRUE, true)
            #Scalar
                token(T_NAME, True)
        #Field
            #Name
                token(T_BOOL_FALSE, false)
            #Scalar
                token(T_NAME, False)
        #Field
            #Name
                token(T_UNION, union)
            #Scalar
                token(T_NAME, Union)
        #Field
            #Name
                token(T_INTERFACE, interface)
            #Scalar
                token(T_NAME, Interface)
        #Field
            #Name
                token(T_IMPLEMENTS, implements)
            #Scalar
                token(T_NAME, Implements)
        #Field
            #Name
                token(T_NAME, aa)
            #Scalar
                token(T_NAME, aa)
        #Field
            #Name
                token(T_NAME, bb)
            #Scalar
                token(T_NAME, bb)
        #Field
            #Name
                token(T_NAME, cc)
            #Scalar
                token(T_NAME, cc)
        #Field
            #Name
                token(T_NAME, dd)
            #Scalar
                token(T_NAME, dd)
        #Field
            #Name
                token(T_NAME, ee)
            #Scalar
                token(T_NAME, ee)
        #Field
            #Name
                token(T_NAME, ff)
            #Scalar
                token(T_NAME, ff)
        #Field
            #Name
                token(T_NAME, gg)
            #Scalar
                token(T_NAME, gg)
        #Field
            #Name
                token(T_NAME, hh)
            #Scalar
                token(T_NAME, hh)
        #Field
            #Name
                token(T_NAME, ii)
            #Scalar
                token(T_NAME, ii)
        #Field
            #Name
                token(T_NAME, jj)
            #Scalar
                token(T_NAME, jj)
        #Field
            #Name
                token(T_NAME, kk)
            #Scalar
                token(T_NAME, kk)
        #Field
            #Name
                token(T_NAME, ll)
            #Scalar
                token(T_NAME, ll)
        #Field
            #Name
                token(T_NAME, mm)
            #Scalar
                token(T_NAME, mm)
        #Field
            #Name
                token(T_NAME, nn)
            #Scalar
                token(T_NAME, nn)
        #Field
            #Name
                token(T_NAME, oo)
            #Scalar
                token(T_NAME, oo)
        #Field
            #Name
                token(T_NAME, pp)
            #Scalar
                token(T_NAME, pp)
        #Field
            #Name
                token(T_NAME, qq)
            #Scalar
                token(T_NAME, qq)
        #Field
            #Name
                token(T_NAME, rr)
            #Scalar
                token(T_NAME, rr)
        #Field
            #Name
                token(T_NAME, ss)
            #Scalar
                token(T_NAME, ss)
        #Field
            #Name
                token(T_NAME, tt)
            #Scalar
                token(T_NAME, tt)
        #Field
            #Name
                token(T_NAME, uu)
            #Scalar
                token(T_NAME, uu)
        #Field
            #Name
                token(T_NAME, vv)
            #Scalar
                token(T_NAME, vv)
        #Field
            #Name
                token(T_NAME, ww)
            #Scalar
                token(T_NAME, ww)
        #Field
            #Name
                token(T_NAME, xx)
            #Scalar
                token(T_NAME, xx)
        #Field
            #Name
                token(T_NAME, yy)
            #Scalar
                token(T_NAME, yy)
        #Field
            #Name
                token(T_NAME, zz)
            #Scalar
                token(T_NAME, zz)
