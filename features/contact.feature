Feature: Test contact page layout
  Scenario: Title should be present
    When I am on "/contact"
    Then I should see an "h2" element
    And the "h2" element should contain "Contact"
