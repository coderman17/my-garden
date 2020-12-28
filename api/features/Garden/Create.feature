Feature: Create a Garden
As a user
I need to create a garden
So that I can plan it

Background: A valid request body
	Given I have a request body:
	"""
	{
		"name": "test",
		"x_dimension": 8,
		"y_dimension": 8
	}
	"""

Scenario: Create a garden with a valid request body
	When I call 'POST' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 201 Created'

Scenario Outline: Create a garden without a parameter
	When I remove '<parameter>' from the root of the request body
	And I call 'POST' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

	Examples:
	| parameter		|
	| name			|
	| x_dimension	|
	| y_dimension	|

Scenario Outline: Create a garden with a value of incorrect type
	When I upsert to the root of the request body:
	"""
	{
		"<parameter>": <value>
	}
	"""
	And I call 'POST' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

	Examples:
	| parameter		| value	|
	| name			| 50	|
	| x_dimension	| "a"	|
	| y_dimension	| "a"	|

Scenario Outline: Create a garden with strings of boundary correct/incorrect length
	When I upsert to the root of the request body, a string of key '<key>' and length '<length>'
	And I call 'POST' 'http://localhost/api/garden'
	Then the response has a status of '<status>'

	Examples:
		| key			| length	| status					|
		| name			| 0			| HTTP/1.1 400 Bad Request	|
		| name			| 1			| HTTP/1.1 201 Created		|
		| name			| 80		| HTTP/1.1 201 Created		|
		| name			| 81		| HTTP/1.1 400 Bad Request	|

Scenario Outline: Create a garden with integers of boundary correct/incorrect length
	When I upsert to the root of the request body, an integer of key '<key>' and value '<value>'
	And I call 'POST' 'http://localhost/api/garden'
	Then the response has a status of '<status>'

	Examples:
		| key			| value		| status					|
		| x_dimension	| 0			| HTTP/1.1 400 Bad Request	|
		| x_dimension	| 1			| HTTP/1.1 201 Created		|
		| x_dimension	| 10		| HTTP/1.1 201 Created		|
		| x_dimension	| 11		| HTTP/1.1 400 Bad Request	|
		| y_dimension	| 0			| HTTP/1.1 400 Bad Request	|
		| y_dimension	| 1			| HTTP/1.1 201 Created		|
		| y_dimension	| 10		| HTTP/1.1 201 Created		|
		| y_dimension	| 11		| HTTP/1.1 400 Bad Request	|
