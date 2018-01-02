Feature: Test about page layout
  Scenario: Title should be present
    When I am on "/about"
    Then I should see an "h4" element
    And the "h4" element should contain "About me"
