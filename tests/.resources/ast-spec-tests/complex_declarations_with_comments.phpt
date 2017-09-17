--TEST--

Complex type declarations with comments

--FILE--

# DocBlock: schema
schema {
    # DocBlock: schema { query }
# DocBlock2: schema { query }
    query: QueryType # COMMENT
# DocBlock: schema { mutation }
    mutation: MutationType # COMMENT
# COMMENT
} # COMMENT
# DocBlock: schema
schema { # COMMENT
# DocBlock: schema { mutation }
    mutation: MutationType # COMMENT
# DocBlock: schema { query }
    query: QueryType # COMMENT
# COMMENT
} # COMMENT
# DocBlock: type Foo
type Foo implements Bar {
    # DocBlock: type Foo { one }
# DocBlock2: type Foo { one }
    one: Type # COMMENT
# DocBlock: type Foo { two }
    two(argument: InputType!): Type # COMMENT
# DocBlock: type Foo { three }
    three(argument: InputType, other: String): Int # COMMENT
# DocBlock: type Foo { four }
    four(argument: String = "string"): String # COMMENT
# DocBlock: type Foo { five }
    five(argument: [String] = ["string", "string"]): String # COMMENT
# DocBlock: type Foo { six }
    six(argument: InputType = {
        key: "value",
        key2: ["value1", "value2"]
    }): Type # COMMENT
# DocBlock: type Foo { seven }
    seven(argument: Int = null): Type # COMMENT
# COMMENT
} # COMMENT
# DocBlock: type AnnotatedObject
type AnnotatedObject @onObject(arg: "value", arg2: [Relation]) { # COMMENT
# DocBlock: type AnnotatedObject { annotatedField }
    annotatedField(arg: Type = "default" @onArg): Type @onField # COMMENT
# COMMENT
} # COMMENT
# DocBlock: interface Bar
interface Bar {
    # DocBlock: interface Bar { one }
# DocBlock2: interface Bar { one }
    one: Type # COMMENT
# DocBlock: interface Bar { four }
    four(argument: String = "string"): String # COMMENT
# COMMENT
} # COMMENT
# DocBlock: interface AnnotatedInterface
interface AnnotatedInterface @onInterface { # COMMENT
# DocBlock: interface AnnotatedInterface { annotatedField }
    annotatedField(arg: Type @onArg): Type @onField # COMMENT
# COMMENT
} # COMMENT
# DocBlock: union Feed
union Feed = Story | Article | Advert # COMMENT
# DocBlock: union AnnotatedUnion
union AnnotatedUnion @onUnion = A | B # COMMENT
# DocBlock: union AnnotatedUnionTwo
union AnnotatedUnionTwo @onUnion = | A | B # COMMENT
# DocBlock: scalar CustomScalar
scalar CustomScalar # COMMENT
# DocBlock: scalar AnnotatedScalar
scalar AnnotatedScalar @onScalar # COMMENT
# DocBlock: enum Site
enum Site { # COMMENT
# DocBlock: enum Site { DESKTOP }
    DESKTOP # COMMENT
# DocBlock: enum Site { MOBILE }
    MOBILE # COMMENT
} # COMMENT
# DocBlock: enum AnnotatedEnum
enum AnnotatedEnum @onEnum { # COMMENT
# DocBlock: enum AnnotatedEnum { ANNOTATED_VALUE }
    ANNOTATED_VALUE @onEnumValue # COMMENT
# DocBlock: enum AnnotatedEnum { OTHER_VALUE }
    OTHER_VALUE # COMMENT
# COMMENT
} # COMMENT
# DocBlock: input InputType
input InputType { # COMMENT
# DocBlock: input InputType { key }
    key: String! # COMMENT
# DocBlock: input InputType { answer }
    answer: Int = 42 # COMMENT
# COMMENT
} # COMMENT
# DocBlock: input AnnotatedInput
input AnnotatedInput @onInputObjectType { # COMMENT
# DocBlock: input AnnotatedInput { annotatedField }
    annotatedField: Type @onField # COMMENT
# COMMENT
} # COMMENT
# DocBlock: extend type Foo
extend type Foo { # COMMENT
# DocBlock: extend type Foo { seven }
    seven(argument: [String]): Type # COMMENT
# COMMENT
} # COMMENT
# DocBlock: extend type Foo
extend type Foo @onType
{ # COMMENT
# COMMENT
} # COMMENT
# DocBlock: type NoFields
type NoFields { # COMMENT
# COMMENT
} # COMMENT
# DocBlock: directive @skip
directive @skip(if: Boolean!) # COMMENT
    on FIELD | FRAGMENT_SPREAD | INLINE_FRAGMENT # COMMENT
# COMMENT
# DocBlock: directive @include
directive @include(if: Boolean!) # COMMENT
    on FIELD | FRAGMENT_SPREAD | INLINE_FRAGMENT
# DocBlock: directive @include2
directive @include2(if: Boolean!) on # COMMENT
    | FIELD
    | FRAGMENT_SPREAD # COMMENT
    | INLINE_FRAGMENT
# COMMENT
# COMMENT

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
                    token(T_NAME, String)
            #Type
                token(T_NAME, Int)
        #Field
            #Name
                token(T_NAME, four)
            #Argument
                #Name
                    token(T_NAME, argument)
                #Type
                    token(T_NAME, String)
                #Value
                    token(string:T_STRING, string)
            #Type
                token(T_NAME, String)
        #Field
            #Name
                token(T_NAME, five)
            #Argument
                #Name
                    token(T_NAME, argument)
                #List
                    #Type
                        token(T_NAME, String)
                #Value
                    #List
                        #Value
                            token(string:T_STRING, string)
                        #Value
                            token(string:T_STRING, string)
            #Type
                token(T_NAME, String)
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
                    token(T_NAME, Int)
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
                    token(T_NAME, String)
                #Value
                    token(string:T_STRING, string)
            #Type
                token(T_NAME, String)
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
                token(T_NAME, String)
                token(T_NON_NULL, !)
        #Field
            #Name
                token(T_NAME, answer)
            #Type
                token(T_NAME, Int)
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
                            token(T_NAME, String)
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
                token(T_NAME, Boolean)
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
                token(T_NAME, Boolean)
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
                token(T_NAME, Boolean)
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
