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
  <Rule name="Document">
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="5">User</Leaf>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="16">name</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="30">firstName</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="41">Boolean</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_BOOL_FALSE" offset="51">false</Leaf>
          </Rule>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="66">lastName</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="76">Boolean</Leaf>
          </Rule>
          <Rule name="Directive">
            <Rule name="Name">
              <Leaf name="T_NAME" offset="97">lastNameDirective</Leaf>
            </Rule>
            <Rule name="Argument">
              <Rule name="Name">
                <Leaf name="T_NAME" offset="115">test</Leaf>
              </Rule>
              <Rule name="Value">
                <Leaf name="T_NUMBER_VALUE" offset="121">23</Leaf>
              </Rule>
            </Rule>
          </Rule>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="133">String</Leaf>
          </Rule>
          <Leaf name="T_NON_NULL" offset="140">!</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="151">fieldDirective</Leaf>
          </Rule>
          <Rule name="Argument">
            <Rule name="Name">
              <Leaf name="T_NAME" offset="166">test</Leaf>
            </Rule>
            <Rule name="Value">
              <Leaf name="T_BOOL_TRUE" offset="172">true</Leaf>
            </Rule>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
