Feature:
  In order to manage users
  As an administrator
  I want to get a specific user

  Background:
    Given I am logged as an administrator

  @end-to-end
  Scenario: I can get a specific user
    When I ask for a specific user
    Then the specified user should be retrieved

  Scenario: I cannot get a user that does not exist
    When I ask for a user that does not exist
    Then I got no user
