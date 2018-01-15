--TEST--

Complex type declaration

--FILE--
schema {
    query: QueryType
    mutation: MutationType
}

schema {
    mutation: MutationType
    query: QueryType
}

type Foo implements Bar {
    one: Type
    two(argument: InputType!): Type
    three(argument: InputType, other: String): Int
    four(argument: String = "string"): String
    five(argument: [String] = ["string", "string"]): String
    six(argument: InputType = {
        key: "value",
        key2: ["value1", "value2"]
    }): Type
    seven(argument: Int = null): Type
}

type AnnotatedObject @onObject(arg: "value", arg2: [Relation]) {
    annotatedField(arg: Type = "default" @onArg): Type @onField
}

interface Bar {
    one: Type
    four(argument: String = "string"): String
}

interface AnnotatedInterface @onInterface {
    annotatedField(arg: Type @onArg): Type @onField
}

union Feed = Story | Article | Advert

union AnnotatedUnion @onUnion = A | B

union AnnotatedUnionTwo @onUnion = | A | B

scalar CustomScalar

scalar AnnotatedScalar @onScalar

enum Site {
    DESKTOP
    MOBILE
}

enum AnnotatedEnum @onEnum {
    ANNOTATED_VALUE @onEnumValue
    OTHER_VALUE
}

input InputType {
    key: String!
    answer: Int = 42
}

input AnnotatedInput @onInputObjectType {
    annotatedField: Type @onField
}

extend type Foo {
    seven(argument: [String]): Type
}

extend type Foo @onType {}

type NoFields {}

directive @skip(if: Boolean!) on FIELD | FRAGMENT_SPREAD | INLINE_FRAGMENT

directive @include(if: Boolean!)
on FIELD
    | FRAGMENT_SPREAD
    | INLINE_FRAGMENT

directive @include2(if: Boolean!) on
    | FIELD
    | FRAGMENT_SPREAD
    | INLINE_FRAGMENT
    
--EXPECTF--

<Ast>
  <Node name="Document">
    <Node name="SchemaDefinition">
      <Node name="Query">
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="20">QueryType</Leaf>
        </Node>
      </Node>
      <Node name="Mutation">
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="44">MutationType</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="SchemaDefinition">
      <Node name="Mutation">
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="83">MutationType</Leaf>
        </Node>
      </Node>
      <Node name="Query">
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="107">QueryType</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="125">Foo</Leaf>
      </Node>
      <Node name="Implements">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="140">Bar</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="150">one</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="155">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="164">two</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="168">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="178">InputType</Leaf>
            <Leaf name="T_NON_NULL" namespace="default" offset="187">!</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="191">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="200">three</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="206">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="216">InputType</Leaf>
          </Node>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="227">other</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="234">String</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="243">Int</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="251">four</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="256">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="266">String</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="string" offset="276">string</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="286">String</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="297">five</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="302">argument</Leaf>
          </Node>
          <Node name="List">
            <Node name="Type">
              <Leaf name="T_NAME" namespace="default" offset="313">String</Leaf>
            </Node>
          </Node>
          <Node name="Value">
            <Node name="List">
              <Node name="Value">
                <Leaf name="T_STRING" namespace="string" offset="325">string</Leaf>
              </Node>
              <Node name="Value">
                <Leaf name="T_STRING" namespace="string" offset="335">string</Leaf>
              </Node>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="346">String</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="357">six</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="361">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="371">InputType</Leaf>
          </Node>
          <Node name="Value">
            <Node name="Object">
              <Node name="ObjectPair">
                <Node name="Name">
                  <Leaf name="T_NAME" namespace="default" offset="393">key</Leaf>
                </Node>
                <Node name="Value">
                  <Leaf name="T_STRING" namespace="string" offset="399">value</Leaf>
                </Node>
              </Node>
              <Node name="ObjectPair">
                <Node name="Name">
                  <Leaf name="T_NAME" namespace="default" offset="415">key2</Leaf>
                </Node>
                <Node name="Value">
                  <Node name="List">
                    <Node name="Value">
                      <Leaf name="T_STRING" namespace="string" offset="423">value1</Leaf>
                    </Node>
                    <Node name="Value">
                      <Leaf name="T_STRING" namespace="string" offset="433">value2</Leaf>
                    </Node>
                  </Node>
                </Node>
              </Node>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="450">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="459">seven</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="465">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="475">Int</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_NULL" namespace="default" offset="481">null</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="488">Type</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="501">AnnotatedObject</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="518">onObject</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="527">arg</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="string" offset="533">value</Leaf>
          </Node>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="541">arg2</Leaf>
          </Node>
          <Node name="Value">
            <Node name="List">
              <Node name="Value">
                <Leaf name="T_NAME" namespace="default" offset="548">Relation</Leaf>
              </Node>
            </Node>
          </Node>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="565">annotatedField</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="580">arg</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="585">Type</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="string" offset="593">default</Leaf>
          </Node>
          <Node name="Directive">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="603">onArg</Leaf>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="611">Type</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="617">onField</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="InterfaceDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="638">Bar</Leaf>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="648">one</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="653">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="662">four</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="667">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="677">String</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="string" offset="687">string</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="697">String</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="InterfaceDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="717">AnnotatedInterface</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="737">onInterface</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="755">annotatedField</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="770">arg</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="775">Type</Leaf>
          </Node>
          <Node name="Directive">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="781">onArg</Leaf>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="789">Type</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="795">onField</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="UnionDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="812">Feed</Leaf>
      </Node>
      <Node name="Relations">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="819">Story</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="827">Article</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="837">Advert</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="UnionDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="851">AnnotatedUnion</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="867">onUnion</Leaf>
        </Node>
      </Node>
      <Node name="Relations">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="877">A</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="881">B</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="UnionDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="890">AnnotatedUnionTwo</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="909">onUnion</Leaf>
        </Node>
      </Node>
      <Node name="Relations">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="921">A</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="925">B</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="ScalarDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="935">CustomScalar</Leaf>
      </Node>
    </Node>
    <Node name="ScalarDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="956">AnnotatedScalar</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="973">onScalar</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="EnumDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="988">Site</Leaf>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="999">DESKTOP</Leaf>
        </Node>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1011">MOBILE</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="EnumDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1026">AnnotatedEnum</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1041">onEnum</Leaf>
        </Node>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1054">ANNOTATED_VALUE</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1071">onEnumValue</Leaf>
          </Node>
        </Node>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1087">OTHER_VALUE</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="InputDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1108">InputType</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1124">key</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1129">String</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="1135">!</Leaf>
        </Node>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1141">answer</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1149">Int</Leaf>
        </Node>
        <Node name="Value">
          <Leaf name="T_NUMBER_VALUE" namespace="default" offset="1155">42</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="InputDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1167">AnnotatedInput</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1183">onInputObjectType</Leaf>
        </Node>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1207">annotatedField</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1223">Type</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1229">onField</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="ExtendDefinition">
      <Node name="ObjectDefinition">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1252">Foo</Leaf>
        </Node>
        <Node name="Field">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1262">seven</Leaf>
          </Node>
          <Node name="Argument">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="1268">argument</Leaf>
            </Node>
            <Node name="List">
              <Node name="Type">
                <Leaf name="T_NAME" namespace="default" offset="1279">String</Leaf>
              </Node>
            </Node>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="1289">Type</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="ExtendDefinition">
      <Node name="ObjectDefinition">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1309">Foo</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1314">onType</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1330">NoFields</Leaf>
      </Node>
    </Node>
    <Node name="DirectiveDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1354">skip</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1359">if</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1363">Boolean</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="1370">!</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1376">FIELD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1384">FRAGMENT_SPREAD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1402">INLINE_FRAGMENT</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="DirectiveDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1430">include</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1438">if</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1442">Boolean</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="1449">!</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1455">FIELD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1467">FRAGMENT_SPREAD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1489">INLINE_FRAGMENT</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="DirectiveDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1517">include2</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1526">if</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1530">Boolean</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="1537">!</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1549">FIELD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1561">FRAGMENT_SPREAD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1583">INLINE_FRAGMENT</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
