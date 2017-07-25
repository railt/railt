--TEST--

Type with all fields definition

--FILE--

type A {
    id: ID
    idList: [ID]
    idNonNull: ID!
    idNonNullList: [ID]!
    idListOfNonNulls: [ID!]!

    int: Int
    intList: [Int]
    intNonNull: Int!
    intNonNullList: [Int]!
    intListOfNonNulls: [Int!]!

    float: Float
    floatList: [Float]
    floatNonNull: Float!
    floatNonNullList: [Float]!
    floatListOfNonNulls: [Float!]!

    string: String
    stringList: [String]
    stringNonNull: String!
    stringNonNullList: [String]!
    stringListOfNonNulls: [String!]!

    boolean: Boolean
    booleanList: [Boolean]
    booleanNonNull: Boolean!
    booleanNonNullList: [Boolean]!
    booleanListOfNonNulls: [Boolean!]!

    relation: Relation
    relationList: [Relation]
    relationNonNull: Relation!
    relationNonNullList: [Relation]!
    relationListOfNonNulls: [Relation!]!
}

--EXPECTF--

#Document
    #Type
        #Name
            token(T_NAME, A)
        #Field
            #Name
                token(T_NAME, id)
            #Scalar
                token(T_SCALAR_ID, ID)
        #Field
            #Name
                token(T_NAME, idList)
            #List
                #Scalar
                    token(T_SCALAR_ID, ID)
        #Field
            #Name
                token(T_NAME, idNonNull)
            #Scalar
                token(T_SCALAR_ID, ID)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, idNonNullList)
            #List
                #Scalar
                    token(T_SCALAR_ID, ID)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, idListOfNonNulls)
            #List
                #Scalar
                    token(T_SCALAR_ID, ID)
                    token(T_NON_NULL, !)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, int)
            #Scalar
                token(T_SCALAR_INTEGER, Int)
        #Field
            #Name
                token(T_NAME, intList)
            #List
                #Scalar
                    token(T_SCALAR_INTEGER, Int)
        #Field
            #Name
                token(T_NAME, intNonNull)
            #Scalar
                token(T_SCALAR_INTEGER, Int)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, intNonNullList)
            #List
                #Scalar
                    token(T_SCALAR_INTEGER, Int)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, intListOfNonNulls)
            #List
                #Scalar
                    token(T_SCALAR_INTEGER, Int)
                    token(T_NON_NULL, !)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, float)
            #Scalar
                token(T_SCALAR_FLOAT, Float)
        #Field
            #Name
                token(T_NAME, floatList)
            #List
                #Scalar
                    token(T_SCALAR_FLOAT, Float)
        #Field
            #Name
                token(T_NAME, floatNonNull)
            #Scalar
                token(T_SCALAR_FLOAT, Float)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, floatNonNullList)
            #List
                #Scalar
                    token(T_SCALAR_FLOAT, Float)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, floatListOfNonNulls)
            #List
                #Scalar
                    token(T_SCALAR_FLOAT, Float)
                    token(T_NON_NULL, !)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, string)
            #Scalar
                token(T_SCALAR_STRING, String)
        #Field
            #Name
                token(T_NAME, stringList)
            #List
                #Scalar
                    token(T_SCALAR_STRING, String)
        #Field
            #Name
                token(T_NAME, stringNonNull)
            #Scalar
                token(T_SCALAR_STRING, String)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, stringNonNullList)
            #List
                #Scalar
                    token(T_SCALAR_STRING, String)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, stringListOfNonNulls)
            #List
                #Scalar
                    token(T_SCALAR_STRING, String)
                    token(T_NON_NULL, !)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, boolean)
            #Scalar
                token(T_SCALAR_BOOLEAN, Boolean)
        #Field
            #Name
                token(T_NAME, booleanList)
            #List
                #Scalar
                    token(T_SCALAR_BOOLEAN, Boolean)
        #Field
            #Name
                token(T_NAME, booleanNonNull)
            #Scalar
                token(T_SCALAR_BOOLEAN, Boolean)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, booleanNonNullList)
            #List
                #Scalar
                    token(T_SCALAR_BOOLEAN, Boolean)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, booleanListOfNonNulls)
            #List
                #Scalar
                    token(T_SCALAR_BOOLEAN, Boolean)
                    token(T_NON_NULL, !)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, relation)
            #Scalar
                token(T_NAME, Relation)
        #Field
            #Name
                token(T_NAME, relationList)
            #List
                #Scalar
                    token(T_NAME, Relation)
        #Field
            #Name
                token(T_NAME, relationNonNull)
            #Scalar
                token(T_NAME, Relation)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, relationNonNullList)
            #List
                #Scalar
                    token(T_NAME, Relation)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, relationListOfNonNulls)
            #List
                #Scalar
                    token(T_NAME, Relation)
                    token(T_NON_NULL, !)
                token(T_NON_NULL, !)
