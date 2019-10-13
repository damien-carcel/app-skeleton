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
