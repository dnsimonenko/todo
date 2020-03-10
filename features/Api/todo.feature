Feature:
  In order to have an ability to see a list of tasks for my day, so that I can do them one by one
  As a user
  I want to have a scenario with todo functionality

  Scenario: Create a task for a given day
    Given I flush redis storage
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

  Scenario: Complete a task
    When I send a PATCH request to "/api/todo/72c87ace-b6d7-41f5-a1e1-1abdece44369/status-completed"
    Then the response code should be 204

  Scenario: Task should be done
    When I send a GET request to "/api/todo/72c87ace-b6d7-41f5-a1e1-1abdece44369"
    Then the response code should be 200
    And the response should contain json:
    """
    {
      "todo_id": "72c87ace-b6d7-41f5-a1e1-1abdece44369",
      "text": "Some text",
      "status": "done",
      "date": "2020-03-06T00:00:00+00:00"
    }
    """

  Scenario: Reopen a task
    When I send a PATCH request to "/api/todo/72c87ace-b6d7-41f5-a1e1-1abdece44369/status-open"
    Then the response code should be 204

  Scenario: Task should be reopened
    When I send a GET request to "/api/todo/72c87ace-b6d7-41f5-a1e1-1abdece44369"
    Then the response code should be 200
    And the response should contain json:
    """
    {
      "todo_id": "72c87ace-b6d7-41f5-a1e1-1abdece44369",
      "text": "Some text",
      "status": "open",
      "date": "2020-03-06T00:00:00+00:00"
    }
    """

  Scenario: List all tasks
    When I send a GET request to "/api/todo"
    Then the response code should be 200
    And the response should contain json:
    """
    [
      {
        "todo_id": "72c87ace-b6d7-41f5-a1e1-1abdece44369",
        "text": "Some text",
        "status": "open",
        "date": "2020-03-06T00:00:00+00:00"
      }
    ]
    """

  Scenario: Delete a task
    When I send a "DELETE" request to "/api/todo/72c87ace-b6d7-41f5-a1e1-1abdece44369"
    Then the response code should be 204

  Scenario: Empty list on get all
    When I send a GET request to "/api/todo"
    Then the response code should be 200
    And the response should contain json:
    """
    [
    ]
    """

#  Scenario: 404 on deleted task
#    When I send a GET request to "/api/todo/72c87ace-b6d7-41f5-a1e1-1abdece44369"
#    Then the response code should be 404
#

