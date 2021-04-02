Feature: Create Plant
As a user
I need to create plants
So that I can add to my collection

Background: A valid request body
	Given I have a request body:
	"""
	{
		"englishName": "test",
		"latinName": "test in latin",
		"imageLink": "www..."
	}
	"""

Scenario: Create a plant with a valid request body
	When I call 'POST' '/api/plant'
	Then the response has a status of '201'

Scenario Outline: Create a plant without a parameter
	Given I remove '<parameter>' from the root of the request body
	When I call 'POST' '/api/plant'
	Then the response has a status of '400'

	Examples:
	| parameter	|
	| englishName	|
	| latinName	|
	| imageLink	|

Scenario Outline: Create a plant with a value of incorrect type
	Given I upsert to the root of the request body:
	"""
	{
		"<parameter>": <value>
	}
	"""
	When I call 'POST' '/api/plant'
	Then the response has a status of '400'

	Examples:
	| parameter	| value	|
	| englishName	| 50	|
	| latinName	| 50	|
	| imageLink	| 50	|

Scenario Outline: Create a plant with strings of boundary correct/incorrect length
	Given I upsert to the root of the request body, a string of key '<key>' and length '<length>'
	When I call 'POST' '/api/plant'
	Then the response has a status of '<status>'

	Examples:
		| key		| length	| status	|
		| englishName	| 0		| 400		|
		| englishName	| 1		| 201		|
		| englishName	| 80		| 201		|
		| englishName	| 81		| 400		|
		| latinName	| 255		| 201		|
		| latinName	| 256		| 400		|
		| imageLink	| 500		| 201		|
		| imageLink	| 501		| 400		|
