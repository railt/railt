--TEST--

Type parsing with type and field directives

--FILE--

type A {
    fieldA: Value
        @DirectiveB(key: value)
        @DirectiveC(key: value)

    fieldB: ID @DirectiveD(key: value) @DirectiveE
}

--EXPECTF--

#Document
    #TypeDefinition
        #Name
            token(T_NAME, A)
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
