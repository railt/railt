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
  <Rule name="Document">
    <Rule name="SchemaDefinition">
      <Leaf name="T_SCHEMA" offset="0">schema</Leaf>
      <Rule name="Query">
        <Rule name="Type">
          <Leaf name="T_NAME" offset="20">QueryType</Leaf>
        </Rule>
      </Rule>
      <Rule name="Mutation">
        <Rule name="Type">
          <Leaf name="T_NAME" offset="44">MutationType</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="SchemaDefinition">
      <Leaf name="T_SCHEMA" offset="60">schema</Leaf>
      <Rule name="Name">
        <Leaf name="T_NAME" offset="67">Example</Leaf>
      </Rule>
      <Rule name="Mutation">
        <Rule name="Type">
          <Leaf name="T_NAME" offset="91">MutationType</Leaf>
        </Rule>
      </Rule>
      <Rule name="Query">
        <Rule name="Type">
          <Leaf name="T_NAME" offset="115">QueryType</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="133">Foo</Leaf>
      </Rule>
      <Rule name="Implements">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="148">Bar</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="158">one</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="163">Type</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="172">two</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="176">argument</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="186">InputType</Leaf>
            <Leaf name="T_NON_NULL" offset="195">!</Leaf>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="199">Type</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="208">three</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="214">argument</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="224">InputType</Leaf>
          </Rule>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="235">other</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="242">String</Leaf>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="251">Int</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="259">four</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="264">argument</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="274">String</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_STRING" offset="283">"string"</Leaf>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="294">String</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="305">five</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="310">argument</Leaf>
          </Rule>
          <Rule name="List">
            <Rule name="Type">
              <Leaf name="T_NAME" offset="321">String</Leaf>
            </Rule>
          </Rule>
          <Rule name="Value">
            <Rule name="List">
              <Rule name="Value">
                <Leaf name="T_STRING" offset="332">"string"</Leaf>
              </Rule>
              <Rule name="Value">
                <Leaf name="T_STRING" offset="342">"string"</Leaf>
              </Rule>
            </Rule>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="354">String</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="365">six</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="369">argument</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="379">InputType</Leaf>
          </Rule>
          <Rule name="Value">
            <Rule name="Object">
              <Rule name="ObjectPair">
                <Rule name="Name">
                  <Leaf name="T_NAME" offset="401">key</Leaf>
                </Rule>
                <Rule name="Value">
                  <Leaf name="T_STRING" offset="406">"value"</Leaf>
                </Rule>
              </Rule>
              <Rule name="ObjectPair">
                <Rule name="Name">
                  <Leaf name="T_NAME" offset="423">key2</Leaf>
                </Rule>
                <Rule name="Value">
                  <Rule name="List">
                    <Rule name="Value">
                      <Leaf name="T_STRING" offset="430">"value1"</Leaf>
                    </Rule>
                    <Rule name="Value">
                      <Leaf name="T_STRING" offset="440">"value2"</Leaf>
                    </Rule>
                  </Rule>
                </Rule>
              </Rule>
            </Rule>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="458">Type</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="467">seven</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="473">argument</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="483">Int</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_NULL" offset="489">null</Leaf>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="496">Type</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="509">AnnotatedObject</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="526">onObject</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="535">arg</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_STRING" offset="540">"value"</Leaf>
          </Rule>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="549">arg2</Leaf>
          </Rule>
          <Rule name="Value">
            <Rule name="List">
              <Rule name="Value">
                <Leaf name="T_NAME" offset="556">Relation</Leaf>
              </Rule>
            </Rule>
          </Rule>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="573">annotatedField</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="588">arg</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="593">Type</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_STRING" offset="600">"default"</Leaf>
          </Rule>
          <Rule name="Directive">
            <Rule name="Name">
              <Leaf name="T_NAME" offset="611">onArg</Leaf>
            </Rule>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="619">Type</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="625">onField</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="InterfaceDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="646">Bar</Leaf>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="656">one</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="661">Type</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="670">four</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="675">argument</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="685">String</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_STRING" offset="694">"string"</Leaf>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="705">String</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="InterfaceDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="725">AnnotatedInterface</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="745">onInterface</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="763">annotatedField</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="778">arg</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="783">Type</Leaf>
          </Rule>
          <Rule name="Directive">
            <Rule name="Name">
              <Leaf name="T_NAME" offset="789">onArg</Leaf>
            </Rule>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="797">Type</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="803">onField</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="UnionDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="820">Feed</Leaf>
      </Rule>
      <Rule name="Relations">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="827">Story</Leaf>
        </Rule>
        <Rule name="Name">
          <Leaf name="T_NAME" offset="835">Article</Leaf>
        </Rule>
        <Rule name="Name">
          <Leaf name="T_NAME" offset="845">Advert</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="UnionDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="859">AnnotatedUnion</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="875">onUnion</Leaf>
        </Rule>
      </Rule>
      <Rule name="Relations">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="885">A</Leaf>
        </Rule>
        <Rule name="Name">
          <Leaf name="T_NAME" offset="889">B</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="UnionDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="898">AnnotatedUnionTwo</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="917">onUnion</Leaf>
        </Rule>
      </Rule>
      <Rule name="Relations">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="929">A</Leaf>
        </Rule>
        <Rule name="Name">
          <Leaf name="T_NAME" offset="933">B</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="ScalarDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="943">CustomScalar</Leaf>
      </Rule>
    </Rule>
    <Rule name="ScalarDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="964">AnnotatedScalar</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="981">onScalar</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="EnumDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="996">Site</Leaf>
      </Rule>
      <Rule name="Value">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1007">DESKTOP</Leaf>
        </Rule>
      </Rule>
      <Rule name="Value">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1019">MOBILE</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="EnumDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="1034">AnnotatedEnum</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1049">onEnum</Leaf>
        </Rule>
      </Rule>
      <Rule name="Value">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1062">ANNOTATED_VALUE</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="1079">onEnumValue</Leaf>
          </Rule>
        </Rule>
      </Rule>
      <Rule name="Value">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1095">OTHER_VALUE</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="InputDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="1116">InputType</Leaf>
      </Rule>
      <Rule name="Argument">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1132">key</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="1137">String</Leaf>
          <Leaf name="T_NON_NULL" offset="1143">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Argument">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1149">answer</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="1157">Int</Leaf>
        </Rule>
        <Rule name="Value">
          <Leaf name="T_NUMBER_VALUE" offset="1163">42</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="InputDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="1175">AnnotatedInput</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1191">onInputObjectType</Leaf>
        </Rule>
      </Rule>
      <Rule name="Argument">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1215">annotatedField</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="1231">Type</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="1237">onField</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="ExtendDefinition">
      <Rule name="ObjectDefinition">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1260">Foo</Leaf>
        </Rule>
        <Rule name="Field">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="1270">seven</Leaf>
          </Rule>
          <Rule name="Argument">
            <Rule name="Name">
              <Leaf name="T_NAME" offset="1276">argument</Leaf>
            </Rule>
            <Rule name="List">
              <Rule name="Type">
                <Leaf name="T_NAME" offset="1287">String</Leaf>
              </Rule>
            </Rule>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="1297">Type</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="ExtendDefinition">
      <Rule name="ObjectDefinition">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1317">Foo</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="1322">onType</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="1338">NoFields</Leaf>
      </Rule>
    </Rule>
    <Rule name="DirectiveDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="1362">skip</Leaf>
      </Rule>
      <Rule name="Argument">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1367">if</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="1371">Boolean</Leaf>
          <Leaf name="T_NON_NULL" offset="1378">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1384">FIELD</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1392">FRAGMENT_SPREAD</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1410">INLINE_FRAGMENT</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="DirectiveDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="1438">include</Leaf>
      </Rule>
      <Rule name="Argument">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1446">if</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="1450">Boolean</Leaf>
          <Leaf name="T_NON_NULL" offset="1457">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1463">FIELD</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1475">FRAGMENT_SPREAD</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1497">INLINE_FRAGMENT</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="DirectiveDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="1525">include2</Leaf>
      </Rule>
      <Rule name="Argument">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1534">if</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="1538">Boolean</Leaf>
          <Leaf name="T_NON_NULL" offset="1545">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1557">FIELD</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1569">FRAGMENT_SPREAD</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1591">INLINE_FRAGMENT</Leaf>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
