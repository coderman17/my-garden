Feature: Read Plants
As a user
I need to see my plants
So that I can review my collection

Background: A valid request body
	Given I have a request body:
	"""
	{
		"englishName": "test",
		"latinName": "test in latin",
		"imageLink": "www..."
	}
	"""

Scenario: Get a plant which doesn't exist
	Given I generate and save a random 'id'
	When I call 'GET' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 404 Not Found'

Scenario: Get a plant which exists
	Given I call 'POST' 'http://localhost/api/plant'
		And I save 'id' from the response
		And I expect the same as the request body but with the saved 'id'
	When I call 'GET' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response body should be as expected

Scenario: Get plants
	Given I call 'POST' 'http://localhost/api/plant'
		And I save 'id' from the response
		And I expect the same as the request body but with the saved 'id'
	When I call 'GET' 'http://localhost/api/plants'
	Then the response body should contain what is expected

Scenario: Get a plant without an id
	When I call 'GET' 'http://localhost/api/plant'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

Scenario: Get a plant with an id of incorrect type
	Given I have a request body:
	"""
	{
		"id": 5
	}
	"""
	When I call 'GET' 'http://localhost/api/plant'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'
