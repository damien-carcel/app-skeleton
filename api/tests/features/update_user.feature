Feature:
  In order to manage users
  As an administrator
  I want to be able to update users' data

  Background:
    Given I am logged as an administrator

  @end-to-end
  Scenario: I can change the name of a user
    When I change the name of an existing user
    Then this user has a new name
