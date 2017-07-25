--TEST--

Type parsing

--FILE--

type A {
}

--EXPECTF--

#Document
    #TypeDefinition
        #Name
            token(T_NAME, A)

