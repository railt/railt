--TEST--

Extend with comments test

--FILE--

#
# Skip
#

# DocBlock: Foo
extend type Foo {
    # DocBlock: seven
    # DocBlock2: seven
    seven(argument: [String]): Type # Skip
    # Skip
# Skip
} # Skip

# DocBlock: Foo2
extend type Foo2 @onType { # Skip
    # Skip
} # Skip

--EXPECTF--

<Ast>
  <Rule name="Document">
    <Rule name="ExtendDefinition">
      <Rule name="ObjectDefinition">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="40">Foo</Leaf>
        </Rule>
        <Rule name="Field">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="95">seven</Leaf>
          </Rule>
          <Rule name="Argument">
            <Rule name="Name">
              <Leaf name="T_NAME" offset="101">argument</Leaf>
            </Rule>
            <Rule name="List">
              <Rule name="Type">
                <Leaf name="T_NAME" offset="112">String</Leaf>
              </Rule>
            </Rule>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="122">Type</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="ExtendDefinition">
      <Rule name="ObjectDefinition">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="191">Foo2</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="197">onType</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
