Feature: Test API RPC services

Scenario: Sending GET request to non existing resource should lead to 404
  When I send a GET request to "foo"
  Then the response code should be 404

Scenario: Ping call should respond
  When I send a GET request to "api/ping"
  Then the response code should be 200
  And the response should be in JSON
  And the JSON node "ack" should exist