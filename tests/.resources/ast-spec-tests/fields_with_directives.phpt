--TEST--

Field arguments with directives

--FILE--

type User {
    name(
        firstName: Boolean = false,
        lastName: Boolean
            @lastNameDirective(test: Any = 23)
    ): [String]!
        @fieldDirective(test: Boolean = true)
}

--EXPECTF--

#Document
    #ObjectDefinition
        #Name
            token(T_NAME, User)
        #Field
            #Name
                token(T_NAME, name)
            #Argument
                #Name
                    token(T_NAME, firstName)
                #Type
                    token(T_SCALAR_BOOLEAN, Boolean)
                #Value
                    token(T_BOOL_FALSE, false)
            #Argument
                #Name
                    token(T_NAME, lastName)
                #Type
                    token(T_SCALAR_BOOLEAN, Boolean)
                #Directive
                    #Name
                        token(T_NAME, lastNameDirective)
                    #Argument
                        #Name
                            token(T_NAME, test)
                        #Value
                            token(T_NAME, Any)
                        #Value
                            token(T_NUMBER_VALUE, 23)
            #List
                #Type
                    token(T_SCALAR_STRING, String)
                token(T_NON_NULL, !)
            #Directive
                #Name
                    token(T_NAME, fieldDirective)
                #Argument
                    #Name
                        token(T_NAME, test)
                    #Value
                        token(T_SCALAR_BOOLEAN, Boolean)
                    #Value
                        token(T_BOOL_TRUE, true)
