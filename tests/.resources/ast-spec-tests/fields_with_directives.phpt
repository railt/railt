--TEST--

Field arguments with directives

--FILE--

type User {
    name(
        firstName: Boolean = false,
        lastName: Boolean
            @lastNameDirective(test: 23)
    ): [String]!
        @fieldDirective(test: true)
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
                    token(T_NAME, Boolean)
                #Value
                    token(T_BOOL_FALSE, false)
            #Argument
                #Name
                    token(T_NAME, lastName)
                #Type
                    token(T_NAME, Boolean)
                #Directive
                    #Name
                        token(T_NAME, lastNameDirective)
                    #Argument
                        #Name
                            token(T_NAME, test)
                        #Value
                            token(T_NUMBER_VALUE, 23)
            #List
                #Type
                    token(T_NAME, String)
                token(T_NON_NULL, !)
            #Directive
                #Name
                    token(T_NAME, fieldDirective)
                #Argument
                    #Name
                        token(T_NAME, test)
                    #Value
                        token(T_BOOL_TRUE, true)
