--TEST--

Type parsing with type directive named "Directive"

--FILE--

type A @Directive(key: value, key2: value2) {
}

--EXPECTF--

<Ast>
  <Node name="Document">
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="5">A</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="8">Directive</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="18">key</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_NAME" namespace="default" offset="23">value</Leaf>
          </Node>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="30">key2</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_NAME" namespace="default" offset="36">value2</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
