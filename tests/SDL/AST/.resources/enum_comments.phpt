--TEST--

Enum with comments test

--FILE--

#
# Skip
#

# DocBlock: enum
enum Colour {
    # DocBlock: a
    Red # Skip

    # DocBlock: b
    # DocBlock2: b
    Green # Skip
    # Skip
    # Skip
}
# Skip

--EXPECTF--

<Ast>
  <Node name="Document">
    <Node name="EnumDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="34">Colour</Leaf>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="65">Red</Leaf>
        </Node>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="118">Green</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
