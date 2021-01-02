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
	When I call 'DELETE' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 204 No Content'
	When I call 'GET' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 404 Not Found'

Scenario: Delete a plant which doesn't exist
	Given I generate and save a random 'id'
	When I call 'DELETE' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 404 Not Found'

Scenario: Delete a plant without an id
	When I call 'DELETE' 'http://localhost/api/plant'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

Scenario: Delete a plant with an id of incorrect type
	Given I have a request body:
	"""
	{
		"id": 5
	}
	"""
	When I call 'DELETE' 'http://localhost/api/plant'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'
