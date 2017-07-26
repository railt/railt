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
    #ObjectDefinition
        #Name
            token(T_NAME, A)
        #Field
            #Name
                token(T_NAME, id)
            #Type
                token(T_SCALAR_ID, ID)
        #Field
            #Name
                token(T_NAME, idList)
            #Type
                #List
                    token(T_SCALAR_ID, ID)
        #Field
            #Name
                token(T_NAME, idNonNull)
            #Type
                token(T_SCALAR_ID, ID)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, idNonNullList)
            #Type
                #List
                    token(T_SCALAR_ID, ID)
                    token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, idListOfNonNulls)
            #Type
                #List
                    token(T_SCALAR_ID, ID)
                    token(T_NON_NULL, !)
                    token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, int)
            #Type
                token(T_SCALAR_INTEGER, Int)
        #Field
            #Name
                token(T_NAME, intList)
            #Type
                #List
                    token(T_SCALAR_INTEGER, Int)
        #Field
            #Name
                token(T_NAME, intNonNull)
            #Type
                token(T_SCALAR_INTEGER, Int)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, intNonNullList)
            #Type
                #List
                    token(T_SCALAR_INTEGER, Int)
                    token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, intListOfNonNulls)
            #Type
                #List
                    token(T_SCALAR_INTEGER, Int)
                    token(T_NON_NULL, !)
                    token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, float)
            #Type
                token(T_SCALAR_FLOAT, Float)
        #Field
            #Name
                token(T_NAME, floatList)
            #Type
                #List
                    token(T_SCALAR_FLOAT, Float)
        #Field
            #Name
                token(T_NAME, floatNonNull)
            #Type
                token(T_SCALAR_FLOAT, Float)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, floatNonNullList)
            #Type
                #List
                    token(T_SCALAR_FLOAT, Float)
                    token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, floatListOfNonNulls)
            #Type
                #List
                    token(T_SCALAR_FLOAT, Float)
                    token(T_NON_NULL, !)
                    token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, string)
            #Type
                token(T_SCALAR_STRING, String)
        #Field
            #Name
                token(T_NAME, stringList)
            #Type
                #List
                    token(T_SCALAR_STRING, String)
        #Field
            #Name
                token(T_NAME, stringNonNull)
            #Type
                token(T_SCALAR_STRING, String)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, stringNonNullList)
            #Type
                #List
                    token(T_SCALAR_STRING, String)
                    token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, stringListOfNonNulls)
            #Type
                #List
                    token(T_SCALAR_STRING, String)
                    token(T_NON_NULL, !)
                    token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, boolean)
            #Type
                token(T_SCALAR_BOOLEAN, Boolean)
        #Field
            #Name
                token(T_NAME, booleanList)
            #Type
                #List
                    token(T_SCALAR_BOOLEAN, Boolean)
        #Field
            #Name
                token(T_NAME, booleanNonNull)
            #Type
                token(T_SCALAR_BOOLEAN, Boolean)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, booleanNonNullList)
            #Type
                #List
                    token(T_SCALAR_BOOLEAN, Boolean)
                    token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, booleanListOfNonNulls)
            #Type
                #List
                    token(T_SCALAR_BOOLEAN, Boolean)
                    token(T_NON_NULL, !)
                    token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, relation)
            #Type
                token(T_NAME, Relation)
        #Field
            #Name
                token(T_NAME, relationList)
            #Type
                #List
                    token(T_NAME, Relation)
        #Field
            #Name
                token(T_NAME, relationNonNull)
            #Type
                token(T_NAME, Relation)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, relationNonNullList)
            #Type
                #List
                    token(T_NAME, Relation)
                    token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, relationListOfNonNulls)
            #Type
                #List
                    token(T_NAME, Relation)
                    token(T_NON_NULL, !)
                    token(T_NON_NULL, !)
