Feature: Update Plant
As a user
I need to update plants
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

@updatePlant

Scenario: Update a plant which exists
	Given I call 'POST' 'http://localhost/api/plant'
	And I save 'id' from the response
	And I call 'GET' 'http://localhost/api/plant?id=' appending the saved 'id'
	And the response should have a status of 'HTTP/1.1 200 OK'
	When I have a request body:
	"""
	{
		"englishName": "updated",
		"latinName": "updated in latin",
		"imageLink": "updated..."
	}
	"""
	When I call 'PUT' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response should have a status of 'HTTP/1.1 200 OK'
	When I expect the request body as the response body with the saved 'id'
	And I call 'GET' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response body should be as expected