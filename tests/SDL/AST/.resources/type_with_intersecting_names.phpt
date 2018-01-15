--TEST--

Type name definition named like keyword will not throws an error.

--FILE--

type A {
    ID: ID
    Int: Int
    Bool: Bool
    Float: Float
    String: String


    null: Null
    true: True
    false: False
    on: On
    type: Type
    enum: Enum
    union: Union
    interface: Interface
    implements: Implements
    schema: Schema
    query: Query
    mutation: Mutation
    scalar: Scalar
    directive: Directive
    input: Input
    extend: Extend


    aa: aa
    bb: bb
    cc: cc
    dd: dd
    ee: ee
    ff: ff
    gg: gg
    hh: hh
    ii: ii
    jj: jj
    kk: kk
    ll: ll
    mm: mm
    nn: nn
    oo: oo
    pp: pp
    qq: qq
    rr: rr
    ss: ss
    tt: tt
    uu: uu
    vv: vv
    ww: ww
    xx: xx
    yy: yy
    zz: zz
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
          <Leaf name="T_NAME" namespace="default" offset="13">ID</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="17">ID</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="24">Int</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="29">Int</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="37">Bool</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="43">Bool</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="52">Float</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="59">Float</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="69">String</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="77">String</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NULL" namespace="default" offset="90">null</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="96">Null</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_BOOL_TRUE" namespace="default" offset="105">true</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="111">True</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_BOOL_FALSE" namespace="default" offset="120">false</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="127">False</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_ON" namespace="default" offset="137">on</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="141">On</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_TYPE" namespace="default" offset="148">type</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="154">Type</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_ENUM" namespace="default" offset="163">enum</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="169">Enum</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_UNION" namespace="default" offset="178">union</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="185">Union</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_INTERFACE" namespace="default" offset="195">interface</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="206">Interface</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_TYPE_IMPLEMENTS" namespace="default" offset="220">implements</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="232">Implements</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_SCHEMA" namespace="default" offset="247">schema</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="255">Schema</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_SCHEMA_QUERY" namespace="default" offset="266">query</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="273">Query</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_SCHEMA_MUTATION" namespace="default" offset="283">mutation</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="293">Mutation</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_SCALAR" namespace="default" offset="306">scalar</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="314">Scalar</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_DIRECTIVE" namespace="default" offset="325">directive</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="336">Directive</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_INPUT" namespace="default" offset="350">input</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="357">Input</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_EXTEND" namespace="default" offset="367">extend</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="375">Extend</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="388">aa</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="392">aa</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="399">bb</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="403">bb</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="410">cc</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="414">cc</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="421">dd</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="425">dd</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="432">ee</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="436">ee</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="443">ff</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="447">ff</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="454">gg</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="458">gg</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="465">hh</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="469">hh</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="476">ii</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="480">ii</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="487">jj</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="491">jj</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="498">kk</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="502">kk</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="509">ll</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="513">ll</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="520">mm</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="524">mm</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="531">nn</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="535">nn</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="542">oo</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="546">oo</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="553">pp</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="557">pp</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="564">qq</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="568">qq</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="575">rr</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="579">rr</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="586">ss</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="590">ss</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="597">tt</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="601">tt</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="608">uu</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="612">uu</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="619">vv</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="623">vv</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="630">ww</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="634">ww</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="641">xx</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="645">xx</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="652">yy</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="656">yy</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="663">zz</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="667">zz</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
