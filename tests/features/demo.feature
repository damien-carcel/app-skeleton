Feature:
  In order to prove that the Behat Symfony extension is correctly installed
  As a user
  I want to have a demo scenario

  @system
  Scenario: It receives a response from Symfony's kernel
    When a request is sent to "/"
    Then a response should be received
