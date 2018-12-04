Feature:
  In order to prove that the Behat Symfony extension is correctly installed
  As a user
  I want to have a demo scenario

  Scenario: It receives a response from Symfony's kernel
    When a request is sent to "/"
    Then a response should be received

  @end-to-end
  Scenario: It gets all existing users
    When a request asks for the list of users
    Then all the users should be retrieved
