--TEST--

Interface parsing with empty body

--FILE--

interface HasTimestamps {
    createdAt: String!
    updatedAt: String
    timestamps: [String!]!
}

--EXPECTF--

<Ast>
  <Rule name="Document">
    <Rule name="InterfaceDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="10">HasTimestamps</Leaf>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="30">createdAt</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="41">String</Leaf>
          <Leaf name="T_NON_NULL" offset="47">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="53">updatedAt</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="64">String</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="75">timestamps</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="88">String</Leaf>
            <Leaf name="T_NON_NULL" offset="94">!</Leaf>
          </Rule>
          <Leaf name="T_NON_NULL" offset="96">!</Leaf>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
