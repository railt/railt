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
  <Node name="Document">
    <Node name="SchemaDefinition">
      <Leaf name="T_SCHEMA" namespace="default" offset="19">schema</Leaf>
      <Node name="Query">
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="102">QueryType</Leaf>
        </Node>
      </Node>
      <Node name="Mutation">
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="168">MutationType</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="SchemaDefinition">
      <Leaf name="T_SCHEMA" namespace="default" offset="232">schema</Leaf>
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="239">Example</Leaf>
      </Node>
      <Node name="Mutation">
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="305">MutationType</Leaf>
        </Node>
      </Node>
      <Node name="Query">
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="368">QueryType</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="436">Foo</Leaf>
      </Node>
      <Node name="Implements">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="451">Bar</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="524">one</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="529">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="577">two</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="581">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="591">InputType</Leaf>
            <Leaf name="T_NON_NULL" namespace="default" offset="600">!</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="604">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="654">three</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="660">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="670">InputType</Leaf>
          </Node>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="681">other</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="688">String</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="697">Int</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="745">four</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="750">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="760">String</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="default" offset="769">"string"</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="780">String</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="831">five</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="836">argument</Leaf>
          </Node>
          <Node name="List">
            <Node name="Type">
              <Leaf name="T_NAME" namespace="default" offset="847">String</Leaf>
            </Node>
          </Node>
          <Node name="Value">
            <Node name="List">
              <Node name="Value">
                <Leaf name="T_STRING" namespace="default" offset="858">"string"</Leaf>
              </Node>
              <Node name="Value">
                <Leaf name="T_STRING" namespace="default" offset="868">"string"</Leaf>
              </Node>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="880">String</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="930">six</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="934">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="944">InputType</Leaf>
          </Node>
          <Node name="Value">
            <Node name="Object">
              <Node name="ObjectPair">
                <Node name="Name">
                  <Leaf name="T_NAME" namespace="default" offset="966">key</Leaf>
                </Node>
                <Node name="Value">
                  <Leaf name="T_STRING" namespace="default" offset="971">"value"</Leaf>
                </Node>
              </Node>
              <Node name="ObjectPair">
                <Node name="Name">
                  <Leaf name="T_NAME" namespace="default" offset="988">key2</Leaf>
                </Node>
                <Node name="Value">
                  <Node name="List">
                    <Node name="Value">
                      <Leaf name="T_STRING" namespace="default" offset="995">"value1"</Leaf>
                    </Node>
                    <Node name="Value">
                      <Leaf name="T_STRING" namespace="default" offset="1005">"value2"</Leaf>
                    </Node>
                  </Node>
                </Node>
              </Node>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1023">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1073">seven</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1079">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="1089">Int</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_NULL" namespace="default" offset="1095">null</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1102">Type</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1177">AnnotatedObject</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1194">onObject</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1203">arg</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="default" offset="1208">"value"</Leaf>
          </Node>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1217">arg2</Leaf>
          </Node>
          <Node name="Value">
            <Node name="List">
              <Node name="Value">
                <Leaf name="T_NAME" namespace="default" offset="1224">Relation</Leaf>
              </Node>
            </Node>
          </Node>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1303">annotatedField</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1318">arg</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="1323">Type</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="default" offset="1330">"default"</Leaf>
          </Node>
          <Node name="Directive">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="1341">onArg</Leaf>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1349">Type</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1355">onField</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="InterfaceDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1431">Bar</Leaf>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1514">one</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1519">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1573">four</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1578">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="1588">String</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="default" offset="1597">"string"</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1608">String</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="InterfaceDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1698">AnnotatedInterface</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1718">onInterface</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1806">annotatedField</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1821">arg</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="1826">Type</Leaf>
          </Node>
          <Node name="Directive">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="1832">onArg</Leaf>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1840">Type</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1846">onField</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="UnionDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1915">Feed</Leaf>
      </Node>
      <Node name="Relations">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1922">Story</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1930">Article</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1940">Advert</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="UnionDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1996">AnnotatedUnion</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2012">onUnion</Leaf>
        </Node>
      </Node>
      <Node name="Relations">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2022">A</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2026">B</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="UnionDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="2080">AnnotatedUnionTwo</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2099">onUnion</Leaf>
        </Node>
      </Node>
      <Node name="Relations">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2111">A</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2115">B</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="ScalarDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="2166">CustomScalar</Leaf>
      </Node>
    </Node>
    <Node name="ScalarDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="2231">AnnotatedScalar</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2248">onScalar</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="EnumDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="2294">Site</Leaf>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2349">DESKTOP</Leaf>
        </Node>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2404">MOBILE</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="EnumDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="2469">AnnotatedEnum</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2484">onEnum</Leaf>
        </Node>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2558">ANNOTATED_VALUE</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="2575">onEnumValue</Leaf>
          </Node>
        </Node>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2648">OTHER_VALUE</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="InputDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="2726">InputType</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2788">key</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="2793">String</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="2799">!</Leaf>
        </Node>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2854">answer</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="2862">Int</Leaf>
        </Node>
        <Node name="Value">
          <Leaf name="T_NUMBER_VALUE" namespace="default" offset="2868">42</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="InputDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="2942">AnnotatedInput</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2958">onInputObjectType</Leaf>
        </Node>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3044">annotatedField</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="3060">Type</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="3066">onField</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="ExtendDefinition">
      <Node name="ObjectDefinition">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3146">Foo</Leaf>
        </Node>
        <Node name="Field">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="3204">seven</Leaf>
          </Node>
          <Node name="Argument">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="3210">argument</Leaf>
            </Node>
            <Node name="List">
              <Node name="Type">
                <Leaf name="T_NAME" namespace="default" offset="3221">String</Leaf>
              </Node>
            </Node>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="3231">Type</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="ExtendDefinition">
      <Node name="ObjectDefinition">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3308">Foo</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="3313">onType</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="3385">NoFields</Leaf>
      </Node>
    </Node>
    <Node name="DirectiveDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="3467">skip</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3472">if</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="3476">Boolean</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="3483">!</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3503">FIELD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3511">FRAGMENT_SPREAD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3529">INLINE_FRAGMENT</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="DirectiveDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="3607">include</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3615">if</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="3619">Boolean</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="3626">!</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3646">FIELD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3654">FRAGMENT_SPREAD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3672">INLINE_FRAGMENT</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="DirectiveDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="3731">include2</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3740">if</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="3744">Boolean</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="3751">!</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3773">FIELD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3785">FRAGMENT_SPREAD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3817">INLINE_FRAGMENT</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
