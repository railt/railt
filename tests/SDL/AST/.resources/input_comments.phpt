--TEST--

Input with comments test

--FILE--

#
# Skip
#

# DocBlock: InputType
input InputType {
    # DocBlock: key
    # DocBlock2: key
    key: String! # Skip

# DocBlock: answer
    # DocBlock2: answer
    answer: Int = 42 # Skip
    # Skip
}

# DocBlock: AnnotatedInput
input AnnotatedInput @onInputObjectType {
    # DocBlock: annotatedField
    # DocBlock2: annotatedField
    annotatedField: Type @onField # Skip
    # Skip
}

--EXPECTF--

<Ast>
  <Node name="Document">
    <Node name="InputDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="40">InputType</Leaf>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="97">key</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="102">String</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="108">!</Leaf>
        </Node>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="165">answer</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="173">Int</Leaf>
        </Node>
        <Node name="Value">
          <Leaf name="T_NUMBER_VALUE" namespace="default" offset="179">42</Leaf>
        </Node>
      </Node>
    </Node>
    <Node name="InputDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="236">AnnotatedInput</Leaf>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="252">onInputObjectType</Leaf>
        </Node>
      </Node>
      <Node name="Argument">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="339">annotatedField</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="355">Type</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="361">onField</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
