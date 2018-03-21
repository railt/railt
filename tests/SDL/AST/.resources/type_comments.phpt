--TEST--

Type with comments test

--FILE--

#
# Skip
#

# DocBlock: TestQuery
type TestQuery {
    # DocBlock: field
    field: TestQuery # Skip

    # DocBlock: field2
    # DocBlock2: field2
    field2: TestMutation
    # Skip
    # Skip
} #
# Skip
#

# DocBlock: TestMutation
type TestMutation {
    # Skip

    # Skip

    # Skip

    # DocBlock: field3
    # DocBlock2: field3
    field3: TestQuery # DocBlock: field4
    # DocBlock: field4
    # DocBlock: field4
    field4: TestMutation
    # Skip
    # Skip

    # Skip
} #
# Skip
#

--EXPECTF--

<Ast>
  <Rule name="Document">
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="39">TestQuery</Leaf>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="77">field</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="84">TestQuery</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="153">field2</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="161">TestMutation</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="240">TestMutation</Leaf>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="342">field3</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="350">TestQuery</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="429">field4</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="437">TestMutation</Leaf>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
