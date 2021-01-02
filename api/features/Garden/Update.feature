Feature: Update garden
As a user
I need to update gardens
So that I can manage my collection

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
		And I call 'POST' 'http://localhost/api/garden'
		And I save 'id' from the response

Scenario: Update a garden which exists
	Given I have a request body:
	"""
	{
		"name": "updated test",
		"dimensionX": 1,
		"dimensionY": 1,
		"plantLocations": []
	}
	"""
	When I call 'PUT' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 200 OK'
		And I expect the same as the request body but with the saved 'id'
	When I call 'GET' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response body should be as expected

Scenario: Update a garden but without altering it
	Given I expect the same as the request body but with the saved 'id'
	When I call 'PUT' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 200 OK'
		And the response body should be as expected

Scenario: Update a garden which doesn't exist
	Given I generate and save a random 'id'
		And I expect the same as the request body but with the saved 'id'
	When I call 'PUT' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 201 Created'
		And the response body should be as expected

Scenario Outline: Update a garden without a parameter
	Given I remove '<parameter>' from the root of the request body
	When I call 'PUT' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

	Examples:
		| parameter	|
		| name		|
		| dimensionX	|
		| dimensionY	|
		| plantLocations|

Scenario Outline: Update a garden with a value of incorrect type
	Given I upsert to the root of the request body:
	"""
	{
		"<parameter>": <value>
	}
	"""
	When I call 'PUT' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

	Examples:
		| parameter		| value	|
		| name			| 50	|
		| dimensionX		| "a"	|
		| dimensionY		| "a"	|
		| plantLocations	| "a"	|

Scenario Outline: Update a garden with strings of boundary correct/incorrect length
	Given I upsert to the root of the request body, a string of key '<key>' and length '<length>'
	When I call 'PUT' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response has a status of '<status>'

	Examples:
		| key			| length	| status			|
		| name			| 0		| HTTP/1.1 400 Bad Request	|
		| name			| 1		| HTTP/1.1 200 OK		|
		| name			| 80		| HTTP/1.1 200 OK		|
		| name			| 81		| HTTP/1.1 400 Bad Request	|

Scenario Outline: Update a garden with integers of boundary correct/incorrect length
	Given I upsert to the root of the request body, an int of key '<key>' and value '<value>'
	When I call 'PUT' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response has a status of '<status>'

	Examples:
		| key		| value		| status			|
		| dimensionX	| 0		| HTTP/1.1 400 Bad Request	|
		| dimensionX	| 1		| HTTP/1.1 200 OK		|
		| dimensionX	| 10		| HTTP/1.1 200 OK		|
		| dimensionX	| 11		| HTTP/1.1 400 Bad Request	|
		| dimensionY	| 0		| HTTP/1.1 400 Bad Request	|
		| dimensionY	| 1		| HTTP/1.1 200 OK		|
		| dimensionY	| 10		| HTTP/1.1 200 OK		|
		| dimensionY	| 11		| HTTP/1.1 400 Bad Request	|

Scenario: Update a garden without an id
	When I call 'PUT' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

Scenario: Update a garden with an id of incorrect type
	Given I upsert to the root of the request body:
	"""
	{
		"id": 5
	}
	"""
	When I call 'PUT' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'
