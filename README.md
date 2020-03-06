# todo
Please, implement a user story:
"As a user, I want to have an ability to see a list of tasks for my day, so that I can do them one by one".

What is expected: REST application that you can be proud of
You could use any framework and environment setup.
TIP: we really like TDD, DDD, and docker


As a user of this system we can add todo for any date mark them done and reopen. 

# Events

TodoPosted
TodoReopened
TodoMarkedAsDone
TodoCanceled

# Commands

PostTodo
ReopenTodo
MarkTodoAsDone
CancelTodo

# Exceptions

TodoNotFound
TodoAlreadyOpen
TodoAlreadyDone
TodoAlreadyCanceled
