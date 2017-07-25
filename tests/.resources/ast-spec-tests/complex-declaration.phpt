--TEST--

Complex type declaration

--FILE--
schema {
    query: QueryType
    mutation: MutationType
}

schema {
    mutation: MutationType
    query: QueryType
}

type Foo implements Bar {
    one: Type
    two(argument: InputType!): Type
    three(argument: InputType, other: String): Int
    four(argument: String = "string"): String
    five(argument: [String] = ["string", "string"]): String
    six(argument: InputType = {key: "value"}): Type
    seven(argument: Int = null): Type
}

type AnnotatedObject @onObject(arg: "value") {
    annotatedField(arg: Type = "default" @onArg): Type @onField
}

interface Bar {
    one: Type
    four(argument: String = "string"): String
}

interface AnnotatedInterface @onInterface {
    annotatedField(arg: Type @onArg): Type @onField
}

union Feed = Story | Article | Advert

union AnnotatedUnion @onUnion = A | B

union AnnotatedUnionTwo @onUnion = | A | B

scalar CustomScalar

scalar AnnotatedScalar @onScalar

enum Site {
    DESKTOP
    MOBILE
}

enum AnnotatedEnum @onEnum {
    ANNOTATED_VALUE @onEnumValue
    OTHER_VALUE
}

input InputType {
    key: String!
    answer: Int = 42
}

input AnnotatedInput @onInputObjectType {
    annotatedField: Type @onField
}

extend type Foo {
    seven(argument: [String]): Type
}

extend type Foo @onType {}

type NoFields {}

directive @skip(if: Boolean!) on FIELD | FRAGMENT_SPREAD | INLINE_FRAGMENT

directive @include(if: Boolean!)
on FIELD
    | FRAGMENT_SPREAD
    | INLINE_FRAGMENT

directive @include2(if: Boolean!) on
    | FIELD
    | FRAGMENT_SPREAD
    | INLINE_FRAGMENT
--EXPECTF--

#Document
    #InterfaceDefinition
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
    #InterfaceDefinition
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
    #TypeDefinition
        #Name
            token(T_NAME, User)
        #InterfaceDefinition
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
    #TypeDefinition
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
