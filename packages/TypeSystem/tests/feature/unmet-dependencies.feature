
@validation @unmet-dependencies
Feature: GraphQL types dependency checks

    # --------------------------------------------------------------------------
    #   Directives
    # --------------------------------------------------------------------------

    Scenario: Directive definition + argument
         When I define the schema "directive @a(arg: Example) on FIELD_DEFINITION"
         Then GraphQL type not found exception occurs
          And error message is: Type "Example" not found or could not be loaded

    Scenario: Directive execution
         When I define the schema "@onDocument(arg: 42)"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onDocument" not found or could not be loaded

    # --------------------------------------------------------------------------
    #   Enums
    # --------------------------------------------------------------------------

    Scenario: Enum definition + directive
         When I define the schema "enum Example @onEnum(arg: 42) {}"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onEnum" not found or could not be loaded

    Scenario: Enum definition + enum value + directive
         When I define the schema "enum Example { VALUE @onEnumValue(arg: 42) }"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onEnumValue" not found or could not be loaded

    # --------------------------------------------------------------------------
    #   Input Objects
    # --------------------------------------------------------------------------

    Scenario: Input object + directive
         When I define the schema "input Example @onInputObject(arg: 42)"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onInputObject" not found or could not be loaded

    Scenario: Input object + argument
         When I define the schema "input Example { argument: InvalidType }"
         Then GraphQL type not found exception occurs
          And error message is: Type "InvalidType" not found or could not be loaded

    Scenario: Input object + argument + directive
         When I define the schema "input Example { argument: Example @onInputValue }"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onInputValue" not found or could not be loaded

    # --------------------------------------------------------------------------
    #   Interfaces
    # --------------------------------------------------------------------------

    Scenario: Interface + directive
         When I define the schema "interface Example @onInterface"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onInterface" not found or could not be loaded

    Scenario: Interface + field
         When I define the schema "interface Example { field: InvalidType }"
         Then GraphQL type not found exception occurs
          And error message is: Type "InvalidType" not found or could not be loaded

    Scenario: Interface + field + directive
         When I define the schema "interface Example { field: Example @onInterfaceField }"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onInterfaceField" not found or could not be loaded

    Scenario: Interface + field + argument
         When I define the schema "interface Example { field(arg: InvalidType): Example }"
         Then GraphQL type not found exception occurs
          And error message is: Type "InvalidType" not found or could not be loaded

    Scenario: Interface + field + argument + directive
         When I define the schema "interface Example { field(arg: Example @onArgumentOfFieldOfInterface): Example }"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onArgumentOfFieldOfInterface" not found or could not be loaded

    # --------------------------------------------------------------------------
    #   Objects
    # --------------------------------------------------------------------------

    Scenario: Object + directive
         When I define the schema "type Example @onObject"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onObject" not found or could not be loaded

    Scenario: Object + field
         When I define the schema "type Example { field: InvalidType }"
         Then GraphQL type not found exception occurs
          And error message is: Type "InvalidType" not found or could not be loaded

    Scenario: Object + field + directive
         When I define the schema "type Example { field: Example @onObjectField }"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onObjectField" not found or could not be loaded

    Scenario: Object + field + argument
         When I define the schema "type Example { field(arg: InvalidType): Example }"
         Then GraphQL type not found exception occurs
          And error message is: Type "InvalidType" not found or could not be loaded

    Scenario: Object + field + argument + directive
         When I define the schema "type Example { field(arg: Example @onArgumentOfFieldOfObject): Example }"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onArgumentOfFieldOfObject" not found or could not be loaded

    # --------------------------------------------------------------------------
    #   Scalars
    # --------------------------------------------------------------------------

    Scenario: Scalar + directive
         When I define the schema "scalar Example @onScalar"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onScalar" not found or could not be loaded
          And no document exists

    # --------------------------------------------------------------------------
    #   Schema
    # --------------------------------------------------------------------------

    Scenario: Schema + directive
         When I define the schema "schema @onSchema {}"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onSchema" not found or could not be loaded
          And no document exists

    Scenario: Schema + query
         When I define the schema "schema { query: InvalidType }"
         Then GraphQL type not found exception occurs
          And error message is: Type "InvalidType" not found or could not be loaded
          And no document exists

    Scenario: Schema + query + directive
         When I define the schema "type Query schema { query: Query @onQueryOperation }"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onQueryOperation" not found or could not be loaded
          And no document exists

    Scenario: Schema + mutation
         When I define the schema "schema { mutation: InvalidType }"
         Then GraphQL type not found exception occurs
          And error message is: Type "InvalidType" not found or could not be loaded
          And no document exists

    Scenario: Schema + mutation + directive
         When I define the schema "type Mutation schema { mutation: Mutation @onMutationOperation }"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onMutationOperation" not found or could not be loaded
          And no document exists

    Scenario: Schema + subscription
         When I define the schema "schema { subscription: InvalidType }"
         Then GraphQL type not found exception occurs
          And error message is: Type "InvalidType" not found or could not be loaded
          And no document exists

    Scenario: Schema + subscription + directive
         When I define the schema "type Subscription schema { subscription: Subscription @onSubscriptionOperation }"
         Then GraphQL type not found exception occurs
          And error message is: Directive "@onSubscriptionOperation" not found or could not be loaded
          And no document exists


