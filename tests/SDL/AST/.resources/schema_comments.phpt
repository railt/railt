--TEST--

Schema with comments test

--FILE--

#
# Skip
#

# DocBlock: schema
schema {
    # DocBlock: query
    query: TestQuery # Skip

    # DocBlock: mutation
    # DocBlock2: mutation
    mutation: TestMutation # Skip
    # Skip
    # Skip
} # Skip

--EXPECTF--

<Ast>
  <Node name="Document">
    <Node name="SchemaDefinition">
      <Leaf name="T_SCHEMA" namespace="default" offset="31">schema</Leaf>
      <Node name="Query">
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="73">TestQuery</Leaf>
        </Node>
      </Node>
      <Node name="Mutation">
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="156">TestMutation</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
