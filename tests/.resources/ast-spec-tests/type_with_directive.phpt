--TEST--

Type parsing with type directive named "Directive"

--FILE--

type A @Directive(key: value, key2: value2) {
}

--EXPECTF--

#Document
    #ObjectDefinition
        #Name
            token(T_NAME, A)
        #Directive
            #Name
                token(T_NAME, Directive)
            #Argument
                #Name
                    token(T_NAME, key)
                #Value
                    token(T_NAME, value)
            #Argument
                #Name
                    token(T_NAME, key2)
                #Value
                    token(T_NAME, value2)
