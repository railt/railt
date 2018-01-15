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
  <Node name="Document">
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="5">User</Leaf>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="16">id</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="20">ID</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="22">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="28">name</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="33">firstName</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="44">Boolean</Leaf>
          </Node>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="53">lastName</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="63">Boolean</Leaf>
          </Node>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="74">String</Leaf>
          </Node>
          <Leaf name="T_NON_NULL" namespace="default" offset="81">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="87">email</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="94">String</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="105">createdAt</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="115">dateFormat</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="127">String</Leaf>
            <Leaf name="T_NON_NULL" namespace="default" offset="133">!</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="string" offset="138">Some</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="146">String</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
