--TEST--

Type parsing with two interface implementations named "B" and "C"

--FILE--

type A implements B, C {
}

--EXPECTF--

#Document
    #ObjectDefinition
        #Name
            token(T_NAME, A)
        #Implements
            #Name
                token(T_NAME, B)
            #Name
                token(T_NAME, C)
