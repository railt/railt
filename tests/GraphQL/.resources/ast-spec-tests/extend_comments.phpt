--TEST--

Extend with comments test

--FILE--

#
# Skip
#

# DocBlock: Foo
extend type Foo {
    # DocBlock: seven
    # DocBlock2: seven
    seven(argument: [String]): Type # Skip
    # Skip
# Skip
} # Skip

# DocBlock: Foo2
extend type Foo2 @onType { # Skip
    # Skip
} # Skip

--EXPECTF--

#Document
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
                token(T_NAME, Foo2)
            #Directive
                #Name
                    token(T_NAME, onType)
