Feature: Delete Garden
As a user
I need to delete my garden
So that I can start again from scratch

Background: A valid request body
	Given I have a request body:
	"""
	{
		"name": "test",
		"x_dimension": 8,
		"y_dimension": 8
	}
	"""

Scenario: Delete a plant which exists
	Given I call 'POST' 'http://localhost/api/garden'
	And I save 'id' from the response
	And I call 'GET' 'http://localhost/api/garden?id=' appending the saved 'id'
	And the response should have a status of 'HTTP/1.1 200 OK'
	When I call 'DELETE' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response should have a status of 'HTTP/1.1 204 No Content'
	And I call 'GET' 'http://localhost/api/garden?id=' appending the saved 'id'
	And the response should have a status of 'HTTP/1.1 404 Not Found'

Scenario: Delete a garden which doesn't exist
	Given I call 'GET' 'http://localhost/api/garden?id=439583'
	And the response should have a status of 'HTTP/1.1 404 Not Found'
	When I call 'DELETE' 'http://localhost/api/garden?id=439583'
	Then the response should have a status of 'HTTP/1.1 404 Not Found'
