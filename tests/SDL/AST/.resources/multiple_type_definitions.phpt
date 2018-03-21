--TEST--

Multiple type definitions

--FILE--

type A implements A & B @DirectiveA(key: value) {
    id: ID! @isUnique
}

type B {
    id: ID @isUnique
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
          <Leaf name="T_NAME" offset="18">A</Leaf>
        </Rule>
        <Rule name="Name">
          <Leaf name="T_NAME" offset="22">B</Leaf>
        </Rule>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="25">DirectiveA</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="36">key</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_NAME" offset="41">value</Leaf>
          </Rule>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="54">id</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="58">ID</Leaf>
          <Leaf name="T_NON_NULL" offset="60">!</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="63">isUnique</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="80">B</Leaf>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="88">id</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="92">ID</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="96">isUnique</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
