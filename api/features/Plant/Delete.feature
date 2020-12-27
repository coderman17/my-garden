Feature: Delete Plant
As a user
I need to delete plants
So that I can manage my collection

Background: A valid request body
	Given I have a request body:
	"""
	{
		"englishName": "test",
		"latinName": "test in latin",
		"imageLink": "www..."
	}
	"""

Scenario: Delete a plant which exists
	Given I call 'POST' 'http://localhost/api/plant'
	And I save 'id' from the response
	And I call 'GET' 'http://localhost/api/plant?id=' appending the saved 'id'
	And the response should have a status of 'HTTP/1.1 200 OK'
	When I call 'DELETE' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response should have a status of 'HTTP/1.1 204 No Content'
	And I call 'GET' 'http://localhost/api/plant?id=' appending the saved 'id'
	And the response should have a status of 'HTTP/1.1 404 Not Found'

Scenario: Delete a plant which doesn't exist
	Given I call 'GET' 'http://localhost/api/plant?id=439583'
	And the response should have a status of 'HTTP/1.1 404 Not Found'
	When I call 'DELETE' 'http://localhost/api/plant?id=439583'
	Then the response should have a status of 'HTTP/1.1 404 Not Found'
