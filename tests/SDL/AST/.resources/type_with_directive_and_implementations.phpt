--TEST--

Type parsing with type directive named "Directive" and implementation two interfaces

--FILE--

type A implements B & C @Directive(key: value) {
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
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="25">Directive</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="35">key</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_NAME" namespace="default" offset="40">value</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
