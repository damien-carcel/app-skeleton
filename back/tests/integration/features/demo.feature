Feature:
  In order to prove that the Doctrine repository work
  As a integration test
  I want execute queries

  Scenario: It gets all blog posts from the database
    When the "getAllBlogPosts" method from the Doctrine BlogPostRepository is called
    Then all the blog posts should be retrieved from database
