--TEST--

Type parsing

--FILE--

type A {
}

--EXPECTF--

<Ast>
  <Node name="Document">
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="5">A</Leaf>
      </Node>
    </Node>
  </Node>
</Ast>
