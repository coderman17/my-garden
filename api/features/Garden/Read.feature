Feature: Read Gardens
As a user
I need to see my gardens
So that I can review my collection

Background: A valid request body
	Given I have a request body:
	"""
	{
		"name": "test",
		"dimensionX": 8,
		"dimensionY": 8,
		"plantLocations": []
	}
	"""

Scenario: Get a garden which doesn't exist
	Given I call 'GET' 'http://localhost/api/garden?id=1fb93313436cb'
	Then the response has a status of 'HTTP/1.1 404 Not Found'

Scenario: Get a garden which exists
	Given I call 'POST' 'http://localhost/api/garden'
		And I save 'id' from the response
		And I expect the same as the request body but with the saved 'id'
	When I call 'GET' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response body should be as expected

Scenario: Get gardens
	Given I call 'POST' 'http://localhost/api/garden'
		And I save 'id' from the response
		And I expect the same as the request body but with the saved 'id'
	When I call 'GET' 'http://localhost/api/gardens'
	Then the response body should contain what is expected

Scenario: Get a garden without an id
	When I call 'GET' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

Scenario: Get a garden with an id of incorrect type
	Given I have a request body:
	"""
	{
		"id": 5
	}
	"""
	When I call 'GET' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'
