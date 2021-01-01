Feature: Add plants when creating a garden
As a user
I need to create a garden with plants
So that I can see them

Background: A valid garden request body
	Given I have a request body:
	"""
	{
		"name": "test",
		"dimensionX": 8,
		"dimensionY": 8,
		"plants": [
			{
				"id": "5fea8ef735b2b",
				"coordinateX": 1,
				"coordinateY": 1
			},
			{
				"id": "5fea8ef735b2b",
				"coordinateX": 1,
				"coordinateY": 2
			}
		]
	}
	"""

Scenario: Create a garden with two plants
	Given I have a request body:
	"""
	{
		"englishName": "test",
		"latinName": "test in latin",
		"imageLink": "www..."
	}
	"""
		And I call 'POST' 'http://localhost/api/plant'
		And I save 'id' from the response as 'plantOneId'
		And I call 'POST' 'http://localhost/api/plant'
		And I save 'id' from the response as 'plantTwoId'
		And I have a request body:
		"""
		{
			"name": "test",
			"dimensionX": 8,
			"dimensionY": 8,
			"plantLocations": [
				{
					"id": "{{plantOneId}}",
					"coordinateX": 1,
					"coordinateY": 1
				},
				{
					"id": "{{plantTwoId}}",
					"coordinateX": 2,
					"coordinateY": 1
				}
			]
		}
		"""
		And I replace variables in the request body with the saved value
	When I call 'POST' 'http://localhost/api/garden'
		And the response has a status of 'HTTP/1.1 201 Created'
		And I save 'id' from the response
		And I expect the same as the request body but with the saved 'id'
	When I call 'GET' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response body should be as expected

#Scenario Outline: Create a garden without a parameter
#	Given I remove '<parameter>' from the root of the request body
#	When I call 'POST' 'http://localhost/api/garden'
#	Then the response has a status of 'HTTP/1.1 400 Bad Request'
#
#	Examples:
#	| parameter	|
#	| name		|
#	| dimensionX	|
#	| dimensionY	|
#
#Scenario Outline: Create a garden with a value of incorrect type
#	Given I upsert to the root of the request body:
#	"""
#	{
#		"<parameter>": <value>
#	}
#	"""
#	When I call 'POST' 'http://localhost/api/garden'
#	Then the response has a status of 'HTTP/1.1 400 Bad Request'
#
#	Examples:
#	| parameter	| value	|
#	| name		| 50	|
#	| dimensionX	| "a"	|
#	| dimensionY	| "a"	|
#
#Scenario Outline: Create a garden with strings of boundary correct/incorrect length
#	Given I upsert to the root of the request body, a string of key '<key>' and length '<length>'
#	When I call 'POST' 'http://localhost/api/garden'
#	Then the response has a status of '<status>'
#
#	Examples:
#		| key			| length	| status			|
#		| name			| 0		| HTTP/1.1 400 Bad Request	|
#		| name			| 1		| HTTP/1.1 201 Created		|
#		| name			| 80		| HTTP/1.1 201 Created		|
#		| name			| 81		| HTTP/1.1 400 Bad Request	|
#
#Scenario Outline: Create a garden with integers of boundary correct/incorrect length
#	Given I upsert to the root of the request body, an int of key '<key>' and value '<value>'
#	When I call 'POST' 'http://localhost/api/garden'
#	Then the response has a status of '<status>'
#
#	Examples:
#		| key		| value	| status			|
#		| dimensionX	| 0	| HTTP/1.1 400 Bad Request	|
#		| dimensionX	| 1	| HTTP/1.1 201 Created		|
#		| dimensionX	| 10	| HTTP/1.1 201 Created		|
#		| dimensionX	| 11	| HTTP/1.1 400 Bad Request	|
#		| dimensionY	| 0	| HTTP/1.1 400 Bad Request	|
#		| dimensionY	| 1	| HTTP/1.1 201 Created		|
#		| dimensionY	| 10	| HTTP/1.1 201 Created		|
#		| dimensionY	| 11	| HTTP/1.1 400 Bad Request	|
