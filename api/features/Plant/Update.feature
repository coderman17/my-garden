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
		And I call 'POST' 'http://localhost/api/plant'
		And I save 'id' from the response

Scenario: Update a plant which exists
	Given I have a request body:
	"""
	{
		"englishName": "updated",
		"latinName": "updated in latin",
		"imageLink": "updated..."
	}
	"""
		And I expect the same as the request body but with the saved 'id'
	When I call 'PUT' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 200 OK'
		And the response body should be as expected
	When I call 'GET' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response body should be as expected

Scenario: Update a plant but without altering it
	Given I expect the same as the request body but with the saved 'id'
	When I call 'PUT' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 200 OK'
		And the response body should be as expected

Scenario: Update a plant which doesn't exist
	Given I generate and save a random 'id'
		And I expect the same as the request body but with the saved 'id'
	When I call 'PUT' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 201 Created'
		And the response body should be as expected

Scenario Outline: Update a plant without a parameter
	Given I remove '<parameter>' from the root of the request body
	When I call 'PUT' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

	Examples:
		| parameter	|
		| englishName	|
		| latinName	|
		| imageLink	|

Scenario: Update a plant without an id
	When I call 'PUT' 'http://localhost/api/plant'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

Scenario: Update a plant with an id of incorrect type
	Given I upsert to the root of the request body:
	"""
	{
		"id": 5
	}
	"""
	When I call 'PUT' 'http://localhost/api/plant'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

Scenario Outline: Update a plant with a value of incorrect type
	Given I upsert to the root of the request body:
	"""
	{
		"<parameter>": <value>
	}
	"""
	When I call 'PUT' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

	Examples:
		| parameter	| value	|
		| englishName	| 50	|
		| latinName	| 50	|
		| imageLink	| 50	|

Scenario Outline: Update a plant with strings of boundary correct/incorrect length
	Given I upsert to the root of the request body, a string of key '<key>' and length '<length>'
	When I call 'PUT' 'http://localhost/api/plant?id=' appending the saved 'id'
	Then the response has a status of '<status>'

	Examples:
		| key		| length	| status			|
		| englishName	| 0		| HTTP/1.1 400 Bad Request	|
		| englishName	| 1		| HTTP/1.1 200 OK		|
		| englishName	| 80		| HTTP/1.1 200 OK		|
		| englishName	| 81		| HTTP/1.1 400 Bad Request	|
		| latinName	| 255		| HTTP/1.1 200 OK		|
		| latinName	| 256		| HTTP/1.1 400 Bad Request	|
		| imageLink	| 500		| HTTP/1.1 200 OK		|
		| imageLink	| 501		| HTTP/1.1 400 Bad Request	|
