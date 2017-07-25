--TEST--

Type parsing with type and field directives

--FILE--

type A implements A, B @DirectiveA(key: value) {
    fieldA: Value @DirectiveB(key: value)
        @DirectiveC(key: value)
    fieldB: ID @DirectiveD(key: value)
        @DirectiveE(key: value)
}

--EXPECTF--

#Document
    #Type
        #Name
            token(T_NAME, A)
        #Interface
            #Name
                token(T_NAME, A)
            #Name
                token(T_NAME, B)
        #Directive
            #Name
                token(T_NAME, DirectiveA)
            #Arguments
                #Pair
                    #Name
                        token(T_NAME, key)
                    #Value
                        token(T_NAME, value)
        #Field
            #Name
                token(T_NAME, fieldA)
            #Scalar
                token(T_NAME, Value)
            #Directive
                #Name
                    token(T_NAME, DirectiveB)
                #Arguments
                    #Pair
                        #Name
                            token(T_NAME, key)
                        #Value
                            token(T_NAME, value)
            #Directive
                #Name
                    token(T_NAME, DirectiveC)
                #Arguments
                    #Pair
                        #Name
                            token(T_NAME, key)
                        #Value
                            token(T_NAME, value)
        #Field
            #Name
                token(T_NAME, fieldB)
            #Scalar
                token(T_SCALAR_ID, ID)
            #Directive
                #Name
                    token(T_NAME, DirectiveD)
                #Arguments
                    #Pair
                        #Name
                            token(T_NAME, key)
                        #Value
                            token(T_NAME, value)
            #Directive
                #Name
                    token(T_NAME, DirectiveE)
                #Arguments
                    #Pair
                        #Name
                            token(T_NAME, key)
                        #Value
                            token(T_NAME, value)
