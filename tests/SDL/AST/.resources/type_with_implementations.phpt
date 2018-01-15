--TEST--

Type parsing with two interface implementations named "B" and "C"

--FILE--

type A implements B & C {
}

--EXPECTF--

<Ast>
  <Node name="Document">
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="5">A</Leaf>
      </Node>
      <Node name="Implements">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="18">B</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="22">C</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
