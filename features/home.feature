Feature: Test home page layout
  Scenario: Title should be present
    When I am on the homepage
    Then I should see an "h1" element
    And the "h1" element should contain "Hi there"
