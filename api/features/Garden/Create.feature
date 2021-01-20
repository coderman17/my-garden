Feature: Create a Garden
As a user
I need to create a garden
So that I can plan it

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

Scenario: Create a garden with a valid request body
	When I call 'POST' '/api/garden'
	Then the response has a status of '201'

Scenario Outline: Create a garden without a parameter
	Given I remove '<parameter>' from the root of the request body
	When I call 'POST' '/api/garden'
	Then the response has a status of '400'

	Examples:
		| parameter		|
		| name			|
		| dimensionX		|
		| dimensionY		|
		| plantLocations	|

Scenario Outline: Create a garden with a value of incorrect type
	Given I upsert to the root of the request body:
	"""
	{
		"<parameter>": <value>
	}
	"""
	When I call 'POST' '/api/garden'
	Then the response has a status of '400'

	Examples:
		| parameter		| value	|
		| name			| 50	|
		| dimensionX		| "a"	|
		| dimensionY		| "a"	|
		| plantLocations	| 50	|

Scenario Outline: Create a garden with strings of boundary correct/incorrect length
	Given I upsert to the root of the request body, a string of key '<key>' and length '<length>'
	When I call 'POST' '/api/garden'
	Then the response has a status of '<status>'

	Examples:
		| key			| length	| status			|
		| name			| 0		| 400	|
		| name			| 1		| 201		|
		| name			| 80		| 201		|
		| name			| 81		| 400	|

Scenario Outline: Create a garden with integers of boundary correct/incorrect length
	Given I upsert to the root of the request body, an int of key '<key>' and value '<value>'
	When I call 'POST' '/api/garden'
	Then the response has a status of '<status>'

	Examples:
		| key		| value	| status			|
		| dimensionX	| 0	| 400	|
		| dimensionX	| 1	| 201		|
		| dimensionX	| 10	| 201		|
		| dimensionX	| 11	| 400	|
		| dimensionY	| 0	| 400	|
		| dimensionY	| 1	| 201		|
		| dimensionY	| 10	| 201		|
		| dimensionY	| 11	| 400	|
