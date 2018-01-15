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
  <Node name="Document">
    <Node name="InterfaceDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="10">HasTimestamps</Leaf>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="30">createdAt</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="41">String</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="47">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="53">updatedAt</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="64">String</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="75">timestamps</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="88">String</Leaf>
            <Leaf name="T_NON_NULL" namespace="default" offset="94">!</Leaf>
          </Node>
          <Leaf name="T_NON_NULL" namespace="default" offset="96">!</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
