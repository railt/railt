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
  <Rule name="Document">
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="5">A</Leaf>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="13">id</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="17">ID</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="24">idList</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="33">ID</Leaf>
          </Rule>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="41">idNonNull</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="52">ID</Leaf>
          <Leaf name="T_NON_NULL" offset="54">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="60">idNonNullList</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="76">ID</Leaf>
          </Rule>
          <Leaf name="T_NON_NULL" offset="79">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="85">idListOfNonNulls</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="104">ID</Leaf>
            <Leaf name="T_NON_NULL" offset="106">!</Leaf>
          </Rule>
          <Leaf name="T_NON_NULL" offset="108">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="115">int</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="120">Int</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="128">intList</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="138">Int</Leaf>
          </Rule>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="147">intNonNull</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="159">Int</Leaf>
          <Leaf name="T_NON_NULL" offset="162">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="168">intNonNullList</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="185">Int</Leaf>
          </Rule>
          <Leaf name="T_NON_NULL" offset="189">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="195">intListOfNonNulls</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="215">Int</Leaf>
            <Leaf name="T_NON_NULL" offset="218">!</Leaf>
          </Rule>
          <Leaf name="T_NON_NULL" offset="220">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="227">float</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="234">Float</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="244">floatList</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="256">Float</Leaf>
          </Rule>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="267">floatNonNull</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="281">Float</Leaf>
          <Leaf name="T_NON_NULL" offset="286">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="292">floatNonNullList</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="311">Float</Leaf>
          </Rule>
          <Leaf name="T_NON_NULL" offset="317">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="323">floatListOfNonNulls</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="345">Float</Leaf>
            <Leaf name="T_NON_NULL" offset="350">!</Leaf>
          </Rule>
          <Leaf name="T_NON_NULL" offset="352">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="359">string</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="367">String</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="378">stringList</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="391">String</Leaf>
          </Rule>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="403">stringNonNull</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="418">String</Leaf>
          <Leaf name="T_NON_NULL" offset="424">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="430">stringNonNullList</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="450">String</Leaf>
          </Rule>
          <Leaf name="T_NON_NULL" offset="457">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="463">stringListOfNonNulls</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="486">String</Leaf>
            <Leaf name="T_NON_NULL" offset="492">!</Leaf>
          </Rule>
          <Leaf name="T_NON_NULL" offset="494">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="501">boolean</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="510">Boolean</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="522">booleanList</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="536">Boolean</Leaf>
          </Rule>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="549">booleanNonNull</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="565">Boolean</Leaf>
          <Leaf name="T_NON_NULL" offset="572">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="578">booleanNonNullList</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="599">Boolean</Leaf>
          </Rule>
          <Leaf name="T_NON_NULL" offset="607">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="613">booleanListOfNonNulls</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="637">Boolean</Leaf>
            <Leaf name="T_NON_NULL" offset="644">!</Leaf>
          </Rule>
          <Leaf name="T_NON_NULL" offset="646">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="653">relation</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="663">Relation</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="676">relationList</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="691">Relation</Leaf>
          </Rule>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="705">relationNonNull</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="722">Relation</Leaf>
          <Leaf name="T_NON_NULL" offset="730">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="736">relationNonNullList</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="758">Relation</Leaf>
          </Rule>
          <Leaf name="T_NON_NULL" offset="767">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="773">relationListOfNonNulls</Leaf>
        </Rule>
        <Rule name="List">
          <Rule name="Type">
            <Leaf name="T_NAME" offset="798">Relation</Leaf>
            <Leaf name="T_NON_NULL" offset="806">!</Leaf>
          </Rule>
          <Leaf name="T_NON_NULL" offset="808">!</Leaf>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
