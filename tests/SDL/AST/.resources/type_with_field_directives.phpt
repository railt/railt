--TEST--

Type parsing with type and field directives

--FILE--

type A {
    fieldA: Value
        @DirectiveB(key: value)
        @DirectiveC(key: value)

    fieldB: ID @DirectiveD(key: value) @DirectiveE
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
          <Leaf name="T_NAME" namespace="default" offset="13">fieldA</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="21">Value</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="36">DirectiveB</Leaf>
          </Node>
          <Node name="Argument">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="47">key</Leaf>
            </Node>
            <Node name="Value">
              <Leaf name="T_NAME" namespace="default" offset="52">value</Leaf>
            </Node>
          </Node>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="68">DirectiveC</Leaf>
          </Node>
          <Node name="Argument">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="79">key</Leaf>
            </Node>
            <Node name="Value">
              <Leaf name="T_NAME" namespace="default" offset="84">value</Leaf>
            </Node>
          </Node>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="96">fieldB</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="104">ID</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="108">DirectiveD</Leaf>
          </Node>
          <Node name="Argument">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="119">key</Leaf>
            </Node>
            <Node name="Value">
              <Leaf name="T_NAME" namespace="default" offset="124">value</Leaf>
            </Node>
          </Node>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="132">DirectiveE</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
