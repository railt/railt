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
  <Node name="Document">
    <Node name="ExtendDefinition">
      <Node name="ObjectDefinition">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="40">Foo</Leaf>
        </Node>
        <Node name="Field">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="95">seven</Leaf>
          </Node>
          <Node name="Argument">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="101">argument</Leaf>
            </Node>
            <Node name="List">
              <Node name="Type">
                <Leaf name="T_NAME" namespace="default" offset="112">String</Leaf>
              </Node>
            </Node>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="122">Type</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="ExtendDefinition">
      <Node name="ObjectDefinition">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="191">Foo2</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="197">onType</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
