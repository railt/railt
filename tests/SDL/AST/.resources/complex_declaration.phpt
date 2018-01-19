--TEST--

Complex type declaration

--FILE--
schema {
    query: QueryType
    mutation: MutationType
}

schema Example {
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
      <Leaf name="T_SCHEMA" namespace="default" offset="0">schema</Leaf>
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
      <Leaf name="T_SCHEMA" namespace="default" offset="60">schema</Leaf>
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="67">Example</Leaf>
      </Node>
      <Node name="Mutation">
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="91">MutationType</Leaf>
        </Node>
      </Node>
      <Node name="Query">
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="115">QueryType</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="133">Foo</Leaf>
      </Node>
      <Node name="Implements">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="148">Bar</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="158">one</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="163">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="172">two</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="176">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="186">InputType</Leaf>
            <Leaf name="T_NON_NULL" namespace="default" offset="195">!</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="199">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="208">three</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="214">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="224">InputType</Leaf>
          </Node>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="235">other</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="242">String</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="251">Int</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="259">four</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="264">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="274">String</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="default" offset="283">"string"</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="294">String</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="305">five</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="310">argument</Leaf>
          </Node>
          <Node name="List">
            <Node name="Type">
              <Leaf name="T_NAME" namespace="default" offset="321">String</Leaf>
            </Node>
          </Node>
          <Node name="Value">
            <Node name="List">
              <Node name="Value">
                <Leaf name="T_STRING" namespace="default" offset="332">"string"</Leaf>
              </Node>
              <Node name="Value">
                <Leaf name="T_STRING" namespace="default" offset="342">"string"</Leaf>
              </Node>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="354">String</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="365">six</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="369">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="379">InputType</Leaf>
          </Node>
          <Node name="Value">
            <Node name="Object">
              <Node name="ObjectPair">
                <Node name="Name">
                  <Leaf name="T_NAME" namespace="default" offset="401">key</Leaf>
                </Node>
                <Node name="Value">
                  <Leaf name="T_STRING" namespace="default" offset="406">"value"</Leaf>
                </Node>
              </Node>
              <Node name="ObjectPair">
                <Node name="Name">
                  <Leaf name="T_NAME" namespace="default" offset="423">key2</Leaf>
                </Node>
                <Node name="Value">
                  <Node name="List">
                    <Node name="Value">
                      <Leaf name="T_STRING" namespace="default" offset="430">"value1"</Leaf>
                    </Node>
                    <Node name="Value">
                      <Leaf name="T_STRING" namespace="default" offset="440">"value2"</Leaf>
                    </Node>
                  </Node>
                </Node>
              </Node>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="458">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="467">seven</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="473">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="483">Int</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_NULL" namespace="default" offset="489">null</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="496">Type</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="509">AnnotatedObject</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="526">onObject</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="535">arg</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="default" offset="540">"value"</Leaf>
          </Node>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="549">arg2</Leaf>
          </Node>
          <Node name="Value">
            <Node name="List">
              <Node name="Value">
                <Leaf name="T_NAME" namespace="default" offset="556">Relation</Leaf>
              </Node>
            </Node>
          </Node>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="573">annotatedField</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="588">arg</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="593">Type</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="default" offset="600">"default"</Leaf>
          </Node>
          <Node name="Directive">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="611">onArg</Leaf>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="619">Type</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="625">onField</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="InterfaceDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="646">Bar</Leaf>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="656">one</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="661">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="670">four</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="675">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="685">String</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="default" offset="694">"string"</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="705">String</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="InterfaceDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="725">AnnotatedInterface</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="745">onInterface</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="763">annotatedField</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="778">arg</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="783">Type</Leaf>
          </Node>
          <Node name="Directive">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="789">onArg</Leaf>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="797">Type</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="803">onField</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="UnionDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="820">Feed</Leaf>
      </Node>
      <Node name="Relations">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="827">Story</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="835">Article</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="845">Advert</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="UnionDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="859">AnnotatedUnion</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="875">onUnion</Leaf>
        </Node>
      </Node>
      <Node name="Relations">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="885">A</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="889">B</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="UnionDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="898">AnnotatedUnionTwo</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="917">onUnion</Leaf>
        </Node>
      </Node>
      <Node name="Relations">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="929">A</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="933">B</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="ScalarDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="943">CustomScalar</Leaf>
      </Node>
    </Node>
    <Node name="ScalarDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="964">AnnotatedScalar</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="981">onScalar</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="EnumDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="996">Site</Leaf>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1007">DESKTOP</Leaf>
        </Node>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1019">MOBILE</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="EnumDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1034">AnnotatedEnum</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1049">onEnum</Leaf>
        </Node>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1062">ANNOTATED_VALUE</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1079">onEnumValue</Leaf>
          </Node>
        </Node>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1095">OTHER_VALUE</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="InputDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1116">InputType</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1132">key</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1137">String</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="1143">!</Leaf>
        </Node>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1149">answer</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1157">Int</Leaf>
        </Node>
        <Node name="Value">
          <Leaf name="T_NUMBER_VALUE" namespace="default" offset="1163">42</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="InputDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1175">AnnotatedInput</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1191">onInputObjectType</Leaf>
        </Node>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1215">annotatedField</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1231">Type</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1237">onField</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="ExtendDefinition">
      <Node name="ObjectDefinition">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1260">Foo</Leaf>
        </Node>
        <Node name="Field">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1270">seven</Leaf>
          </Node>
          <Node name="Argument">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="1276">argument</Leaf>
            </Node>
            <Node name="List">
              <Node name="Type">
                <Leaf name="T_NAME" namespace="default" offset="1287">String</Leaf>
              </Node>
            </Node>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="1297">Type</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="ExtendDefinition">
      <Node name="ObjectDefinition">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1317">Foo</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1322">onType</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1338">NoFields</Leaf>
      </Node>
    </Node>
    <Node name="DirectiveDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1362">skip</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1367">if</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1371">Boolean</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="1378">!</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1384">FIELD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1392">FRAGMENT_SPREAD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1410">INLINE_FRAGMENT</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="DirectiveDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1438">include</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1446">if</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1450">Boolean</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="1457">!</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1463">FIELD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1475">FRAGMENT_SPREAD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1497">INLINE_FRAGMENT</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="DirectiveDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1525">include2</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1534">if</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1538">Boolean</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="1545">!</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1557">FIELD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1569">FRAGMENT_SPREAD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1591">INLINE_FRAGMENT</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
