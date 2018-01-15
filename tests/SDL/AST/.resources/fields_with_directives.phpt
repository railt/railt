--TEST--

Field arguments with directives

--FILE--

type User {
    name(
        firstName: Boolean = false,
        lastName: Boolean
            @lastNameDirective(test: 23)
    ): [String]!
        @fieldDirective(test: true)
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
          <Leaf name="T_NAME" namespace="default" offset="16">name</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="30">firstName</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="41">Boolean</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_BOOL_FALSE" namespace="default" offset="51">false</Leaf>
          </Node>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="66">lastName</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="76">Boolean</Leaf>
          </Node>
          <Node name="Directive">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="97">lastNameDirective</Leaf>
            </Node>
            <Node name="Argument">
              <Node name="Name">
                <Leaf name="T_NAME" namespace="default" offset="115">test</Leaf>
              </Node>
              <Node name="Value">
                <Leaf name="T_NUMBER_VALUE" namespace="default" offset="121">23</Leaf>
              </Node>
            </Node>
          </Node>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="133">String</Leaf>
          </Node>
          <Leaf name="T_NON_NULL" namespace="default" offset="140">!</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="151">fieldDirective</Leaf>
          </Node>
          <Node name="Argument">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="166">test</Leaf>
            </Node>
            <Node name="Value">
              <Leaf name="T_BOOL_TRUE" namespace="default" offset="172">true</Leaf>
            </Node>
          </Node>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
