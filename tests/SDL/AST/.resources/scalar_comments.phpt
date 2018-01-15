--TEST--

Scalar with comments test

--FILE--

#
# Skip
#

# DocBlock: Test
scalar Test

# DocBlock: Test2
# DocBlock2:Test2
scalar Test2 @Directive # Skip
# Skip
# Skip

--EXPECTF--

<Ast>
  <Node name="Document">
    <Node name="ScalarDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="36">Test</Leaf>
      </Node>
    </Node>
    <Node name="ScalarDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="85">Test2</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="92">Directive</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
