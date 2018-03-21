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
  <Rule name="Document">
    <Rule name="ObjectDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="5">A</Leaf>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="13">ID</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="17">ID</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="24">Int</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="29">Int</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="37">Bool</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="43">Bool</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="52">Float</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="59">Float</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="69">String</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="77">String</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NULL" offset="90">null</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="96">Null</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_BOOL_TRUE" offset="105">true</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="111">True</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_BOOL_FALSE" offset="120">false</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="127">False</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_ON" offset="137">on</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="141">On</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_TYPE" offset="148">type</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="154">Type</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_ENUM" offset="163">enum</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="169">Enum</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_UNION" offset="178">union</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="185">Union</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_INTERFACE" offset="195">interface</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="206">Interface</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_TYPE_IMPLEMENTS" offset="220">implements</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="232">Implements</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_SCHEMA" offset="247">schema</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="255">Schema</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_SCHEMA_QUERY" offset="266">query</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="273">Query</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_SCHEMA_MUTATION" offset="283">mutation</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="293">Mutation</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_SCALAR" offset="306">scalar</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="314">Scalar</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_DIRECTIVE" offset="325">directive</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="336">Directive</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_INPUT" offset="350">input</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="357">Input</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_EXTEND" offset="367">extend</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="375">Extend</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="388">aa</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="392">aa</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="399">bb</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="403">bb</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="410">cc</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="414">cc</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="421">dd</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="425">dd</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="432">ee</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="436">ee</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="443">ff</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="447">ff</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="454">gg</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="458">gg</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="465">hh</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="469">hh</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="476">ii</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="480">ii</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="487">jj</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="491">jj</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="498">kk</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="502">kk</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="509">ll</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="513">ll</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="520">mm</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="524">mm</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="531">nn</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="535">nn</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="542">oo</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="546">oo</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="553">pp</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="557">pp</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="564">qq</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="568">qq</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="575">rr</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="579">rr</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="586">ss</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="590">ss</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="597">tt</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="601">tt</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="608">uu</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="612">uu</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="619">vv</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="623">vv</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="630">ww</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="634">ww</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="641">xx</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="645">xx</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="652">yy</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="656">yy</Leaf>
        </Rule>
      </Rule>
      <Rule name="Field">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="663">zz</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="667">zz</Leaf>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
