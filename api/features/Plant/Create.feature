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
	| englishName	| 50	|
	| latinName		| 50	|
	| imageLink		| 50	|

Scenario Outline: Create a plant with strings of boundary correct/incorrect length
	When I upsert to the root of the payload the key of '<key>' with a string value of '<length>' characters
	And I call 'POST' 'http://localhost/api/plant'
	Then the response should have a status of '<status>'

	Examples:
		| key			| length	| status					|
		| englishName	| 0			| HTTP/1.1 400 Bad Request	|
		| englishName	| 1			| HTTP/1.1 201 Created		|
		| englishName	| 80		| HTTP/1.1 201 Created		|
		| englishName	| 81		| HTTP/1.1 400 Bad Request	|
		| latinName		| 255		| HTTP/1.1 201 Created		|
		| latinName		| 256		| HTTP/1.1 400 Bad Request	|
		| imageLink		| 500		| HTTP/1.1 201 Created		|
		| imageLink		| 501		| HTTP/1.1 400 Bad Request	|
