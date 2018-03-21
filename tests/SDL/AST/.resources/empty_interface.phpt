--TEST--

Interface parsing with empty body

--FILE--

interface InterfaceName {
}

--EXPECTF--

<Ast>
  <Rule name="Document">
    <Rule name="InterfaceDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="10">InterfaceName</Leaf>
      </Rule>
    </Rule>
  </Rule>
</Ast>
