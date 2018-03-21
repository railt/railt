--TEST--

Type parsing with type directive named "Directive"

--FILE--

type A @Directive(key: value, key2: value2) {
}

--EXPECTF--

<Ast>
  <Rule name="Document">
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="5">A</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="8">Directive</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="18">key</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_NAME" offset="23">value</Leaf>
          </Rule>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="30">key2</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_NAME" offset="36">value2</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
