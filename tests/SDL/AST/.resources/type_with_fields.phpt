--TEST--

Type with all fields definition

--FILE--

type A {
    id: ID
    idList: [ID]
    idNonNull: ID!
    idNonNullList: [ID]!
    idListOfNonNulls: [ID!]!

    int: Int
    intList: [Int]
    intNonNull: Int!
    intNonNullList: [Int]!
    intListOfNonNulls: [Int!]!

    float: Float
    floatList: [Float]
    floatNonNull: Float!
    floatNonNullList: [Float]!
    floatListOfNonNulls: [Float!]!

    string: String
    stringList: [String]
    stringNonNull: String!
    stringNonNullList: [String]!
    stringListOfNonNulls: [String!]!

    boolean: Boolean
    booleanList: [Boolean]
    booleanNonNull: Boolean!
    booleanNonNullList: [Boolean]!
    booleanListOfNonNulls: [Boolean!]!

    relation: Relation
    relationList: [Relation]
    relationNonNull: Relation!
    relationNonNullList: [Relation]!
    relationListOfNonNulls: [Relation!]!
}

--EXPECTF--

<Ast>
  <Node name="Document">
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="5">A</Leaf>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="13">id</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="17">ID</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="24">idList</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="33">ID</Leaf>
          </Node>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="41">idNonNull</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="52">ID</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="54">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="60">idNonNullList</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="76">ID</Leaf>
          </Node>
          <Leaf name="T_NON_NULL" namespace="default" offset="79">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="85">idListOfNonNulls</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="104">ID</Leaf>
            <Leaf name="T_NON_NULL" namespace="default" offset="106">!</Leaf>
          </Node>
          <Leaf name="T_NON_NULL" namespace="default" offset="108">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="115">int</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="120">Int</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="128">intList</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="138">Int</Leaf>
          </Node>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="147">intNonNull</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="159">Int</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="162">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="168">intNonNullList</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="185">Int</Leaf>
          </Node>
          <Leaf name="T_NON_NULL" namespace="default" offset="189">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="195">intListOfNonNulls</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="215">Int</Leaf>
            <Leaf name="T_NON_NULL" namespace="default" offset="218">!</Leaf>
          </Node>
          <Leaf name="T_NON_NULL" namespace="default" offset="220">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="227">float</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="234">Float</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="244">floatList</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="256">Float</Leaf>
          </Node>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="267">floatNonNull</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="281">Float</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="286">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="292">floatNonNullList</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="311">Float</Leaf>
          </Node>
          <Leaf name="T_NON_NULL" namespace="default" offset="317">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="323">floatListOfNonNulls</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="345">Float</Leaf>
            <Leaf name="T_NON_NULL" namespace="default" offset="350">!</Leaf>
          </Node>
          <Leaf name="T_NON_NULL" namespace="default" offset="352">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="359">string</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="367">String</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="378">stringList</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="391">String</Leaf>
          </Node>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="403">stringNonNull</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="418">String</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="424">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="430">stringNonNullList</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="450">String</Leaf>
          </Node>
          <Leaf name="T_NON_NULL" namespace="default" offset="457">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="463">stringListOfNonNulls</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="486">String</Leaf>
            <Leaf name="T_NON_NULL" namespace="default" offset="492">!</Leaf>
          </Node>
          <Leaf name="T_NON_NULL" namespace="default" offset="494">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="501">boolean</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="510">Boolean</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="522">booleanList</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="536">Boolean</Leaf>
          </Node>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="549">booleanNonNull</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="565">Boolean</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="572">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="578">booleanNonNullList</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="599">Boolean</Leaf>
          </Node>
          <Leaf name="T_NON_NULL" namespace="default" offset="607">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="613">booleanListOfNonNulls</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="637">Boolean</Leaf>
            <Leaf name="T_NON_NULL" namespace="default" offset="644">!</Leaf>
          </Node>
          <Leaf name="T_NON_NULL" namespace="default" offset="646">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="653">relation</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="663">Relation</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="676">relationList</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="691">Relation</Leaf>
          </Node>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="705">relationNonNull</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="722">Relation</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="730">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="736">relationNonNullList</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="758">Relation</Leaf>
          </Node>
          <Leaf name="T_NON_NULL" namespace="default" offset="767">!</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="773">relationListOfNonNulls</Leaf>
        </Node>
        <Node name="List">
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="798">Relation</Leaf>
            <Leaf name="T_NON_NULL" namespace="default" offset="806">!</Leaf>
          </Node>
          <Leaf name="T_NON_NULL" namespace="default" offset="808">!</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
