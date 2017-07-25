--TEST--

Type parsing

--FILE--

type A {
}

--EXPECTF--

#Document
    #Type
        #Name
            token(T_NAME, A)

