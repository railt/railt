--TEST--

Field arguments

--FILE--

type User {
    id: ID!
    name(firstName: Boolean, lastName: Boolean): [String]!
    email: String
    createdAt(dateFormat: String! = "Some"): String
}

--EXPECTF--

#Document
    #Interface
        #Name
            token(T_NAME, InterfaceName)
