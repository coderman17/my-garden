Feature: Update garden
As a user
I need to update gardens
So that I can manage my collection

Background: A valid request body
	Given I have a request body:
	"""
	{
		"name": "test",
		"x_dimension": 8,
		"y_dimension": 8
	}
	"""

Scenario: Update a garden which exists
	Given I call 'POST' 'http://localhost/api/garden'
	And I save 'id' from the response
	And I call 'GET' 'http://localhost/api/garden?id=' appending the saved 'id'
	And the response has a status of 'HTTP/1.1 200 OK'
	When I have a request body:
	"""
	{
		"name": "updated test",
		"x_dimension": 1,
		"y_dimension": 1
	}
	"""
	And I call 'PUT' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 200 OK'
	When I expect the same as the request body but with the saved 'id'
	And I call 'GET' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response body should be as expected

Scenario: Update a garden which doesn't exist
	Given I call 'GET' 'http://localhost/api/garden?id=439583'
	And the response has a status of 'HTTP/1.1 404 Not Found'
	When I have a request body:
	"""
	{
		"name": "updated test",
		"x_dimension": 1,
		"y_dimension": 1
	}
	"""
	When I call 'PUT' 'http://localhost/api/garden?id=439583'
	Then the response has a status of 'HTTP/1.1 404 Not Found'

Scenario Outline: Update a garden without a parameter
	Given I call 'POST' 'http://localhost/api/garden'
	And I save 'id' from the response
	And I call 'GET' 'http://localhost/api/garden?id=' appending the saved 'id'
	And the response has a status of 'HTTP/1.1 200 OK'
	When I have a request body:
	"""
	{
		"name": "updated test",
		"x_dimension": 1,
		"y_dimension": 1
	}
	"""
	And I remove '<parameter>' from the root of the request body
	And I call 'PUT' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

	Examples:
		| parameter		|
		| name			|
		| x_dimension	|
		| y_dimension	|

Scenario Outline: Update a garden with a value of incorrect type
	Given I call 'POST' 'http://localhost/api/garden'
	And I save 'id' from the response
	And I call 'GET' 'http://localhost/api/garden?id=' appending the saved 'id'
	And the response has a status of 'HTTP/1.1 200 OK'
	When I have a request body:
	"""
	{
		"name": "updated test",
		"x_dimension": 1,
		"y_dimension": 1
	}
	"""
	And I upsert to the root of the request body:
	"""
	{
		"<parameter>": <value>
	}
	"""
	And I call 'PUT' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

	Examples:
		| parameter		| value	|
		| name			| 50	|
		| x_dimension	| "a"	|
		| y_dimension	| "a"	|

Scenario Outline: Update a garden with strings of boundary correct/incorrect length
	Given I call 'POST' 'http://localhost/api/garden'
	And I save 'id' from the response
	And I call 'GET' 'http://localhost/api/garden?id=' appending the saved 'id'
	And the response has a status of 'HTTP/1.1 200 OK'
	When I have a request body:
	"""
	{
		"name": "updated test",
		"x_dimension": 1,
		"y_dimension": 1
	}
	"""
	And I upsert to the root of the request body, a string of key '<key>' and length '<length>'
	And I call 'PUT' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response has a status of '<status>'

	Examples:
		| key			| length	| status					|
		| name			| 0			| HTTP/1.1 400 Bad Request	|
		| name			| 1			| HTTP/1.1 201 Created		|
		| name			| 80		| HTTP/1.1 201 Created		|
		| name			| 81		| HTTP/1.1 400 Bad Request	|

Scenario Outline: Update a garden with integers of boundary correct/incorrect length
	Given I call 'POST' 'http://localhost/api/garden'
	And I save 'id' from the response
	And I call 'GET' 'http://localhost/api/garden?id=' appending the saved 'id'
	And the response has a status of 'HTTP/1.1 200 OK'
	When I have a request body:
	"""
	{
		"name": "updated test",
		"x_dimension": 1,
		"y_dimension": 1
	}
	"""
	When I upsert to the root of the request body, an integer of key '<key>' and value '<value>'
	And I call 'PUT' 'http://localhost/api/garden?id=' appending the saved 'id'
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
