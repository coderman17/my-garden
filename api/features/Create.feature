Feature: Create Plant
As a user
I need to create plants
So that I can add to my collection

Background: A valid payload
	Given I have a valid payload:
	"""
	{
		"englishName": "test",
		"latinName": "test in latin",
		"imageLink": "www..."
	}
	"""

Scenario: Create a plant with a valid payload
	When I call 'POST' 'http://localhost/api/plant'
	Then the response should have a status of 'HTTP/1.1 201 Created'

Scenario: Create a plant without an englishName
	When I remove 'englishName' from the root of the payload
	And I call 'POST' 'http://localhost/api/plant'
	Then the response should have a status of 'HTTP/1.1 400 Bad Request'

Scenario: Create a plant without a latinName
	When I remove 'latinName' from the root of the payload
	And I call 'POST' 'http://localhost/api/plant'
	Then the response should have a status of 'HTTP/1.1 400 Bad Request'

Scenario: Create a plant without an imageLink
	When I remove 'imageLink' from the root of the payload
	And I call 'POST' 'http://localhost/api/plant'
	Then the response should have a status of 'HTTP/1.1 400 Bad Request'
