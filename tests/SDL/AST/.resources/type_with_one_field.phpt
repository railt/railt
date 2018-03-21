--TEST--

Type with one field named "id"

--FILE--

type A {
    id: ID
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
          <Leaf name="T_NAME" offset="13">id</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="17">ID</Leaf>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
