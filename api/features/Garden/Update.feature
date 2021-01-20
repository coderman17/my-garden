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
		And I call 'POST' '/api/garden'
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
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '200'
		And I expect the same as the request body but with the saved 'id'
	When I call 'GET' '/api/garden?id=' appending the saved 'id'
	Then the response body should be as expected

Scenario: Update a garden but without altering it
	Given I expect the same as the request body but with the saved 'id'
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '200'
		And the response body should be as expected

Scenario: Update a garden which doesn't exist
	Given I generate and save a random 'id'
		And I expect the same as the request body but with the saved 'id'
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '201'
	When I call 'GET' '/api/garden?id=' appending the saved 'id'
	Then the response body should be as expected

Scenario Outline: Update a garden without a parameter
	Given I remove '<parameter>' from the root of the request body
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '400'

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
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '400'

	Examples:
		| parameter		| value	|
		| name			| 50	|
		| dimensionX		| "a"	|
		| dimensionY		| "a"	|
		| plantLocations	| "a"	|

Scenario Outline: Update a garden with strings of boundary correct/incorrect length
	Given I upsert to the root of the request body, a string of key '<key>' and length '<length>'
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '<status>'

	Examples:
		| key			| length	| status			|
		| name			| 0		| 400	|
		| name			| 1		| 200		|
		| name			| 80		| 200		|
		| name			| 81		| 400	|

Scenario Outline: Update a garden with integers of boundary correct/incorrect length
	Given I upsert to the root of the request body, an int of key '<key>' and value '<value>'
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '<status>'

	Examples:
		| key		| value		| status			|
		| dimensionX	| 0		| 400	|
		| dimensionX	| 1		| 200		|
		| dimensionX	| 10		| 200		|
		| dimensionX	| 11		| 400	|
		| dimensionY	| 0		| 400	|
		| dimensionY	| 1		| 200		|
		| dimensionY	| 10		| 200		|
		| dimensionY	| 11		| 400	|

Scenario: Update a garden without an id
	When I call 'PUT' '/api/garden'
	Then the response has a status of '400'

Scenario: Update a garden with an id of incorrect type
	Given I upsert to the root of the request body:
	"""
	{
		"id": 5
	}
	"""
	When I call 'PUT' '/api/garden'
	Then the response has a status of '400'
