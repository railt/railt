--TEST--

Enum with comments test

--FILE--

#
# Skip
#

# DocBlock: enum
enum Colour {
    # DocBlock: a
    Red # Skip

    # DocBlock: b
    # DocBlock2: b
    Green # Skip
    # Skip
    # Skip
}
# Skip

--EXPECTF--

#Document
    #EnumDefinition
        #Name
            token(T_NAME, Colour)
        #Value
            #Name
                token(T_NAME, Red)
        #Value
            #Name
                token(T_NAME, Green)
