# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html
# As a user, I want to have an ability to see a list of tasks for my day, so that I can do them one by one
Feature:
  In order to have an ability to see a list of tasks for my day, so that I can do them one by one
  As a user
  I want to have a scenario with todo functionality

#  Scenario: List all tasks for a given day
#    When I send a GET request to "/api/todo" with body:

  Scenario: Create a task for a given day
    When I send a POST request to "/api/todo" with body:
    """
    {
      "todoId": "72c87ace-b6d7-41f5-a1e1-1abdece44369",
      "text": "Some text",
      "status": "open",
      "date": "2020-03-06"
    }
    """
    Then the response code should be 201
