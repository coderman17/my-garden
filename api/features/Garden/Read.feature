Feature: Read Gardens
As a user
I need to see my gardens
So that I can review my collection

Background: A valid request body
	Given I have a request body:
	"""
	{
		"name": "test",
		"x_dimension": 8,
		"y_dimension": 8
	}
	"""

Scenario: Get a garden which doesn't exist
	Given I call 'GET' 'http://localhost/api/garden?id=439583'
	Then the response should have a status of 'HTTP/1.1 404 Not Found'

Scenario: Get a garden which exists
	Given I call 'POST' 'http://localhost/api/garden'
	And I save 'id' from the response
	When I expect the same as the request body but with the saved 'id'
	And I call 'GET' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response body should be as expected

Scenario: Get gardens
	Given I call 'POST' 'http://localhost/api/garden'
	And I save 'id' from the response
	Given I call 'GET' 'http://localhost/api/gardens'
	When I expect the same as the request body but with the saved 'id'
	Then the response body should contain what is expected
