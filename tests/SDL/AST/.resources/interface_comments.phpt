--TEST--

Interface with comments test

--FILE--

#
# Skip
#

# DocBlock: interface
interface Example {
    # DocBlock: a
    a: TestQuery # Skip

    # DocBlock: b
    # DocBlock2: b
    b: TestMutation # Skip
    # Skip
    # Skip
} # Skip

--EXPECTF--

<Ast>
  <Node name="Document">
    <Node name="InterfaceDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="44">Example</Leaf>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="76">a</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="79">TestQuery</Leaf>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="138">b</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="141">TestMutation</Leaf>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
