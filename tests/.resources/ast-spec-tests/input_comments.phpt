--TEST--

Input with comments test

--FILE--

#
# Skip
#

# DocBlock: InputType
input InputType {
    # DocBlock: key
    # DocBlock2: key
    key: String! # Skip

# DocBlock: answer
    # DocBlock2: answer
    answer: Int = 42 # Skip
    # Skip
}

# DocBlock: AnnotatedInput
input AnnotatedInput @onInputObjectType {
    # DocBlock: annotatedField
    # DocBlock2: annotatedField
    annotatedField: Type @onField # Skip
    # Skip
}

--EXPECTF--

#Document
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
