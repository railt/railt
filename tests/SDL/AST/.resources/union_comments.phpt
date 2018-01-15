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
  <Node name="Document">
    <Node name="UnionDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="35">Feed</Leaf>
      </Node>
      <Node name="Relations">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="42">Story</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="50">Article</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="60">Advert</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="UnionDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="108">AnnotatedUnion</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="124">onUnion</Leaf>
        </Node>
      </Node>
      <Node name="Relations">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="134">A</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="138">B</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="UnionDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="184">AnnotatedUnionTwo</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="203">onUnion</Leaf>
        </Node>
      </Node>
      <Node name="Relations">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="215">A</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="219">B</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
