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
	Given I call 'GET' 'http://localhost/api/plant?id=439583'
	Then the response should have a status of 'HTTP/1.1 404 Not Found'

Scenario: Get a plant which exists
	Given I call 'POST' 'http://localhost/api/plant'
	And I save 'id' from the response
	When I expect the same as the request body but with the saved 'id'
	And I call 'GET' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response body should be as expected

Scenario: Get plants
	Given I call 'POST' 'http://localhost/api/plant'
	And I save 'id' from the response
	Given I call 'GET' 'http://localhost/api/plants'
	When I expect the same as the request body but with the saved 'id'
	Then the response body should contain what is expected
