--TEST--

Type parsing with type directive named "Directive"

--FILE--

type A @Directive(key: value, key2: value2) {
}

--EXPECTF--

#Document
    #TypeDefinition
        #Name
            token(T_NAME, A)
        #Directive
            #Name
                token(T_NAME, Directive)
            #Arguments
                #Pair
                    #Name
                        token(T_NAME, key)
                    #Value
                        token(T_NAME, value)
                #Pair
                    #Name
                        token(T_NAME, key2)
                    #Value
                        token(T_NAME, value2)
