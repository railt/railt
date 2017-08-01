--TEST--

Interface parsing with empty body

--FILE--

interface HasTimestamps {
    createdAt: String!
    updatedAt: String
    timestamps: [String!]!
}

--EXPECTF--

#Document
    #InterfaceDefinition
        #Name
            token(T_NAME, HasTimestamps)
        #Field
            #Name
                token(T_NAME, createdAt)
            #Type
                token(T_SCALAR_STRING, String)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, updatedAt)
            #Type
                token(T_SCALAR_STRING, String)
        #Field
            #Name
                token(T_NAME, timestamps)
            #List
                #Type
                    token(T_SCALAR_STRING, String)
                    token(T_NON_NULL, !)
                token(T_NON_NULL, !)
