Feature: Delete Garden
As a user
I need to delete my garden
So that I can start again from scratch

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

Scenario: Delete a garden which exists
	Given I call 'POST' 'http://localhost/api/garden'
		And I save 'id' from the response
	When I call 'DELETE' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 204 No Content'
	When I call 'GET' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 404 Not Found'

Scenario: Delete a garden which doesn't exist
	When I call 'DELETE' 'http://localhost/api/garden?id=1fb93313436cb'
	Then the response has a status of 'HTTP/1.1 404 Not Found'

Scenario: Delete a garden without an id
	When I call 'DELETE' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

Scenario: Delete a garden with an id of incorrect type
	Given I have a request body:
	"""
	{
		"id": 5
	}
	"""
	When I call 'DELETE' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'
