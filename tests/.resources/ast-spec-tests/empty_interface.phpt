--TEST--

Interface parsing with empty body

--FILE--

interface InterfaceName {
}

--EXPECTF--

#Document
    #InterfaceDefinition
        #Name
            token(T_NAME, InterfaceName)
