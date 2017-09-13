--TEST--

Union with comments test

--FILE--

#
# Skip
#

# DocBlock: Feed
union Feed = Story | Article | Advert # Skip

# DocBlock: AnnotatedUnion
union AnnotatedUnion @onUnion = A | B # Skip

# DocBlock: AnnotatedUnionTwo
union AnnotatedUnionTwo @onUnion = | A | B # Skip
#
# Skip

--EXPECTF--

#Document
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
