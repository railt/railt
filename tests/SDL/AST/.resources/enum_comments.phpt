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
  <Rule name="Document">
    <Rule name="EnumDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="34">Colour</Leaf>
      </Rule>
      <Rule name="Value">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="65">Red</Leaf>
        </Rule>
      </Rule>
      <Rule name="Value">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="118">Green</Leaf>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
