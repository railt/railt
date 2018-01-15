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
  <Node name="Document">
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="39">TestQuery</Leaf>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="77">field</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="84">TestQuery</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="153">field2</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="161">TestMutation</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="240">TestMutation</Leaf>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="342">field3</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="350">TestQuery</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="429">field4</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="437">TestMutation</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
