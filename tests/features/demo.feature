Feature:
  In order to prove that the Behat Symfony extension is correctly installed
  As a user
  I want to have a demo scenario

  Scenario: It receives a response from Symfony's kernel
    When a request is sent to "/"
    Then a response should be received

  @system
  Scenario: It gets all existing blog posts
    When a request asks for the list of blog posts
    Then all the blog posts should be retrieved
