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
schema { # COMMENT
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
      <Node name="Mutation">
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="297">MutationType</Leaf>
        </Node>
      </Node>
      <Node name="Query">
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="360">QueryType</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="428">Foo</Leaf>
      </Node>
      <Node name="Implements">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="443">Bar</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="516">one</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="521">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="569">two</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="573">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="583">InputType</Leaf>
            <Leaf name="T_NON_NULL" namespace="default" offset="592">!</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="596">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="646">three</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="652">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="662">InputType</Leaf>
          </Node>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="673">other</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="680">String</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="689">Int</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="737">four</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="742">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="752">String</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="string" offset="762">string</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="772">String</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="823">five</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="828">argument</Leaf>
          </Node>
          <Node name="List">
            <Node name="Type">
              <Leaf name="T_NAME" namespace="default" offset="839">String</Leaf>
            </Node>
          </Node>
          <Node name="Value">
            <Node name="List">
              <Node name="Value">
                <Leaf name="T_STRING" namespace="string" offset="851">string</Leaf>
              </Node>
              <Node name="Value">
                <Leaf name="T_STRING" namespace="string" offset="861">string</Leaf>
              </Node>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="872">String</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="922">six</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="926">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="936">InputType</Leaf>
          </Node>
          <Node name="Value">
            <Node name="Object">
              <Node name="ObjectPair">
                <Node name="Name">
                  <Leaf name="T_NAME" namespace="default" offset="958">key</Leaf>
                </Node>
                <Node name="Value">
                  <Leaf name="T_STRING" namespace="string" offset="964">value</Leaf>
                </Node>
              </Node>
              <Node name="ObjectPair">
                <Node name="Name">
                  <Leaf name="T_NAME" namespace="default" offset="980">key2</Leaf>
                </Node>
                <Node name="Value">
                  <Node name="List">
                    <Node name="Value">
                      <Leaf name="T_STRING" namespace="string" offset="988">value1</Leaf>
                    </Node>
                    <Node name="Value">
                      <Leaf name="T_STRING" namespace="string" offset="998">value2</Leaf>
                    </Node>
                  </Node>
                </Node>
              </Node>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1015">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1065">seven</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1071">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="1081">Int</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_NULL" namespace="default" offset="1087">null</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1094">Type</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1169">AnnotatedObject</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1186">onObject</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1195">arg</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="string" offset="1201">value</Leaf>
          </Node>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1209">arg2</Leaf>
          </Node>
          <Node name="Value">
            <Node name="List">
              <Node name="Value">
                <Leaf name="T_NAME" namespace="default" offset="1216">Relation</Leaf>
              </Node>
            </Node>
          </Node>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1295">annotatedField</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1310">arg</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="1315">Type</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="string" offset="1323">default</Leaf>
          </Node>
          <Node name="Directive">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="1333">onArg</Leaf>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1341">Type</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1347">onField</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="InterfaceDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1423">Bar</Leaf>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1506">one</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1511">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1565">four</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1570">argument</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="1580">String</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_STRING" namespace="string" offset="1590">string</Leaf>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1600">String</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="InterfaceDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1690">AnnotatedInterface</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1710">onInterface</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1798">annotatedField</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1813">arg</Leaf>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="1818">Type</Leaf>
          </Node>
          <Node name="Directive">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="1824">onArg</Leaf>
            </Node>
          </Node>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="1832">Type</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="1838">onField</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="UnionDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1907">Feed</Leaf>
      </Node>
      <Node name="Relations">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1914">Story</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1922">Article</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="1932">Advert</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="UnionDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="1988">AnnotatedUnion</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2004">onUnion</Leaf>
        </Node>
      </Node>
      <Node name="Relations">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2014">A</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2018">B</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="UnionDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="2072">AnnotatedUnionTwo</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2091">onUnion</Leaf>
        </Node>
      </Node>
      <Node name="Relations">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2103">A</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2107">B</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="ScalarDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="2158">CustomScalar</Leaf>
      </Node>
    </Node>
    <Node name="ScalarDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="2223">AnnotatedScalar</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2240">onScalar</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="EnumDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="2286">Site</Leaf>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2341">DESKTOP</Leaf>
        </Node>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2396">MOBILE</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="EnumDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="2461">AnnotatedEnum</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2476">onEnum</Leaf>
        </Node>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2550">ANNOTATED_VALUE</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="2567">onEnumValue</Leaf>
          </Node>
        </Node>
      </Node>
      <Node name="Value">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2640">OTHER_VALUE</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="InputDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="2718">InputType</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2780">key</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="2785">String</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="2791">!</Leaf>
        </Node>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2846">answer</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="2854">Int</Leaf>
        </Node>
        <Node name="Value">
          <Leaf name="T_NUMBER_VALUE" namespace="default" offset="2860">42</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="InputDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="2934">AnnotatedInput</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="2950">onInputObjectType</Leaf>
        </Node>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3036">annotatedField</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="3052">Type</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="3058">onField</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="ExtendDefinition">
      <Node name="ObjectDefinition">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3138">Foo</Leaf>
        </Node>
        <Node name="Field">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="3196">seven</Leaf>
          </Node>
          <Node name="Argument">
            <Node name="Name">
              <Leaf name="T_NAME" namespace="default" offset="3202">argument</Leaf>
            </Node>
            <Node name="List">
              <Node name="Type">
                <Leaf name="T_NAME" namespace="default" offset="3213">String</Leaf>
              </Node>
            </Node>
          </Node>
          <Node name="Type">
            <Leaf name="T_NAME" namespace="default" offset="3223">Type</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="ExtendDefinition">
      <Node name="ObjectDefinition">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3300">Foo</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="3305">onType</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="3377">NoFields</Leaf>
      </Node>
    </Node>
    <Node name="DirectiveDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="3459">skip</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3464">if</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="3468">Boolean</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="3475">!</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3495">FIELD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3503">FRAGMENT_SPREAD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3521">INLINE_FRAGMENT</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="DirectiveDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="3599">include</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3607">if</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="3611">Boolean</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="3618">!</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3638">FIELD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3646">FRAGMENT_SPREAD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3664">INLINE_FRAGMENT</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="DirectiveDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="3723">include2</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3732">if</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="3736">Boolean</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="3743">!</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3765">FIELD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3777">FRAGMENT_SPREAD</Leaf>
        </Node>
      </Node>
      <Node name="Target">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="3809">INLINE_FRAGMENT</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
