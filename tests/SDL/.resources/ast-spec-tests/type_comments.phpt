--TEST--

Type with comments test

--FILE--

#
# Skip
#

# DocBlock: TestQuery
type TestQuery {
    # DocBlock: field
    field: TestQuery # Skip

    # DocBlock: field2
    # DocBlock2: field2
    field2: TestMutation
    # Skip
    # Skip
} #
# Skip
#

# DocBlock: TestMutation
type TestMutation {
    # Skip

    # Skip

    # Skip

    # DocBlock: field3
    # DocBlock2: field3
    field3: TestQuery # DocBlock: field4
    # DocBlock: field4
    # DocBlock: field4
    field4: TestMutation
    # Skip
    # Skip

    # Skip
} #
# Skip
#

--EXPECTF--

#Document
    #ObjectDefinition
        #Name
            token(T_NAME, TestQuery)
        #Field
            #Name
                token(T_NAME, field)
            #Type
                token(T_NAME, TestQuery)
        #Field
            #Name
                token(T_NAME, field2)
            #Type
                token(T_NAME, TestMutation)
    #ObjectDefinition
        #Name
            token(T_NAME, TestMutation)
        #Field
            #Name
                token(T_NAME, field3)
            #Type
                token(T_NAME, TestQuery)
        #Field
            #Name
                token(T_NAME, field4)
            #Type
                token(T_NAME, TestMutation)
