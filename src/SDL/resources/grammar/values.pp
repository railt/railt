//
// Boolean value (true or false)
//
Boolean:
    <T_TRUE> | <T_FALSE>
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
    <T_STRING> | <T_BLOCK_STRING>
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
        __inputField()*
    ::T_BRACE_CLOSE::
    #Input

__inputField:
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
        Boolean() |
        Number()  |
        String()  |
        Null()    |
        Input()   |
        List()    |
        NameWithReserved()
    ) #Value

//
// The shortcut for default value
//
DefaultValue:
    ::T_EQUAL:: Value()
