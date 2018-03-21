--TEST--

Interface with comments test

--FILE--

#
# Skip
#

# DocBlock: interface
interface Example {
    # DocBlock: a
    a: TestQuery # Skip

    # DocBlock: b
    # DocBlock2: b
    b: TestMutation # Skip
    # Skip
    # Skip
} # Skip

--EXPECTF--

<Ast>
  <Rule name="Document">
    <Rule name="InterfaceDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="44">Example</Leaf>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="76">a</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="79">TestQuery</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="138">b</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="141">TestMutation</Leaf>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
