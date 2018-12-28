Feature:
  In order to manage users
  As an administrator
  I want to display, edit and remove other users

  Background:
    Given I am logged as an administrator

  @end-to-end
  Scenario: I can list all existing users
    When I ask for the list of the users
    Then all the users should be retrieved
