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
  <Rule name="Document">
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="5">A</Leaf>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="13">fieldA</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="21">Value</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="36">DirectiveB</Leaf>
          </Rule>
          <Rule name="Argument">
            <Rule name="Name">
              <Leaf name="T_NAME" offset="47">key</Leaf>
            </Rule>
            <Rule name="Value">
              <Leaf name="T_NAME" offset="52">value</Leaf>
            </Rule>
          </Rule>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="68">DirectiveC</Leaf>
          </Rule>
          <Rule name="Argument">
            <Rule name="Name">
              <Leaf name="T_NAME" offset="79">key</Leaf>
            </Rule>
            <Rule name="Value">
              <Leaf name="T_NAME" offset="84">value</Leaf>
            </Rule>
          </Rule>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="96">fieldB</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="104">ID</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="108">DirectiveD</Leaf>
          </Rule>
          <Rule name="Argument">
            <Rule name="Name">
              <Leaf name="T_NAME" offset="119">key</Leaf>
            </Rule>
            <Rule name="Value">
              <Leaf name="T_NAME" offset="124">value</Leaf>
            </Rule>
          </Rule>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="132">DirectiveE</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
