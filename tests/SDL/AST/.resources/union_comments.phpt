--TEST--

Union with comments test

--FILE--

#
# Skip
#

# DocBlock: Feed
union Feed = Story | Article | Advert # Skip

# DocBlock: AnnotatedUnion
union AnnotatedUnion @onUnion = A | B # Skip

# DocBlock: AnnotatedUnionTwo
union AnnotatedUnionTwo @onUnion = | A | B # Skip
#
# Skip

--EXPECTF--

<Ast>
  <Rule name="Document">
    <Rule name="UnionDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="35">Feed</Leaf>
      </Rule>
      <Rule name="Relations">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="42">Story</Leaf>
        </Rule>
        <Rule name="Name">
          <Leaf name="T_NAME" offset="50">Article</Leaf>
        </Rule>
        <Rule name="Name">
          <Leaf name="T_NAME" offset="60">Advert</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="UnionDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="108">AnnotatedUnion</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="124">onUnion</Leaf>
        </Rule>
      </Rule>
      <Rule name="Relations">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="134">A</Leaf>
        </Rule>
        <Rule name="Name">
          <Leaf name="T_NAME" offset="138">B</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="UnionDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="184">AnnotatedUnionTwo</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="203">onUnion</Leaf>
        </Rule>
      </Rule>
      <Rule name="Relations">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="215">A</Leaf>
        </Rule>
        <Rule name="Name">
          <Leaf name="T_NAME" offset="219">B</Leaf>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
