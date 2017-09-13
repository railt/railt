--TEST--

Field arguments

--FILE--

type User {
    id: ID!
    name(firstName: Boolean, lastName: Boolean): [String]!
    email: String
    createdAt(dateFormat: String! = "Some"): String
}

--EXPECTF--

#Document
    #ObjectDefinition
        #Name
            token(T_NAME, User)
        #Field
            #Name
                token(T_NAME, id)
            #Type
                token(T_SCALAR_ID, ID)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, name)
            #Argument
                #Name
                    token(T_NAME, firstName)
                #Type
                    token(T_SCALAR_BOOLEAN, Boolean)
            #Argument
                #Name
                    token(T_NAME, lastName)
                #Type
                    token(T_SCALAR_BOOLEAN, Boolean)
            #List
                #Type
                    token(T_SCALAR_STRING, String)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, email)
            #Type
                token(T_SCALAR_STRING, String)
        #Field
            #Name
                token(T_NAME, createdAt)
            #Argument
                #Name
                    token(T_NAME, dateFormat)
                #Type
                    token(T_SCALAR_STRING, String)
                    token(T_NON_NULL, !)
                #Value
                    token(string:T_STRING, Some)
            #Type
                token(T_SCALAR_STRING, String)
