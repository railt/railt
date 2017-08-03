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
    six(argument: InputType = {
        key: "value",
        key2: ["value1", "value2"]
    }): Type
    seven(argument: Int = null): Type
}

type AnnotatedObject @onObject(arg: "value", arg2: [Relation]) {
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
    #SchemaDefinition
        #Query
            #Type
                token(T_NAME, QueryType)
        #Mutation
            #Type
                token(T_NAME, MutationType)
    #SchemaDefinition
        #Mutation
            #Type
                token(T_NAME, MutationType)
        #Query
            #Type
                token(T_NAME, QueryType)
    #ObjectDefinition
        #Name
            token(T_NAME, Foo)
        #Implements
            #Name
                token(T_NAME, Bar)
        #Field
            #Name
                token(T_NAME, one)
            #Type
                token(T_NAME, Type)
        #Field
            #Name
                token(T_NAME, two)
            #Argument
                #Name
                    token(T_NAME, argument)
                #Type
                    token(T_NAME, InputType)
                    token(T_NON_NULL, !)
            #Type
                token(T_NAME, Type)
        #Field
            #Name
                token(T_NAME, three)
            #Argument
                #Name
                    token(T_NAME, argument)
                #Type
                    token(T_NAME, InputType)
            #Argument
                #Name
                    token(T_NAME, other)
                #Type
                    token(T_SCALAR_STRING, String)
            #Type
                token(T_SCALAR_INTEGER, Int)
        #Field
            #Name
                token(T_NAME, four)
            #Argument
                #Name
                    token(T_NAME, argument)
                #Type
                    token(T_SCALAR_STRING, String)
                #Value
                    token(string:T_STRING, string)
            #Type
                token(T_SCALAR_STRING, String)
        #Field
            #Name
                token(T_NAME, five)
            #Argument
                #Name
                    token(T_NAME, argument)
                #List
                    #Type
                        token(T_SCALAR_STRING, String)
                #Value
                    #List
                        #Value
                            token(string:T_STRING, string)
                        #Value
                            token(string:T_STRING, string)
            #Type
                token(T_SCALAR_STRING, String)
        #Field
            #Name
                token(T_NAME, six)
            #Argument
                #Name
                    token(T_NAME, argument)
                #Type
                    token(T_NAME, InputType)
                #Value
                    #Object
                        #ObjectPair
                            #Name
                                token(T_NAME, key)
                            #Value
                                token(string:T_STRING, value)
                        #ObjectPair
                            #Name
                                token(T_NAME, key2)
                            #Value
                                #List
                                    #Value
                                        token(string:T_STRING, value1)
                                    #Value
                                        token(string:T_STRING, value2)
            #Type
                token(T_NAME, Type)
        #Field
            #Name
                token(T_NAME, seven)
            #Argument
                #Name
                    token(T_NAME, argument)
                #Type
                    token(T_SCALAR_INTEGER, Int)
                #Value
                    token(T_NULL, null)
            #Type
                token(T_NAME, Type)
    #ObjectDefinition
        #Name
            token(T_NAME, AnnotatedObject)
        #Directive
            #Name
                token(T_NAME, onObject)
            #Argument
                #Name
                    token(T_NAME, arg)
                #Value
                    token(string:T_STRING, value)
            #Argument
                #Name
                    token(T_NAME, arg2)
                #Value
                    #List
                        #Value
                            token(T_NAME, Relation)
        #Field
            #Name
                token(T_NAME, annotatedField)
            #Argument
                #Name
                    token(T_NAME, arg)
                #Type
                    token(T_NAME, Type)
                #Value
                    token(string:T_STRING, default)
                #Directive
                    #Name
                        token(T_NAME, onArg)
            #Type
                token(T_NAME, Type)
            #Directive
                #Name
                    token(T_NAME, onField)
    #InterfaceDefinition
        #Name
            token(T_NAME, Bar)
        #Field
            #Name
                token(T_NAME, one)
            #Type
                token(T_NAME, Type)
        #Field
            #Name
                token(T_NAME, four)
            #Argument
                #Name
                    token(T_NAME, argument)
                #Type
                    token(T_SCALAR_STRING, String)
                #Value
                    token(string:T_STRING, string)
            #Type
                token(T_SCALAR_STRING, String)
    #InterfaceDefinition
        #Name
            token(T_NAME, AnnotatedInterface)
        #Directive
            #Name
                token(T_NAME, onInterface)
        #Field
            #Name
                token(T_NAME, annotatedField)
            #Argument
                #Name
                    token(T_NAME, arg)
                #Type
                    token(T_NAME, Type)
                #Directive
                    #Name
                        token(T_NAME, onArg)
            #Type
                token(T_NAME, Type)
            #Directive
                #Name
                    token(T_NAME, onField)
    #UnionDefinition
        #Name
            token(T_NAME, Feed)
        #Relations
            #Name
                token(T_NAME, Story)
            #Name
                token(T_NAME, Article)
            #Name
                token(T_NAME, Advert)
    #UnionDefinition
        #Name
            token(T_NAME, AnnotatedUnion)
        #Directive
            #Name
                token(T_NAME, onUnion)
        #Relations
            #Name
                token(T_NAME, A)
            #Name
                token(T_NAME, B)
    #UnionDefinition
        #Name
            token(T_NAME, AnnotatedUnionTwo)
        #Directive
            #Name
                token(T_NAME, onUnion)
        #Relations
            #Name
                token(T_NAME, A)
            #Name
                token(T_NAME, B)
    #ScalarDefinition
        #Name
            token(T_NAME, CustomScalar)
    #ScalarDefinition
        #Name
            token(T_NAME, AnnotatedScalar)
        #Directive
            #Name
                token(T_NAME, onScalar)
    #EnumDefinition
        #Name
            token(T_NAME, Site)
        #Value
            #Name
                token(T_NAME, DESKTOP)
        #Value
            #Name
                token(T_NAME, MOBILE)
    #EnumDefinition
        #Name
            token(T_NAME, AnnotatedEnum)
        #Directive
            #Name
                token(T_NAME, onEnum)
        #Value
            #Name
                token(T_NAME, ANNOTATED_VALUE)
            #Directive
                #Name
                    token(T_NAME, onEnumValue)
        #Value
            #Name
                token(T_NAME, OTHER_VALUE)
    #InputDefinition
        #Name
            token(T_NAME, InputType)
        #Field
            #Name
                token(T_NAME, key)
            #Type
                token(T_SCALAR_STRING, String)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, answer)
            #Type
                token(T_SCALAR_INTEGER, Int)
            #DefaultValue
                #Value
                    token(T_NUMBER_VALUE, 42)
    #InputDefinition
        #Name
            token(T_NAME, AnnotatedInput)
        #Directive
            #Name
                token(T_NAME, onInputObjectType)
        #Field
            #Name
                token(T_NAME, annotatedField)
            #Type
                token(T_NAME, Type)
            #Directive
                #Name
                    token(T_NAME, onField)
    #ExtendDefinition
        #ObjectDefinition
            #Name
                token(T_NAME, Foo)
            #Field
                #Name
                    token(T_NAME, seven)
                #Argument
                    #Name
                        token(T_NAME, argument)
                    #List
                        #Type
                            token(T_SCALAR_STRING, String)
                #Type
                    token(T_NAME, Type)
    #ExtendDefinition
        #ObjectDefinition
            #Name
                token(T_NAME, Foo)
            #Directive
                #Name
                    token(T_NAME, onType)
    #ObjectDefinition
        #Name
            token(T_NAME, NoFields)
    #DirectiveDefinition
        #Name
            token(T_NAME, skip)
        #Argument
            #Name
                token(T_NAME, if)
            #Type
                token(T_SCALAR_BOOLEAN, Boolean)
                token(T_NON_NULL, !)
        #Target
            #Name
                token(T_NAME, FIELD)
        #Target
            #Name
                token(T_NAME, FRAGMENT_SPREAD)
        #Target
            #Name
                token(T_NAME, INLINE_FRAGMENT)
    #DirectiveDefinition
        #Name
            token(T_NAME, include)
        #Argument
            #Name
                token(T_NAME, if)
            #Type
                token(T_SCALAR_BOOLEAN, Boolean)
                token(T_NON_NULL, !)
        #Target
            #Name
                token(T_NAME, FIELD)
        #Target
            #Name
                token(T_NAME, FRAGMENT_SPREAD)
        #Target
            #Name
                token(T_NAME, INLINE_FRAGMENT)
    #DirectiveDefinition
        #Name
            token(T_NAME, include2)
        #Argument
            #Name
                token(T_NAME, if)
            #Type
                token(T_SCALAR_BOOLEAN, Boolean)
                token(T_NON_NULL, !)
        #Target
            #Name
                token(T_NAME, FIELD)
        #Target
            #Name
                token(T_NAME, FRAGMENT_SPREAD)
        #Target
            #Name
                token(T_NAME, INLINE_FRAGMENT)
