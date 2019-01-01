Feature:
  In order to manage users
  As an administrator
  I want to list users

  Background:
    Given I am logged as an administrator

  @end-to-end
  Scenario: I can list the first ten users
    When I ask for the 1st page of 10 users
    Then the 1st 10 users should be retrieved

  Scenario: I can list the second ten users
    When I ask for the 2st page of 10 users
    Then the 2nd 10 users should be retrieved
