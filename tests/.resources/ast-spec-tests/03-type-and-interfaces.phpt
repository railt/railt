--TEST--

Type parsing with two interface implementations named "B" and "C"

--FILE--

type A implements B, C {
}

--EXPECTF--

#Document
    #Type
        #Name
            token(T_NAME, A)
        #Interface
            #Name
                token(T_NAME, B)
            #Name
                token(T_NAME, C)
