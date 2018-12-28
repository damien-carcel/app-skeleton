Feature:
  In order to prove that the Doctrine repository work
  As an integration test
  I want execute queries

  Scenario: It gets all users from the database
    Given users are loaded in database
    When the method "findAll" from the Doctrine UserRepository is called
    Then all the users should be retrieved from database

  Scenario: It gets a user from the database
    Given users are loaded in database
    When the method "find" from the Doctrine UserRepository is called with argument "02432f0b-c33e-4d71-8ba9-a5e3267a45d5"
    Then the user with ID "02432f0b-c33e-4d71-8ba9-a5e3267a45d5" should be retrieved from database

  Scenario: It saves a user in database
    Given no user is loaded in database
    When the user "02432f0b-c33e-4d71-8ba9-a5e3267a45d5" is saved
    Then there should be 1 user in database

  Scenario: It removes the user with ID "02432f0b-c33e-4d71-8ba9-a5e3267a45d5" from the database
    Given users are loaded in database
    When the user "02432f0b-c33e-4d71-8ba9-a5e3267a45d5" is removed
    Then there should be 2 users in database
