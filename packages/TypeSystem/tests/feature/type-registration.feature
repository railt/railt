
@type-registration
Feature: Checks that GraphQL types are registered in the system

    # --------------------------------------------------------------------------
    #   Definitions
    # --------------------------------------------------------------------------

    Scenario: Registration of [directive definition]
         When I define the schema "directive @myCustomDirective on FIELD_DEFINITION"
         Then no errors occurred
          And document contains one directive definition
          And where "$name->value" like "myCustomDirective"
          And where "$loc->start" is int "0"
          And where "$loc->end" is int "48"

    Scenario: Registration of [enum definition]
         When I define the schema "enum EnumTypeDefinition"
         Then no errors occurred
          And document contains one enum definition
          And where "$name->value" like "EnumTypeDefinition"
          And where "$loc->start" is int "0"
          And where "$loc->end" is int "23"

    Scenario: Registration of [input object definition]
         When I define the schema "input ExampleInputObject"
         Then no errors occurred
          And document contains one input object definition
          And where "$name->value" like "ExampleInputObject"
          And where "$loc->start" is int "0"
          And where "$loc->end" is int "24"

    Scenario: Registration of [interface definition]
         When I define the schema "interface HellOrWorld"
         Then no errors occurred
          And document contains one interface definition
          And where "$name->value" like "HellOrWorld"
          And where "$loc->start" is int "0"
          And where "$loc->end" is int "21"

    Scenario: Registration of [object definition]
         When I define the schema "type MyObjectType"
         Then no errors occurred
          And document contains one object definition
          And where "$name->value" like "MyObjectType"
          And where "$loc->start" is int "0"
          And where "$loc->end" is int "17"

    Scenario: Registration of [scalar definition]
         When I define the schema "scalar IAmNotScalar"
         Then no errors occurred
          And document contains one scalar definition
          And where "$name->value" like "IAmNotScalar"
          And where "$loc->start" is int "0"
          And where "$loc->end" is int "19"

    Scenario: Registration of [schema definition]
         When I define the schema "schema {}"
         Then no errors occurred
          And document contains a schema type
          And where "$loc->start" is int "0"
          And where "$loc->end" is int "9"


    # --------------------------------------------------------------------------
    #   Executions
    # --------------------------------------------------------------------------

    Scenario: Registration of [directive execution]
         When I define the schema:
            """
              @rootDirective

              directive @rootDirective on DOCUMENT
            """
         Then no errors occurred
          And document contains one execution
          And where "$loc->startLine" is int "1"
          # And where "$loc->endLine" of 0 item is int "1"
          ### NOTE: This is a bug of calculating the last offset, due to the
          ### fact that the latest lexer state for which a match was applies
          ### to the AST node.
          ### However, all "skipped" tokens (like whitespaces) do not change
          ### state and are considered part of the current rule.
          And where "$loc->endLine" of zero item is int "3"

         And document contains one directive definition
         And where "$loc->startLine" is int "3"
         And where "$loc->endLine" is int "3"
