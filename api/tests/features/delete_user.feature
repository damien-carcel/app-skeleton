Feature:
  In order to manage users
  As an administrator
  I want to be able to delete a user

  Background:
    Given I am logged as an administrator

  @end-to-end
  Scenario: I can delete a user
    When I delete a user
    Then the user is deleted

  Scenario: I cannot delete a user that does not exist
    When I try to delete a user that does not exist
    Then I got nothing to delete
