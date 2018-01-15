--TEST--

Type with one field named "id"

--FILE--

type A {
    id: ID
}

--EXPECTF--

<Ast>
  <Node name="Document">
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="5">A</Leaf>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="13">id</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="17">ID</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
