//
// Common definitions and partials
//
%include definitions/common/documentation.pp
%include definitions/common/return-type-definition.pp
%include definitions/common/implements.pp
%include definitions/common/generics.pp
%include definitions/common/arguments.pp
%include definitions/common/fields.pp

//
// TypeDefinitions
//
%include definitions/module.pp
%include definitions/schema.pp
%include definitions/object.pp
%include definitions/interface.pp
%include definitions/scalar.pp
%include definitions/enum.pp
%include definitions/union.pp
%include definitions/input.pp
%include definitions/directive.pp


Definition:
    SchemaDefinition()     |
    ScalarDefinition()     |
    ObjectDefinition()     |
    InterfaceDefinition()  |
    EnumDefinition()       |
    UnionDefinition()      |
    DirectiveDefinition()  |
    InputDefinition()
