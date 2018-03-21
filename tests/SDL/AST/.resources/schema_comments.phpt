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
  <Rule name="Document">
    <Rule name="SchemaDefinition">
      <Leaf name="T_SCHEMA" offset="31">schema</Leaf>
      <Rule name="Query">
        <Rule name="Type">
          <Leaf name="T_NAME" offset="73">TestQuery</Leaf>
        </Rule>
      </Rule>
      <Rule name="Mutation">
        <Rule name="Type">
          <Leaf name="T_NAME" offset="156">TestMutation</Leaf>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
