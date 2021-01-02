Feature: Get gardens containing plants
As a user
I need to get the gardens I have created containing plants
So that I can see them

Background: Given I have the payload to create a garden with plants
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

Scenario: Get gardens with plants
	Given I call 'POST' 'http://localhost/api/garden'
		And I save 'id' from the response
		And I expect the same as the request body but with the saved 'id'
	When I call 'GET' 'http://localhost/api/gardens'
	Then the response body should contain what is expected

Scenario: Get a specific garden with plants
	Given I call 'POST' 'http://localhost/api/garden'
		And I save 'id' from the response
		And I expect the same as the request body but with the saved 'id'
	When I call 'GET' 'http://localhost/api/garden?id=' appending the saved 'id'
	Then the response body should be as expected
