//
// Boolean value (true or false)
//
Boolean:
    <T_FALSE> |
    <T_TRUE>
        #Boolean

//
// Number value
//
Number:
    <T_NUMBER>
        #Number

//
// String value
//
String:
    <T_BLOCK_STRING> |
    <T_STRING>
        #String

//
// Null value
//
Null:
    <T_NULL>
        #Null

//
// Input value
//
Input:
    ::T_BRACE_OPEN::
        __inputPair()*
    ::T_BRACE_CLOSE::
        #Input

__inputPair:
    NameWithReserved() ::T_COLON:: Value()
        #Pair

//
// The list of values
//
List:
    ::T_BRACKET_OPEN::
        Value()*
    ::T_BRACKET_CLOSE::
        #List

//
// Value
//
Value:
    (
        NameWithReserved()  |
        Boolean()           |
        Number()            |
        String()            |
        Input()             |
        Null()              |
        List()
    )
        #Value
