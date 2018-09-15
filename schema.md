# Schema description
This document describes the planned schema for the todo app.
The app will be developed in phases, initially as a single user application and then expanded to support multiple users and teams.


## Basic single-user schema
- projects
  - id
  - name
  - description
  - timestamps

- tasks
  - id
  - parent_id     _(The parent task id, to allow nesting tasks, nullable)_
  - project_id
  - status        _(Complete, In Progress, Deleted, etc.)_
  - type          _(Checkbox, Note)_
  - description
  - timestamps

- statuses
  - id
  - task_id
  - status
  - duration
  - started_at
  - ended_at
  - timestamps


## Final planned schema
- users
  - id
  - username
  - email
  - public key    _(Users do not have passwords, key based auth)_
  - timestamps

- teams
  - id
  - name
  - description
  - timestamps

- team_users
  - team_id
  - user_id
  - role
  - timestamps

- projects
  - id
  - owner_id
  - owner_type    _(Can be owned by teams or users)_
  - name
  - description
  - timestamps

- tasks
  - id
  - parent_id     _(The parent task id, to allow nesting tasks, nullable)_
  - owner_id
  - owner_type    _(Can be owned by teams, projects, or users)_
  - status        _(Complete, In Progress, Deleted, etc.)_
  - type          _(Checkbox, Iterator, Countdown, Note)_
  - description
  - timestamps

- statuses
  - id
  - task_id
  - user_id       _(The user who initiated this status change)_
  - status
  - duration
  - started_at
  - ended_at
  - timestamps


## Needs further thought
- task_data       _(Used for storing iterator and countdown values)_
  - id
  - task_id
  - user_id       _(The user who last interacted with this task)_
  - value
  - timestamps
