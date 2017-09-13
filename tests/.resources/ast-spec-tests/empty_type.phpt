--TEST--

Type parsing

--FILE--

type A {
}

--EXPECTF--

#Document
    #ObjectDefinition
        #Name
            token(T_NAME, A)

