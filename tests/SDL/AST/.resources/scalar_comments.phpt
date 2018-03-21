--TEST--

Scalar with comments test

--FILE--

#
# Skip
#

# DocBlock: Test
scalar Test

# DocBlock: Test2
# DocBlock2:Test2
scalar Test2 @Directive # Skip
# Skip
# Skip

--EXPECTF--

<Ast>
  <Rule name="Document">
    <Rule name="ScalarDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="36">Test</Leaf>
      </Rule>
    </Rule>
    <Rule name="ScalarDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="85">Test2</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="92">Directive</Leaf>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
