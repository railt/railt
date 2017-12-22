--TEST--

Multiple type definitions

--FILE--

type A implements A & B @DirectiveA(key: value) {
    id: ID! @isUnique
}

type B {
    id: ID @isUnique
}

--EXPECTF--

#Document
    #ObjectDefinition
        #Name
            token(T_NAME, A)
        #Implements
            #Name
                token(T_NAME, A)
            #Name
                token(T_NAME, B)
        #Directive
            #Name
                token(T_NAME, DirectiveA)
            #Argument
                #Name
                    token(T_NAME, key)
                #Value
                    token(T_NAME, value)
        #Field
            #Name
                token(T_NAME, id)
            #Type
                token(T_NAME, ID)
                token(T_NON_NULL, !)
            #Directive
                #Name
                    token(T_NAME, isUnique)
    #ObjectDefinition
        #Name
            token(T_NAME, B)
        #Field
            #Name
                token(T_NAME, id)
            #Type
                token(T_NAME, ID)
            #Directive
                #Name
                    token(T_NAME, isUnique)
