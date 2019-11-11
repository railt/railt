
@validation @type-uniqueness
Feature: GraphQL type name uniqueness checks

    # --------------------------------------------------------------------------
    #   Type definitions conflict checks with directive definitions
    # --------------------------------------------------------------------------

    Scenario: directive + enum
         When I define the schema:
              """
                directive @Example on FIELD_DEFINITION
                enum Example {}
              """
         Then document contains one enum definition
          And one directive definition

    Scenario: directive + input
         When I define the schema:
              """
                directive @Example on FIELD_DEFINITION
                input Example {}
              """
         Then document contains one input object definition
          And one directive definition

    Scenario: directive + interface
         When I define the schema:
              """
                directive @Example on FIELD_DEFINITION
                interface Example {}
              """
         Then document contains one interface definition
          And one directive definition

    Scenario: directive + type
         When I define the schema:
              """
                directive @Example on FIELD_DEFINITION
                type Example {}
              """
         Then document contains one object definition
          And one directive definition

    Scenario: directive + scalar
         When I define the schema:
              """
                directive @Example on FIELD_DEFINITION
                scalar Example
              """
         Then document contains one scalar definition
          And one directive definition

    Scenario: directive + union
         When I define the schema:
              """
                directive @Example on FIELD_DEFINITION
                union Example = Example
              """
         Then document contains one union definition
          And one directive definition

    # --------------------------------------------------------------------------
    # Check that the same types with the same names cause errors
    # --------------------------------------------------------------------------

    Scenario: enum + enum
         When I define the schema:
              """
                enum Example
                enum Example
              """
         Then GraphQL type error exception occurs
          And error message is: There can be only one type named "Example"
          But no document exists

    Scenario: directive + directive
         When I define the schema:
              """
                directive @example on FIELD_DEFINITION
                directive @example on FIELD_DEFINITION
              """
         Then GraphQL type error exception occurs
          And error message is: There can be only one directive named "@example"
          But no document exists

    Scenario: input + input
         When I define the schema:
              """
                input Example
                input Example
              """
         Then GraphQL type error exception occurs
          And error message is: There can be only one type named "Example"
          But no document exists

    Scenario: interface + interface
         When I define the schema:
              """
                interface Example
                interface Example
              """
         Then GraphQL type error exception occurs
          And error message is: There can be only one type named "Example"
          But no document exists

    Scenario: type + type
         When I define the schema:
              """
                type Example
                type Example
              """
         Then GraphQL type error exception occurs
          And error message is: There can be only one type named "Example"
          But no document exists

    Scenario: scalar + scalar
         When I define the schema:
              """
                scalar Example
                scalar Example
              """
         Then GraphQL type error exception occurs
          And error message is: There can be only one type named "Example"
          But no document exists

    Scenario: union + union
         When I define the schema:
              """
                union Example = Example
                union Example = Example
              """
         Then GraphQL type error exception occurs
          And error message is: There can be only one type named "Example"
          But no document exists

    Scenario: schema + schema
         When I define the schema:
              """
                schema { }
                schema { }
              """
         Then error Railt\SDL\Exception\TypeErrorException occurs
          And error message is: Schema definition must be unique
          But no document exists
