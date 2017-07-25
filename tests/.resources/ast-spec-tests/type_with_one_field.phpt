--TEST--

Type with one field named "id"

--FILE--

type A {
    id: ID
}

--EXPECTF--

#Document
    #Type
        #Name
            token(T_NAME, A)
        #Field
            #Name
                token(T_NAME, id)
            #Scalar
                token(T_SCALAR_ID, ID)

