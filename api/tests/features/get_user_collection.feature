Feature:
  In order to manage users
  As an administrator
  I want to get several users at once

  Background:
    Given I am logged as an administrator

  @end-to-end
  Scenario: I can get the first ten users
    When I ask for the 1st page of 10 users
    Then the 1st 10 users should be retrieved

  Scenario: I can list the second ten users
    When I ask for the 2nd page of 10 users
    Then the 2nd 10 users should be retrieved
