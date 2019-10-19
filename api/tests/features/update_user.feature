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

  Scenario: I can change the fist name of a user
    When I change the first name of an existing user
    Then this user has a new first name

  Scenario: I can change the last name of a user
    When I change the last name of an existing user
    Then this user has a new last name

  Scenario: I cannot change the name of a user that does not exist
    When I try to change the name of a user that does not exist
    Then I got nothing to update
