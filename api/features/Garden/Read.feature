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
	Given I generate and save a random 'id'
	When I call 'GET' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '404'

Scenario: Get a garden which exists
	Given I call 'POST' '/api/garden'
		And I save 'id' from the response
		And I expect the same as the request body but with the saved 'id'
	When I call 'GET' '/api/garden?id=' appending the saved 'id'
	Then the response body should be as expected

Scenario: Get gardens
	Given I call 'POST' '/api/garden'
		And I save 'id' from the response
		And I expect the same as the request body but with the saved 'id'
	When I call 'GET' '/api/gardens'
	Then the response body should contain what is expected

Scenario: Get a garden without an id
	When I call 'GET' '/api/garden'
	Then the response has a status of '400'

Scenario: Get a garden with an id of incorrect type
	Given I have a request body:
	"""
	{
		"id": 5
	}
	"""
	When I call 'GET' '/api/garden'
	Then the response has a status of '400'
