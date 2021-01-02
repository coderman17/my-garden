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
	When I call 'POST' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 201 Created'

Scenario Outline: Create a garden without a parameter
	Given I remove '<parameter>' from the root of the request body
	When I call 'POST' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

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
	When I call 'POST' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

	Examples:
	| parameter		| value	|
	| name			| 50	|
	| dimensionX		| "a"	|
	| dimensionY		| "a"	|
	| plantLocations	| 50	|

Scenario Outline: Create a garden with strings of boundary correct/incorrect length
	Given I upsert to the root of the request body, a string of key '<key>' and length '<length>'
	When I call 'POST' 'http://localhost/api/garden'
	Then the response has a status of '<status>'

	Examples:
	| key			| length	| status			|
	| name			| 0		| HTTP/1.1 400 Bad Request	|
	| name			| 1		| HTTP/1.1 201 Created		|
	| name			| 80		| HTTP/1.1 201 Created		|
	| name			| 81		| HTTP/1.1 400 Bad Request	|

Scenario Outline: Create a garden with integers of boundary correct/incorrect length
	Given I upsert to the root of the request body, an int of key '<key>' and value '<value>'
	When I call 'POST' 'http://localhost/api/garden'
	Then the response has a status of '<status>'

	Examples:
	| key		| value	| status			|
	| dimensionX	| 0	| HTTP/1.1 400 Bad Request	|
	| dimensionX	| 1	| HTTP/1.1 201 Created		|
	| dimensionX	| 10	| HTTP/1.1 201 Created		|
	| dimensionX	| 11	| HTTP/1.1 400 Bad Request	|
	| dimensionY	| 0	| HTTP/1.1 400 Bad Request	|
	| dimensionY	| 1	| HTTP/1.1 201 Created		|
	| dimensionY	| 10	| HTTP/1.1 201 Created		|
	| dimensionY	| 11	| HTTP/1.1 400 Bad Request	|
