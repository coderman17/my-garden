Feature: Add plants when creating a garden
As a user
I need to create a garden with plants
So that I can see them

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

Scenario Outline: Create a garden with a plant location parameter of the wrong type
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
		And I have a request body:
		"""
		{
		"name": "test",
		"dimensionX": 8,
		"dimensionY": 8,
		"plantLocations": [
			{
				"id": "{{plantOneId}}",
				"coordinateX": <coordinateX>,
				"coordinateY": <coordinateY>
			}
		]
		}
		"""
		And I replace variables in the request body with the saved value
	When I call 'POST' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

	Examples:
		| coordinateX	| coordinateY	|
		| "a"			| 1				|
		| 1				| "a"			|

Scenario: Create a garden with two plants in the same location
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
					"coordinateX": 1,
					"coordinateY": 1
				}
			]
		}
		"""
		And I replace variables in the request body with the saved value
	When I call 'POST' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

Scenario Outline: Create a garden with a plant in a location outside of the garden's dimensions
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
		And I have a request body:
		"""
		{
		"name": "test",
		"dimensionX": 8,
		"dimensionY": 8,
		"plantLocations": [
			{
				"id": "{{plantOneId}}",
				"coordinateX": <coordinateX>,
				"coordinateY": <coordinateY>
			}
		]
		}
		"""
		And I replace variables in the request body with the saved value
	When I call 'POST' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

	Examples:
		| coordinateX	| coordinateY	|
		| 0				| 1				|
		| 1				| 0				|
		| 8				| 9				|
		| 9				| 8				|

Scenario Outline: Create a garden with a plant but without a parameter
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
		And I have a request body:
		"""
		{
		"name": "test",
		"dimensionX": 8,
		"dimensionY": 8,
		"plantLocations": [
			{
				"id": "{{plantOneId}}",
				"coordinateX": 9,
				"coordinateY": 1
			}
		]
		}
		"""
		And I replace variables in the request body with the saved value
		And I remove '<parameter>' from the first 'plantLocations' object in the request body
	When I call 'POST' 'http://localhost/api/garden'
	Then the response has a status of 'HTTP/1.1 400 Bad Request'

	Examples:
		| parameter			|
		| id				|
		| coordinateX		|
		| coordinateY		|
