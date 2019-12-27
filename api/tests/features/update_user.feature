Feature:
  In order to manage users
  As an administrator
  I want to be able to update users' data

  Background:
    Given I am logged as an administrator

  @end-to-end
  Scenario: I can change all the data of a user
    When I change the data of an existing user
    Then this user has new email, first name and last name

  Scenario: I can change the email of a user
    When I change the email of an existing user
    Then this user has a new email

  Scenario: I can change the fist name of a user
    When I change the first name of an existing user
    Then this user has a new first name

  Scenario: I can change the last name of a user
    When I change the last name of an existing user
    Then this user has a new last name

  @end-to-end
  Scenario: I cannot change the data of a user with invalid ones
    When I try to change the data of an existing user with invalid ones
    Then I cannot change the user data

  @end-to-end
  Scenario: I cannot change the name of a user that does not exist
    When I try to change the name of a user that does not exist
    Then I got nothing to update
