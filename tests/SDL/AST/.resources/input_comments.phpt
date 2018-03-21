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
  <Rule name="Document">
    <Rule name="InputDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="40">InputType</Leaf>
      </Rule>
      <Rule name="Argument">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="97">key</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="102">String</Leaf>
          <Leaf name="T_NON_NULL" offset="108">!</Leaf>
        </Rule>
      </Rule>
      <Rule name="Argument">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="165">answer</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="173">Int</Leaf>
        </Rule>
        <Rule name="Value">
          <Leaf name="T_NUMBER_VALUE" offset="179">42</Leaf>
        </Rule>
      </Rule>
    </Rule>
    <Rule name="InputDefinition">
      <Rule name="Name">
        <Leaf name="T_NAME" offset="236">AnnotatedInput</Leaf>
      </Rule>
      <Rule name="Directive">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="252">onInputObjectType</Leaf>
        </Rule>
      </Rule>
      <Rule name="Argument">
        <Rule name="Name">
          <Leaf name="T_NAME" offset="339">annotatedField</Leaf>
        </Rule>
        <Rule name="Type">
          <Leaf name="T_NAME" offset="355">Type</Leaf>
        </Rule>
        <Rule name="Directive">
          <Rule name="Name">
            <Leaf name="T_NAME" offset="361">onField</Leaf>
          </Rule>
        </Rule>
      </Rule>
    </Rule>
  </Rule>
</Ast>
