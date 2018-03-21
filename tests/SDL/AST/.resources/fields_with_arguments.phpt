--TEST--

Field arguments

--FILE--

type User {
    id: ID!
    name(firstName: Boolean, lastName: Boolean): [String]!
    email: String
    createdAt(dateFormat: String! = "Some"): String
}

--EXPECTF--

<Ast>
  <Rule name="Document">
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="5">User</Leaf>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="16">id</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="20">ID</Leaf>
          <Leaf name="T_NON_NULL" offset="22">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="28">name</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="33">firstName</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="44">Boolean</Leaf>
          </Rule>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="53">lastName</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="63">Boolean</Leaf>
          </Rule>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="74">String</Leaf>
          </Rule>
          <Leaf name="T_NON_NULL" offset="81">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="87">email</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="94">String</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="105">createdAt</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="115">dateFormat</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="127">String</Leaf>
            <Leaf name="T_NON_NULL" offset="133">!</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_STRING" offset="137">"Some"</Leaf>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="146">String</Leaf>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
