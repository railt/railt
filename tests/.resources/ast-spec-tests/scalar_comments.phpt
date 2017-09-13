--TEST--

Scalar with comments test

--FILE--

#
# Skip
#

# DocBlock: Test
scalar Test

# DocBlock: Test2
# DocBlock2:Test2
scalar Test2 @Directive # Skip
# Skip
# Skip

--EXPECTF--

#Document
    #ScalarDefinition
        #Name
            token(T_NAME, Test)
    #ScalarDefinition
        #Name
            token(T_NAME, Test2)
        #Directive
            #Name
                token(T_NAME, Directive)
