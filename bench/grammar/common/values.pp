/**
 * Boolean value (true or false)
 */
#Boolean
    : <T_FALSE>
    | <T_TRUE>

/**
 * Number value
 */
#Number: <T_NUMBER>

/**
 * String value
 */
#String
    : <T_BLOCK_STRING>
    | <T_STRING>

/**
 * Null value
 */
#Null
    : <T_NULL>

/**
 * Input value
 */
#Input:
    ::T_BRACE_OPEN::
        __inputPair()*
    ::T_BRACE_CLOSE::

__inputPair:
    NameWithReserved() ::T_COLON:: Value()
        #Pair

/**
 * The list of values
 */
#List:
    ::T_BRACKET_OPEN::
        Value()*
    ::T_BRACKET_CLOSE::

/**
 * Value
 */
#Value
    : NameWithReserved()
    | Boolean()
    | Number()
    | String()
    | Input()
    | Null()
    | List()

