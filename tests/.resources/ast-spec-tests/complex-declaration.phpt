--TEST--

Complex type declaration

--FILE--
interface HasTimestamps {
    createdAt: String
    updatedAt: String
}

interface UserInterface {
    id: ID!
    friends: [User!]
    status: UserStatus!
}

type User implements UserInterface, HasTimestamps {
    id: ID! @isUnique
    friends: [User!] @meta(
        relatedTo: User,
        description: "List of friends which contains a friendship with parent =)"
    )
    status: UserStatus!
    createdAt: String
    updatedAt: String
}

union SearchResult = UserInterface | HasTimestamps

type SearchQuery {
    result: SearchResult
}

enum UserStatus {
    ACTIVE,
    NOT_ACTIVE
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
    #Interface
        #Name
            token(T_NAME, UserInterface)
        #Field
            #Name
                token(T_NAME, id)
            #Scalar
                token(T_SCALAR_ID, ID)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, friends)
            #List
                #Scalar
                    token(T_NAME, User)
                    token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, status)
            #Scalar
                token(T_NAME, UserStatus)
                token(T_NON_NULL, !)
    #Type
        #Name
            token(T_NAME, User)
        #Interface
            #Name
                token(T_NAME, UserInterface)
            #Name
                token(T_NAME, HasTimestamps)
        #Field
            #Name
                token(T_NAME, id)
            #Scalar
                token(T_SCALAR_ID, ID)
                token(T_NON_NULL, !)
            #Directive
                #Name
                    token(T_NAME, isUnique)
        #Field
            #Name
                token(T_NAME, friends)
            #List
                #Scalar
                    token(T_NAME, User)
                    token(T_NON_NULL, !)
            #Directive
                #Name
                    token(T_NAME, meta)
                #Arguments
                    #Pair
                        #Name
                            token(T_NAME, relatedTo)
                        #Value
                            token(T_NAME, User)
                    #Pair
                        #Name
                            token(T_NAME, description)
                        #Value
                            token(string:T_STRING, List of friends which contains a friendship with parent =))
        #Field
            #Name
                token(T_NAME, status)
            #Scalar
                token(T_NAME, UserStatus)
                token(T_NON_NULL, !)
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
    #Union
        #Name
            token(T_NAME, SearchResult)
        #Relations
            #Name
                token(T_NAME, UserInterface)
            #Name
                token(T_NAME, HasTimestamps)
    #Type
        #Name
            token(T_NAME, SearchQuery)
        #Field
            #Name
                token(T_NAME, result)
            #Scalar
                token(T_NAME, SearchResult)
    #Enum
        #Name
            token(T_NAME, UserStatus)
        #Values
            #Name
                token(T_NAME, ACTIVE)
            #Name
                token(T_NAME, NOT_ACTIVE)
