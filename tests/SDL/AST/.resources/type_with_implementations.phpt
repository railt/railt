--TEST--

Type parsing with two interface implementations named "B" and "C"

--FILE--

type A implements B & C {
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
    </Rule>
  </Rule>
</Ast>
