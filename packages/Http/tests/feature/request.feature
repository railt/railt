
Feature: Tests verifying the correctness of the GraphQL request

    Scenario: Default request
         When create request
         Then request query is empty
          And request variables has been defined
          And request operation name is empty

    Scenario: Request with query
        Given GraphQL query "{ query }"
         When create request
         Then request query is "{ query }"
          And request variables has been defined
          And request operation name is empty

    Scenario: Request with variables
        Given GraphQL variables:
              | a | {"example": 42} | object |
              | b | 23              | int    |
         When create request
         Then request query is empty
          And request contains two variables
          And where variable "a.example" contains int "42"
          And where variable "b" is int "23"
          And request operation name is empty

    Scenario: Request with operation
        Given GraphQL operation "test"
         When create request
         Then request query is empty
          And request variables has been defined
          And request operation name is "test"
