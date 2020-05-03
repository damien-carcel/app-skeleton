Feature:
  In order to manage users
  As an administrator
  I want to be able to create new users

  Background:
    Given I am logged as an administrator

  @end-to-end
  Scenario: I can create a new user
    When I create a new user
    Then a new user is created

  @end-to-end
  Scenario: I cannot create a user with invalid data
    When I try to create a user with invalid data
    Then I cannot create this user

  Scenario: I cannot create a user if its email is already used by another user
    When I try to create a user with an email already used by another user
    Then I cannot create this user
