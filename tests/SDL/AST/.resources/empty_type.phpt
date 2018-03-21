--TEST--

Type parsing

--FILE--

type A {
}

--EXPECTF--

<Ast>
  <Rule name="Document">
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="5">A</Leaf>
      </Rule>
    </Rule>
  </Rule>
</Ast>
