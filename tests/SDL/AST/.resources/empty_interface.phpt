--TEST--

Interface parsing with empty body

--FILE--

interface InterfaceName {
}

--EXPECTF--

<Ast>
  <Node name="Document">
    <Node name="InterfaceDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="10">InterfaceName</Leaf>
      </Node>
    </Node>
  </Node>
</Ast>
