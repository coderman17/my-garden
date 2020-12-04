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

Scenario Outline: Create a plant without a parameter
	When I remove '<parameter>' from the root of the payload
	And I call 'POST' 'http://localhost/api/plant'
	Then the response should have a status of 'HTTP/1.1 400 Bad Request'

	Examples:
	| parameter		|
	| englishName	|
	| latinName		|
	| imageLink		|

Scenario Outline: Create a plant with a value of incorrect type
	When I upsert to the root of the payload:
	"""
	{
		"<parameter>": <value>
	}
	"""
	And I call 'POST' 'http://localhost/api/plant'
	Then the response should have a status of 'HTTP/1.1 400 Bad Request'

	Examples:
	| parameter		| value	|
	| englishName	| 5		|
	| latinName		| 5		|
	| imageLink		| 5		|
