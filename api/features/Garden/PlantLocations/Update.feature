Feature: Add plants when updating a garden
As a user
I need to update a garden with plants
So that I can see them

Background: I have a garden with two plants
	Given I have a request body:
	"""
	{
		"englishName": "test",
		"latinName": "test in latin",
		"imageLink": "www..."
	}
	"""
		And I call 'POST' '/api/plant'
		And I save 'id' from the response as 'plantOneId'
		And I call 'POST' '/api/plant'
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
		And I call 'POST' '/api/garden'
		And I save 'id' from the response

Scenario: update a garden adding a new plant
	Given I have a request body:
	"""
	{
		"englishName": "test",
		"latinName": "test in latin",
		"imageLink": "www..."
	}
	"""
		And I call 'POST' '/api/plant'
		And I save 'id' from the response as 'plantThreeId'
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
				},
				{
					"id": "{{plantThreeId}}",
					"coordinateX": 3,
					"coordinateY": 1
				}
			]
		}
		"""
		And I replace variables in the request body with the saved value
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '200'
		And I expect the same as the request body but with the saved 'id'
	When I call 'GET' '/api/garden?id=' appending the saved 'id'
	Then the response body should be as expected

Scenario: update a garden replacing a plant
	Given I have a request body:
	"""
	{
		"englishName": "test",
		"latinName": "test in latin",
		"imageLink": "www..."
	}
	"""
		And I call 'POST' '/api/plant'
		And I save 'id' from the response as 'plantThreeId'
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
					"id": "{{plantThreeId}}",
					"coordinateX": 2,
					"coordinateY": 1
				}
			]
		}
		"""
		And I replace variables in the request body with the saved value
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '200'
		And I expect the same as the request body but with the saved 'id'
	When I call 'GET' '/api/garden?id=' appending the saved 'id'
	Then the response body should be as expected

Scenario: update a garden, removing a plant
	Given I have a request body:
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
			}
		]
	}
	"""
		And I replace variables in the request body with the saved value
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '200'
		And I expect the same as the request body but with the saved 'id'
	When I call 'GET' '/api/garden?id=' appending the saved 'id'
	Then the response body should be as expected

Scenario: Update a garden containing plants, which doesn't exist
	Given I generate and save a random 'id'
		And I expect the same as the request body but with the saved 'id'
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '201'
	When I call 'GET' '/api/garden?id=' appending the saved 'id'
	Then the response body should be as expected

Scenario: Update a garden with plants without altering anything
	Given I expect the same as the request body but with the saved 'id'
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '200'
		And the response body should be as expected

Scenario: Update a garden with a plant which doesn't exist
	Given I have a request body:
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
			}
		]
	}
	"""
	Given I generate and save a random 'plantOneId'
		And I replace variables in the request body with the saved value
	When I call 'PUT' '/api/plant?id=' appending the saved 'id'
	Then the response has a status of '400'

Scenario Outline: Update a garden with a plant location parameter of the wrong type
	Given I have a request body:
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
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '400'

	Examples:
		| coordinateX	| coordinateY	|
		| "a"		| 1		|
		| 1		| "a"		|

Scenario: Update a garden with two plants in the same location
	Given I have a request body:
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
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '400'

Scenario Outline: Update a garden with a plant in a location outside of the garden's dimensions
	Given I have a request body:
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
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '400'

	Examples:
		| coordinateX	| coordinateY	|
		| 0		| 1		|
		| 1		| 0		|
		| 8		| 9		|
		| 9		| 8		|

Scenario Outline: Update a garden with a plant but without a parameter
	Given I have a request body:
	"""
	{
	"name": "test",
	"dimensionX": 8,
	"dimensionY": 8,
	"plantLocations": [
		{
			"id": "{{plantOneId}}",
			"coordinateX": 2,
			"coordinateY": 1
		}
	]
	}
	"""
		And I replace variables in the request body with the saved value
		And I remove '<parameter>' from the first 'plantLocations' object in the request body
	When I call 'PUT' '/api/garden?id=' appending the saved 'id'
	Then the response has a status of '400'

	Examples:
		| parameter		|
		| id			|
		| coordinateX		|
		| coordinateY		|
