--TEST--

Interface parsing with empty body

--FILE--

interface InterfaceName {
}

--EXPECTF--

#Document
    #Interface
        #Name
            token(T_NAME, InterfaceName)
