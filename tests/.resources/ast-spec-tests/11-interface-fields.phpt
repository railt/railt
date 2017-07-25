--TEST--

Interface parsing with empty body

--FILE--

interface HasTimestamps {
    createdAt: String
    updatedAt: String
}

--EXPECTF--

#Document
    #Interface
        #Name
            token(T_NAME, HasTimestamps)
        #Field
            #Name
                token(T_NAME, createdAt)
            #Scalar
                token(T_SCALAR_STRING, String)
        #Field
            #Name
                token(T_NAME, updatedAt)
            #Scalar
                token(T_SCALAR_STRING, String)
