Feature:
  In order to manage users
  As an administrator
  I want to get a specific user

  Background:
    Given I am logged as an administrator

  @end-to-end
  Scenario: I can list the first ten users
    When I ask for a specific user
    Then the specified user should be retrieved
