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
	Given I call 'POST' '/api/garden'
		And I save 'id' from the response
	When I call 'DELETE' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '204'
	When I call 'GET' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '404'

Scenario: Delete a garden which doesn't exist
	Given I generate and save a random 'id'
	When I call 'DELETE' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '404'

Scenario: Delete a garden without an id
	When I call 'DELETE' '/api/garden'
	Then the response has a status of '400'

Scenario: Delete a garden with an id of incorrect type
	Given I have a request body:
	"""
	{
		"id": 5
	}
	"""
	When I call 'DELETE' '/api/garden'
	Then the response has a status of '400'
