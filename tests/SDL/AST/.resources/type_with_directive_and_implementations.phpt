--TEST--

Type parsing with type directive named "Directive" and implementation two interfaces

--FILE--

type A implements B & C @Directive(key: value) {
}

--EXPECTF--

<Ast>
  <Rule name="Document">
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="5">A</Leaf>
      </Rule>
      <Rule name="Implements">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="18">B</Leaf>
        </Rule>
        <Rule name="Name">
          <Leaf name="T_NAME" offset="22">C</Leaf>
        </Rule>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="25">Directive</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="35">key</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_NAME" offset="40">value</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
