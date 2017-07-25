--TEST--

Type parsing with type directive named "Directive" and implementation two interfaces

--FILE--

type A implements B, C @Directive(key: value) {
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
        #Directive
            #Name
                token(T_NAME, Directive)
            #Arguments
                #Pair
                    #Name
                        token(T_NAME, key)
                    #Value
                        token(T_NAME, value)
