--TEST--

Interface with comments test

--FILE--

#
# Skip
#

# DocBlock: interface
interface Example {
    # DocBlock: a
    a: TestQuery # Skip

    # DocBlock: b
    # DocBlock2: b
    b: TestMutation # Skip
    # Skip
    # Skip
} # Skip

--EXPECTF--

#Document
    #InterfaceDefinition
        #Name
            token(T_NAME, Example)
        #Field
            #Name
                token(T_NAME, a)
            #Type
                token(T_NAME, TestQuery)
        #Field
            #Name
                token(T_NAME, b)
            #Type
                token(T_NAME, TestMutation)
