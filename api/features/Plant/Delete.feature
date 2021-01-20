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
	Given I call 'POST' '/api/plant'
		And I save 'id' from the response
	When I call 'DELETE' '/api/plant?id=' appending the saved 'id'
	Then the response has a status of '204'
	When I call 'GET' '/api/plant?id=' appending the saved 'id'
	Then the response has a status of '404'

Scenario: Delete a plant which doesn't exist
	Given I generate and save a random 'id'
	When I call 'DELETE' '/api/plant?id=' appending the saved 'id'
	Then the response has a status of '404'

Scenario: Delete a plant without an id
	When I call 'DELETE' '/api/plant'
	Then the response has a status of '400'

Scenario: Delete a plant with an id of incorrect type
	Given I have a request body:
	"""
	{
		"id": 5
	}
	"""
	When I call 'DELETE' '/api/plant'
	Then the response has a status of '400'
