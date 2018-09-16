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
  - type          _(One Time, Recurring, Note)_
  - description
  - timestamps

- task_options    _(Used for storing optional task data like recurring dates and deadlines)_
  - id
  - task_id
  - key           _(Deadline, Recurring, Recurring Monthly, Recurring Annually)_
  - value         _(Possible values - Deadline: Date; Recurring: Daily | Day of week (Monday, Tuesday, etc); Recurring Monthly: Day of Month; Recurring Annually: Date)_
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
  - type          _(One Time, Recurring, Counter, Note)_
  - description
  - timestamps

- task_options    _(Used for storing optional task data like recurring dates and deadlines)_
  - id
  - task_id
  - user_id       _(The user who last interacted with this option)_
  - key           _(Deadline, Recurring, Recurring Monthly, Recurring Annually, Iterator, Countdown)_
  - value         _(Possible values - Deadline: Date; Recurring: Daily | Day of week (Monday, Tuesday, etc); Recurring Monthly: Day of Month; Recurring Annually: Date; Counter: Integer)_
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
