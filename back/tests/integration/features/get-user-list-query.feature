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
      | 08acf31d-2e62-44e9-ba18-fd160ac125ad |
      | 1605a575-77e5-4427-bbdb-2ebcb8cc8033 |
      | 22cd05c9-622d-4dcb-8837-1975e8c08812 |
      | 2a2a63c2-f01a-4b28-b52b-922bd6a170f5 |
      | 3553b4cf-49ab-4dd6-ba6e-e09b5b96115c |
      | 5eefa64f-0800-4fe2-b86f-f3d96bf7d602 |
      | 7f57d041-a612-4a5a-a61a-e0c96b2c576e |
      | 9f9e9cd2-88bb-438f-b825-b9610c6ee3f4 |
      | d24b8b4a-2476-48f7-b865-ee5318d845f3 |

  Scenario: It gets a limited list of users
    Given users are loaded in database
    When 2 users are queried starting page 1
    Then the following user list should be retrieved:
      | user_id                              |
      | 02432f0b-c33e-4d71-8ba9-a5e3267a45d5 |
      | 08acf31d-2e62-44e9-ba18-fd160ac125ad |

  Scenario: It gets a list of one user starting a certain page
    Given users are loaded in database
    When 1 user is queried starting page 2
    Then the following user list should be retrieved:
      | user_id                              |
      | 08acf31d-2e62-44e9-ba18-fd160ac125ad |

  Scenario: It gets a list of users starting a certain page
    Given users are loaded in database
    When 5 users are queried starting page 2
    Then the following user list should be retrieved:
      | user_id                              |
      | 3553b4cf-49ab-4dd6-ba6e-e09b5b96115c |
      | 5eefa64f-0800-4fe2-b86f-f3d96bf7d602 |
      | 7f57d041-a612-4a5a-a61a-e0c96b2c576e |
      | 9f9e9cd2-88bb-438f-b825-b9610c6ee3f4 |
      | d24b8b4a-2476-48f7-b865-ee5318d845f3 |

  Scenario: It gets an empty list of users if the page is to high
    Given users are loaded in database
    When 10 users are queried starting page 3
    Then the retrieved user list should be empty
