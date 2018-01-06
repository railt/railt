--TEST--

Schema with comments test

--FILE--

#
# Skip
#

# DocBlock: schema
schema {
    # DocBlock: query
    query: TestQuery # Skip

    # DocBlock: mutation
    # DocBlock2: mutation
    mutation: TestMutation # Skip
    # Skip
    # Skip
} # Skip

--EXPECTF--

#Document
    #SchemaDefinition
        #Query
            #Type
                token(T_NAME, TestQuery)
        #Mutation
            #Type
                token(T_NAME, TestMutation)
