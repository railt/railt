--TEST--

Multiple type definitions

--FILE--

type A implements A & B @DirectiveA(key: value) {
    id: ID! @isUnique
}

type B {
    id: ID @isUnique
}

--EXPECTF--

<Ast>
  <Node name="Document">
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="5">A</Leaf>
      </Node>
      <Node name="Implements">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="18">A</Leaf>
        </Node>
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="22">B</Leaf>
        </Node>
      </Node>
      <Node name="Directive">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="25">DirectiveA</Leaf>
        </Node>
        <Node name="Argument">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="36">key</Leaf>
          </Node>
          <Node name="Value">
            <Leaf name="T_NAME" namespace="default" offset="41">value</Leaf>
          </Node>
        </Node>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="54">id</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="58">ID</Leaf>
          <Leaf name="T_NON_NULL" namespace="default" offset="60">!</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="63">isUnique</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
    <Node name="ObjectDefinition">
      <Node name="Name">
        <Leaf name="T_NAME" namespace="default" offset="80">B</Leaf>
      </Node>
      <Node name="Field">
        <Node name="Name">
          <Leaf name="T_NAME" namespace="default" offset="88">id</Leaf>
        </Node>
        <Node name="Type">
          <Leaf name="T_NAME" namespace="default" offset="92">ID</Leaf>
        </Node>
        <Node name="Directive">
          <Node name="Name">
            <Leaf name="T_NAME" namespace="default" offset="96">isUnique</Leaf>
          </Node>
        </Node>
      </Node>
    </Node>
  </Node>
</Ast>
