Feature:
  In order to prove that the GetUserList query works
  As an integration test
  I want to execute queries

  Scenario: It gets a list of users
    Given users are loaded in database
    When 10 users are queried starting page 1
    Then the following user list should be retrieved:
      | user_id                              |
      | 02432f0b-c33e-4d71-8ba9-a5e3267a45d5 |
      | 7f57d041-a612-4a5a-a61a-e0c96b2c576e |
      | fff8bb6d-5772-4e6c-9d10-41d522683264 |

  Scenario: It gets a limited list of users
    Given users are loaded in database
    When 2 users are queried starting page 1
    Then the following user list should be retrieved:
      | user_id                              |
      | 02432f0b-c33e-4d71-8ba9-a5e3267a45d5 |
      | 7f57d041-a612-4a5a-a61a-e0c96b2c576e |

  Scenario: It gets a list of one user starting a certain page
    Given users are loaded in database
    When 1 user is queried starting page 2
    Then the following user list should be retrieved:
      | user_id                              |
      | 7f57d041-a612-4a5a-a61a-e0c96b2c576e |

  Scenario: It gets a list of users starting a certain page
    Given users are loaded in database
    When 2 users are queried starting page 2
    Then the following user list should be retrieved:
      | user_id                              |
      | fff8bb6d-5772-4e6c-9d10-41d522683264 |

  Scenario: It gets an empty list of users if the page is to high
    Given users are loaded in database
    When 10 users are queried starting page 2
    Then the retrieved user list should be empty
