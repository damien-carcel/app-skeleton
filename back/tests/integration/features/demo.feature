Feature:
  In order to prove that the Doctrine repository work
  As a integration test
  I want execute queries

  Scenario: It gets all users from the database
    When the "findAll" method from the Doctrine UserRepository is called
    Then all the users should be retrieved from database
