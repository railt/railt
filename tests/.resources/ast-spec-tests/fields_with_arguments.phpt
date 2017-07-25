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
    #TypeDefinition
        #Name
            token(T_NAME, User)
        #Field
            #Name
                token(T_NAME, id)
            #Scalar
                token(T_SCALAR_ID, ID)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, name)
            #Arguments
                #Name
                    token(T_NAME, firstName)
                #Scalar
                    token(T_SCALAR_BOOLEAN, Boolean)
                #Name
                    token(T_NAME, lastName)
                #Scalar
                    token(T_SCALAR_BOOLEAN, Boolean)
            #List
                #Scalar
                    token(T_SCALAR_STRING, String)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, email)
            #Scalar
                token(T_SCALAR_STRING, String)
        #Field
            #Name
                token(T_NAME, createdAt)
            #Arguments
                #Name
                    token(T_NAME, dateFormat)
                #Scalar
                    token(T_SCALAR_STRING, String)
                    token(T_NON_NULL, !)
                #DefaultValue
                    #Value
                        token(string:T_STRING, Some)
            #Scalar
                token(T_SCALAR_STRING, String)
