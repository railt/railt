--TEST--

Complex type declarations with comments

--FILE--

# DocBlock: schema
schema {
    # DocBlock: schema { query }
# DocBlock2: schema { query }
    query: QueryType # COMMENT
# DocBlock: schema { mutation }
    mutation: MutationType # COMMENT
# COMMENT
} # COMMENT
# DocBlock: schema
schema Example { # COMMENT
# DocBlock: schema { mutation }
    mutation: MutationType # COMMENT
# DocBlock: schema { query }
    query: QueryType # COMMENT
# COMMENT
} # COMMENT
# DocBlock: type Foo
type Foo implements Bar {
    # DocBlock: type Foo { one }
# DocBlock2: type Foo { one }
    one: Type # COMMENT
# DocBlock: type Foo { two }
    two(argument: InputType!): Type # COMMENT
# DocBlock: type Foo { three }
    three(argument: InputType, other: String): Int # COMMENT
# DocBlock: type Foo { four }
    four(argument: String = "string"): String # COMMENT
# DocBlock: type Foo { five }
    five(argument: [String] = ["string", "string"]): String # COMMENT
# DocBlock: type Foo { six }
    six(argument: InputType = {
        key: "value",
        key2: ["value1", "value2"]
    }): Type # COMMENT
# DocBlock: type Foo { seven }
    seven(argument: Int = null): Type # COMMENT
# COMMENT
} # COMMENT
# DocBlock: type AnnotatedObject
type AnnotatedObject @onObject(arg: "value", arg2: [Relation]) { # COMMENT
# DocBlock: type AnnotatedObject { annotatedField }
    annotatedField(arg: Type = "default" @onArg): Type @onField # COMMENT
# COMMENT
} # COMMENT
# DocBlock: interface Bar
interface Bar {
    # DocBlock: interface Bar { one }
# DocBlock2: interface Bar { one }
    one: Type # COMMENT
# DocBlock: interface Bar { four }
    four(argument: String = "string"): String # COMMENT
# COMMENT
} # COMMENT
# DocBlock: interface AnnotatedInterface
interface AnnotatedInterface @onInterface { # COMMENT
# DocBlock: interface AnnotatedInterface { annotatedField }
    annotatedField(arg: Type @onArg): Type @onField # COMMENT
# COMMENT
} # COMMENT
# DocBlock: union Feed
union Feed = Story | Article | Advert # COMMENT
# DocBlock: union AnnotatedUnion
union AnnotatedUnion @onUnion = A | B # COMMENT
# DocBlock: union AnnotatedUnionTwo
union AnnotatedUnionTwo @onUnion = | A | B # COMMENT
# DocBlock: scalar CustomScalar
scalar CustomScalar # COMMENT
# DocBlock: scalar AnnotatedScalar
scalar AnnotatedScalar @onScalar # COMMENT
# DocBlock: enum Site
enum Site { # COMMENT
# DocBlock: enum Site { DESKTOP }
    DESKTOP # COMMENT
# DocBlock: enum Site { MOBILE }
    MOBILE # COMMENT
} # COMMENT
# DocBlock: enum AnnotatedEnum
enum AnnotatedEnum @onEnum { # COMMENT
# DocBlock: enum AnnotatedEnum { ANNOTATED_VALUE }
    ANNOTATED_VALUE @onEnumValue # COMMENT
# DocBlock: enum AnnotatedEnum { OTHER_VALUE }
    OTHER_VALUE # COMMENT
# COMMENT
} # COMMENT
# DocBlock: input InputType
input InputType { # COMMENT
# DocBlock: input InputType { key }
    key: String! # COMMENT
# DocBlock: input InputType { answer }
    answer: Int = 42 # COMMENT
# COMMENT
} # COMMENT
# DocBlock: input AnnotatedInput
input AnnotatedInput @onInputObjectType { # COMMENT
# DocBlock: input AnnotatedInput { annotatedField }
    annotatedField: Type @onField # COMMENT
# COMMENT
} # COMMENT
# DocBlock: extend type Foo
extend type Foo { # COMMENT
# DocBlock: extend type Foo { seven }
    seven(argument: [String]): Type # COMMENT
# COMMENT
} # COMMENT
# DocBlock: extend type Foo
extend type Foo @onType
{ # COMMENT
# COMMENT
} # COMMENT
# DocBlock: type NoFields
type NoFields { # COMMENT
# COMMENT
} # COMMENT
# DocBlock: directive @skip
directive @skip(if: Boolean!) # COMMENT
    on FIELD | FRAGMENT_SPREAD | INLINE_FRAGMENT # COMMENT
# COMMENT
# DocBlock: directive @include
directive @include(if: Boolean!) # COMMENT
    on FIELD | FRAGMENT_SPREAD | INLINE_FRAGMENT
# DocBlock: directive @include2
directive @include2(if: Boolean!) on # COMMENT
    | FIELD
    | FRAGMENT_SPREAD # COMMENT
    | INLINE_FRAGMENT
# COMMENT
# COMMENT

--EXPECTF--

<Ast>
  <Rule name="Document">
    <Rule name="SchemaDefinition">
      <Leaf name="T_SCHEMA" offset="19">schema</Leaf>
      <Rule name="Query">
        <Rule name="Type">
          <Leaf name="T_NAME" offset="102">QueryType</Leaf>
        </Rule>
      </Rule>
      <Rule name="Mutation">
        <Rule name="Type">
          <Leaf name="T_NAME" offset="168">MutationType</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="SchemaDefinition">
      <Leaf name="T_SCHEMA" offset="232">schema</Leaf>
      <Rule name="Name">
        <Leaf name="T_NAME" offset="239">Example</Leaf>
      </Rule>
      <Rule name="Mutation">
        <Rule name="Type">
          <Leaf name="T_NAME" offset="305">MutationType</Leaf>
        </Rule>
      </Rule>
      <Rule name="Query">
        <Rule name="Type">
          <Leaf name="T_NAME" offset="368">QueryType</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="436">Foo</Leaf>
      </Rule>
      <Rule name="Implements">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="451">Bar</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="524">one</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="529">Type</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="577">two</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="581">argument</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="591">InputType</Leaf>
            <Leaf name="T_NON_NULL" offset="600">!</Leaf>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="604">Type</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="654">three</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="660">argument</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="670">InputType</Leaf>
          </Rule>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="681">other</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="688">String</Leaf>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="697">Int</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="745">four</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="750">argument</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="760">String</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_STRING" offset="769">"string"</Leaf>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="780">String</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="831">five</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="836">argument</Leaf>
          </Rule>
          <Rule name="List">
            <Rule name="Type">
              <Leaf name="T_NAME" offset="847">String</Leaf>
            </Rule>
          </Rule>
          <Rule name="Value">
            <Rule name="List">
              <Rule name="Value">
                <Leaf name="T_STRING" offset="858">"string"</Leaf>
              </Rule>
              <Rule name="Value">
                <Leaf name="T_STRING" offset="868">"string"</Leaf>
              </Rule>
            </Rule>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="880">String</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="930">six</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="934">argument</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="944">InputType</Leaf>
          </Rule>
          <Rule name="Value">
            <Rule name="Object">
              <Rule name="ObjectPair">
                <Rule name="Name">
                  <Leaf name="T_NAME" offset="966">key</Leaf>
                </Rule>
                <Rule name="Value">
                  <Leaf name="T_STRING" offset="971">"value"</Leaf>
                </Rule>
              </Rule>
              <Rule name="ObjectPair">
                <Rule name="Name">
                  <Leaf name="T_NAME" offset="988">key2</Leaf>
                </Rule>
                <Rule name="Value">
                  <Rule name="List">
                    <Rule name="Value">
                      <Leaf name="T_STRING" offset="995">"value1"</Leaf>
                    </Rule>
                    <Rule name="Value">
                      <Leaf name="T_STRING" offset="1005">"value2"</Leaf>
                    </Rule>
                  </Rule>
                </Rule>
              </Rule>
            </Rule>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="1023">Type</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1073">seven</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="1079">argument</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="1089">Int</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_NULL" offset="1095">null</Leaf>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="1102">Type</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="1177">AnnotatedObject</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1194">onObject</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="1203">arg</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_STRING" offset="1208">"value"</Leaf>
          </Rule>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="1217">arg2</Leaf>
          </Rule>
          <Rule name="Value">
            <Rule name="List">
              <Rule name="Value">
                <Leaf name="T_NAME" offset="1224">Relation</Leaf>
              </Rule>
            </Rule>
          </Rule>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1303">annotatedField</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="1318">arg</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="1323">Type</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_STRING" offset="1330">"default"</Leaf>
          </Rule>
          <Rule name="Directive">
            <Rule name="Name">
              <Leaf name="T_NAME" offset="1341">onArg</Leaf>
            </Rule>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="1349">Type</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="1355">onField</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="InterfaceDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="1431">Bar</Leaf>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1514">one</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="1519">Type</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1573">four</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="1578">argument</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="1588">String</Leaf>
          </Rule>
          <Rule name="Value">
            <Leaf name="T_STRING" offset="1597">"string"</Leaf>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="1608">String</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="InterfaceDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="1698">AnnotatedInterface</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1718">onInterface</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1806">annotatedField</Leaf>
        </Rule>
        <Rule name="Argument">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="1821">arg</Leaf>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="1826">Type</Leaf>
          </Rule>
          <Rule name="Directive">
            <Rule name="Name">
              <Leaf name="T_NAME" offset="1832">onArg</Leaf>
            </Rule>
          </Rule>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="1840">Type</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="1846">onField</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="UnionDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="1915">Feed</Leaf>
      </Rule>
      <Rule name="Relations">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1922">Story</Leaf>
        </Rule>
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1930">Article</Leaf>
        </Rule>
        <Rule name="Name">
          <Leaf name="T_NAME" offset="1940">Advert</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="UnionDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="1996">AnnotatedUnion</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="2012">onUnion</Leaf>
        </Rule>
      </Rule>
      <Rule name="Relations">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="2022">A</Leaf>
        </Rule>
        <Rule name="Name">
          <Leaf name="T_NAME" offset="2026">B</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="UnionDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="2080">AnnotatedUnionTwo</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="2099">onUnion</Leaf>
        </Rule>
      </Rule>
      <Rule name="Relations">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="2111">A</Leaf>
        </Rule>
        <Rule name="Name">
          <Leaf name="T_NAME" offset="2115">B</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="ScalarDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="2166">CustomScalar</Leaf>
      </Rule>
    </Rule>
    <Rule name="ScalarDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="2231">AnnotatedScalar</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="2248">onScalar</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="EnumDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="2294">Site</Leaf>
      </Rule>
      <Rule name="Value">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="2349">DESKTOP</Leaf>
        </Rule>
      </Rule>
      <Rule name="Value">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="2404">MOBILE</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="EnumDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="2469">AnnotatedEnum</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="2484">onEnum</Leaf>
        </Rule>
      </Rule>
      <Rule name="Value">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="2558">ANNOTATED_VALUE</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="2575">onEnumValue</Leaf>
          </Rule>
        </Rule>
      </Rule>
      <Rule name="Value">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="2648">OTHER_VALUE</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="InputDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="2726">InputType</Leaf>
      </Rule>
      <Rule name="Argument">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="2788">key</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="2793">String</Leaf>
          <Leaf name="T_NON_NULL" offset="2799">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Argument">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="2854">answer</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="2862">Int</Leaf>
        </Rule>
        <Rule name="Value">
          <Leaf name="T_NUMBER_VALUE" offset="2868">42</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="InputDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="2942">AnnotatedInput</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="2958">onInputObjectType</Leaf>
        </Rule>
      </Rule>
      <Rule name="Argument">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="3044">annotatedField</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="3060">Type</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="3066">onField</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="ExtendDefinition">
      <Rule name="ObjectDefinition">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="3146">Foo</Leaf>
        </Rule>
        <Rule name="Field">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="3204">seven</Leaf>
          </Rule>
          <Rule name="Argument">
            <Rule name="Name">
              <Leaf name="T_NAME" offset="3210">argument</Leaf>
            </Rule>
            <Rule name="List">
              <Rule name="Type">
                <Leaf name="T_NAME" offset="3221">String</Leaf>
              </Rule>
            </Rule>
          </Rule>
          <Rule name="Type">
            <Leaf name="T_NAME" offset="3231">Type</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="ExtendDefinition">
      <Rule name="ObjectDefinition">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="3308">Foo</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="3313">onType</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="3385">NoFields</Leaf>
      </Rule>
    </Rule>
    <Rule name="DirectiveDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="3467">skip</Leaf>
      </Rule>
      <Rule name="Argument">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="3472">if</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="3476">Boolean</Leaf>
          <Leaf name="T_NON_NULL" offset="3483">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="3503">FIELD</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="3511">FRAGMENT_SPREAD</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="3529">INLINE_FRAGMENT</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="DirectiveDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="3607">include</Leaf>
      </Rule>
      <Rule name="Argument">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="3615">if</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="3619">Boolean</Leaf>
          <Leaf name="T_NON_NULL" offset="3626">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="3646">FIELD</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="3654">FRAGMENT_SPREAD</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="3672">INLINE_FRAGMENT</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="DirectiveDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="3731">include2</Leaf>
      </Rule>
      <Rule name="Argument">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="3740">if</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="3744">Boolean</Leaf>
          <Leaf name="T_NON_NULL" offset="3751">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="3773">FIELD</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="3785">FRAGMENT_SPREAD</Leaf>
        </Rule>
      </Rule>
      <Rule name="Target">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="3817">INLINE_FRAGMENT</Leaf>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
