# Backend Architecture

**Project**

Rate Unit System

**Framework**

Laravel 11

**Language**

PHP 8.3+

**Architecture Version**

1.0.0

**Status**

Production Architecture

---

# Purpose

This document defines the official backend architecture of the Rate Unit System.

Every backend implementation inside this project must follow the conventions described here.

The objective is to guarantee:

* Consistency
* Scalability
* Maintainability
* Testability
* Separation of Concerns

This document acts as the primary reference for developers and AI coding agents.

Whenever there is a conflict between implementation and this document, this document becomes the source of truth.

---

# Project Philosophy

This project is not built as a simple Laravel CRUD application.

The backend follows a layered architecture where each layer has a single responsibility.

Business logic must never leak into Controllers or Blade templates.

Every feature must remain reusable, modular, and independently maintainable.

The architecture is optimized for long-term development rather than short-term implementation speed.

---

# Core Principles

## 1. Thin Controllers

Controllers are HTTP adapters.

Controllers should:

* receive HTTP requests
* authorize requests
* validate requests
* create DTO objects
* call Services
* return responses

Controllers must never:

* perform business logic
* manipulate database records directly
* send notifications
* dispatch queue jobs
* contain complex conditional logic

Good

```text
Request

↓

Controller

↓

DTO

↓

Service
```

Bad

```text
Controller

↓

Database

↓

Notification

↓

Business Logic

↓

Queue

↓

Response
```

---

## 2. Fat Services

Business logic belongs inside Services.

Services own every business decision.

Examples:

Good

* Create Rating
* Generate QR Code
* Assign Employee
* Submit Report
* Send Conversation Message

Bad

Controllers containing:

* if
* switch
* foreach
* transaction
* notification logic
* permission logic

---

## 3. Domain Driven Organization

Folders are organized by domain.

Never organize backend by UI.

Correct

```text
Conversation

QRCode

Rating

Report

Unit

Employee
```

Wrong

```text
Admin

Student

Employee
```

Roles use domains.

Domains never depend on roles.

---

## 4. Single Responsibility Principle

Every class has one reason to change.

Examples

MessageService

Responsible only for conversation message business logic.

RatingService

Responsible only for ratings.

QrCodeService

Responsible only for QR Code lifecycle.

---

## 5. Event Driven Design

Services emit Events.

Listeners react.

Services never know who consumes the Event.

Example

```text
RatingService

↓

RatingSubmittedEvent

↓

Listeners

↓

Notifications

Analytics

Statistics
```

---

## 6. Queue Heavy Operations

Heavy work must never block HTTP requests.

Queue examples

* Export Excel
* Image Optimization
* QR Generation
* Mail
* Notification
* Analytics

Users should never wait for these operations.

---

## 7. Reusable Components

Everything should be reusable.

Validation

↓

Rules

Business Logic

↓

Services

Notifications

↓

Notifications

Heavy Process

↓

Jobs

Communication

↓

Events

---

# Layered Architecture

Every HTTP request follows this pipeline.

```text
HTTP Request

↓

Middleware

↓

Policy

↓

FormRequest

↓

DTO

↓

Controller

↓

Service

↓

Event

↓

Listener

↓

Job (Optional)

↓

Notification (Optional)

↓

Response
```

Each layer owns a single responsibility.

No layer should bypass another without strong justification.

---

# Architecture Layers

## Layer 1

HTTP Layer

Contains

* Routes
* Middleware
* Policies
* Controllers
* Form Requests

Purpose

Receive external requests.

Nothing else.

---

## Layer 2

DTO Layer

Purpose

Convert validated request data into immutable objects.

DTO prevents Services from depending on HTTP.

DTO never performs validation.

DTO never accesses database.

DTO contains data only.

---

## Layer 3

Service Layer

This is the heart of the application.

Everything important happens here.

Services may

* query database
* create records
* update records
* delete records
* dispatch Events
* use Transactions

Services must never

* render Blade
* return HTML
* access Request()
* call view()

---

## Layer 4

Domain Event Layer

Events describe something that has already happened.

Example

```text
MessageSentEvent

RatingSubmittedEvent

ReportResolvedEvent

QrCodeGeneratedEvent
```

Events contain data.

Events contain no business logic.

---

## Layer 5

Listener Layer

Listeners react to Events.

Examples

* Send Notification
* Update Statistics
* Write Audit Log
* Dispatch Queue Job

Listeners never return HTTP responses.

---

## Layer 6

Queue Layer

Heavy asynchronous work.

Every Job must implement

ShouldQueue

Queue workers execute independently from HTTP lifecycle.

---

## Layer 7

Notification Layer

Responsible for user communication.

Current channel

* Database

Future channels

* Mail
* Broadcast
* Push
* SMS

Notifications should always be queueable.

---

# Dependency Rule

Allowed dependency direction

```text
Controller

↓

DTO

↓

Service

↓

Event

↓

Listener

↓

Job

↓

Notification
```

Reverse dependencies are forbidden.

Example

Notification

↓

Service

❌

Controller

↓

Notification

❌

Rule

↓

Service

❌

Blade

↓

Service

❌


---

# Folder Organization

The backend is organized using **Domain-Oriented Architecture**.

Folders must represent business domains rather than UI pages or user roles.

Correct

```text
Conversation
QRCode
Rating
Report
Employee
Unit
Assignment
```

Incorrect

```text
Admin
Dashboard
Pages
Student
EmployeeRole
```

Backend folders should remain reusable regardless of which role accesses them.

---

# Standard App Structure

```text
app
│
├── Console
├── Data
├── Enums
├── Events
├── Exceptions
├── Helpers
├── Http
│   ├── Controllers
│   ├── Middleware
│   └── Requests
│
├── Jobs
├── Listeners
├── Models
├── Notifications
├── Policies
├── Providers
├── Rules
├── Services
└── Traits
```

Every new feature must integrate into this structure.

Avoid introducing new top-level directories without architectural justification.

---

# Folder Responsibilities

## Controllers

Responsible for HTTP communication only.

Allowed

* Authorization
* Validation
* DTO creation
* Service invocation
* Response generation

Forbidden

* Business logic
* Database manipulation
* Queue dispatching
* Notifications
* Transactions

---

## Form Requests

Responsible for request validation.

Allowed

* validation rules
* authorize()

Forbidden

* database updates
* notifications
* business decisions

---

## DTO (Data Objects)

Purpose

Transfer validated data into Services.

DTO objects should

* be immutable
* contain only data
* not know about HTTP
* not know about Models

Example

```text
CreateRatingData

UpdateUnitData

SendMessageData
```

---

## Services

Services are the application's business layer.

Responsibilities

* execute business rules
* perform database operations
* manage transactions
* dispatch domain events

Services should not

* render Blade
* return HTML
* call Request()
* access Session directly

---

## Events

Events represent completed business actions.

Naming convention

```text
MessageSentEvent

RatingSubmittedEvent

ReportResolvedEvent

QrCodeGeneratedEvent
```

Events must never contain business logic.

Events should only transport data.

---

## Listeners

Listeners react to domain events.

Typical responsibilities

* send notifications
* update statistics
* write logs
* dispatch heavy jobs

Listeners must remain focused.

One listener = one responsibility.

---

## Jobs

Jobs execute asynchronous work.

Jobs must implement

ShouldQueue

Jobs should remain idempotent whenever possible.

Examples

```text
GenerateQrCodeJob

OptimizeImageJob

ExportRatingsJob

CleanupTemporaryFilesJob
```

---

## Notifications

Notifications communicate information to users.

Current channel

* Database

Future

* Mail

* Broadcast

* Push

All notifications should implement Queueable.

---

## Rules

Rules perform reusable validation.

Rules may

* query database
* query cache when required

Rules may not

* modify database
* dispatch events
* send notifications
* call Services

Rules must remain deterministic.

---

# Dependency Matrix

Allowed

```text
Controller
↓

DTO
↓

Service
↓

Event
↓

Listener
↓

Job

↓

Notification
```

Allowed

```text
Rule
↓

Model
```

Allowed

```text
Rule
↓

Database Query
```

Forbidden

```text
Controller
↓

Model

(when business logic exists)
```

Forbidden

```text
Notification
↓

Service
```

Forbidden

```text
Rule
↓

Service
```

Forbidden

```text
Blade
↓

Service
```

Forbidden

```text
Event
↓

Database
```

Forbidden

```text
Listener
↓

Controller
```

---

# Naming Convention

Controllers

```text
ConversationController

RatingController

ReportController
```

Services

```text
ConversationService

RatingService

QrCodeService
```

DTO

```text
CreateUnitData

UpdateEmployeeData

SendMessageData
```

Rules

```text
ValidQrToken

AlreadyRatedToday

WithinUnitRadius
```

Events

```text
MessageSentEvent

RatingSubmittedEvent
```

Listeners

```text
SendUnreadNotificationListener

UpdateUnitStatisticsListener
```

Jobs

```text
GenerateQrCodeJob

ExportRatingJob
```

Notifications

```text
NewMessageNotification

ReportResolvedNotification
```

---

# Redis Strategy

Redis serves as the project's infrastructure layer.

Redis is used for

* Queue
* Cache
* Future Broadcast
* Future Rate Limiting
* Future Distributed Locks

Redis must never become the project's primary datastore.

Persistent data always belongs in MySQL.

---

# Queue Strategy

Queue Driver

```text
redis
```

Heavy processes should never execute during HTTP requests.

Queue candidates

* Export
* QR Generation
* Image Processing
* Notifications
* Analytics
* Maintenance

Fast operations remain synchronous.

Avoid dispatching Jobs unnecessarily.

---

# Transaction Strategy

Business transactions belong inside Services.

Example

```text
Begin Transaction

↓

Insert Rating

↓

Insert Scores

↓

Commit

↓

Dispatch Event
```

Never dispatch Events before a successful commit.

If a transaction fails, no Event should be emitted.

---

# Error Handling

Controllers should never swallow exceptions.

Services throw domain exceptions.

Controllers convert exceptions into HTTP responses.

Unexpected errors should be logged automatically.

Sensitive information must never be exposed to end users.

---

# Logging Strategy

Use logging for

* Exceptions
* Failed Jobs
* Critical business failures
* Security incidents

Do not log

* passwords
* tokens
* personal secrets
* sensitive request payloads

Logs should assist debugging without exposing confidential information.
---

# Service Standards

Services are the application's business layer.

Every business operation must start here.

Services own business decisions.

Services may

* Query Models
* Create Records
* Update Records
* Delete Records
* Start Transactions
* Dispatch Events
* Call Helper Classes
* Use DTO Objects

Services must NOT

* Render Blade
* Return HTML
* Access Request()
* Access Session()
* Access Auth() directly when avoidable
* Send Notifications directly
* Dispatch unnecessary Jobs

---

# Service Responsibilities

A Service answers one question only.

"What business operation should happen?"

Example

Good

```text
MessageService

↓

Send Message

↓

Store Attachments

↓

Dispatch MessageSentEvent
```

Bad

```text
MessageService

↓

Send Email

↓

Render Blade

↓

Generate PDF

↓

Return JSON

↓

Business Logic
```

---

# DTO Standards

DTO exists to isolate Services from HTTP.

Controllers create DTO.

Services consume DTO.

DTO contains

* readonly properties
* typed values
* normalized data

DTO contains NO

* validation
* queries
* business logic
* rendering

Good

```text
SendMessageData

conversationId

senderId

message

attachments
```

Bad

```text
SendMessageData

↓

save()

↓

delete()

↓

query()

↓

notify()
```

---

# Event Standards

Events represent completed business actions.

Events must always be written in Past Tense.

Correct

```text
MessageSentEvent

RatingSubmittedEvent

ReportResolvedEvent

QrCodeGeneratedEvent
```

Wrong

```text
SendMessageEvent

GenerateQrEvent

UpdateRatingEvent
```

Events contain

* identifiers
* models
* DTO
* timestamps

Events contain NO

* queries
* notifications
* business logic
* transactions

---

# Listener Standards

Listeners react.

Listeners never initiate business actions.

Listener responsibilities

* Send Notification

* Dispatch Queue

* Update Analytics

* Write Audit Log

* Sync Statistics

Each Listener should have one purpose.

Good

```text
RatingSubmittedEvent

↓

SendRatingNotificationListener
```

Good

```text
RatingSubmittedEvent

↓

UpdateUnitStatisticListener
```

Bad

```text
One Listener

↓

Notification

↓

Analytics

↓

Export

↓

Mail

↓

Cleanup

↓

Sync

↓

Everything
```

---

# Queue Standards

Heavy work belongs to Queue.

Every Job

implements

ShouldQueue

Jobs should be

* idempotent
* retryable
* independent

Good candidates

* QR Generation

* Export

* Backup

* Image Optimization

* Archive

* Cleanup

Avoid Queue

* Small CRUD

* Validation

* Simple Queries

---

# Notification Standards

Notifications communicate with users.

Notifications should never be triggered inside Controllers.

Preferred flow

```text
Service

↓

Event

↓

Listener

↓

Notification
```

Current Channel

Database

Future Channels

Mail

Broadcast

Push

Slack

SMS

Every Notification

implements

Queueable

---

# Rule Standards

Rules provide reusable validation.

Rules may

* Query Model

* Query Database

* Query Cache

Rules must NOT

* Update Database

* Delete Records

* Dispatch Events

* Dispatch Jobs

* Call Services

* Send Notifications

Rules must remain deterministic.

The same input must always produce the same validation result.

---

# Policy Standards

Policies determine authorization.

Policies answer

Can this user perform this action?

Policies never

* update database

* create records

* send notifications

* dispatch events

Policies only return

true

or

false

---

# Validation Standards

Validation belongs inside

FormRequest

or

Rules

Never validate inside Services.

Never validate inside Controllers.

Never duplicate validation.

---

# Transaction Standards

Transactions belong inside Services.

Correct

```text
Begin Transaction

↓

Business Logic

↓

Commit

↓

Dispatch Event
```

Wrong

```text
Dispatch Event

↓

Transaction

↓

Rollback
```

Events should only be emitted after successful persistence.

---

# Exception Standards

Business failures

↓

Domain Exception

Unexpected failures

↓

System Exception

Controllers convert exceptions into HTTP responses.

Services never return

false

for business failures.

Throw Exceptions instead.

---

# Coding Standards

Every class

Single Responsibility

Every method

Small

Readable

Typed

Avoid methods longer than

50 lines

Prefer

Dependency Injection

Avoid

Static Helpers

unless utility class.

---

# Best Practices

✔ Thin Controllers

✔ Fat Services

✔ DTO Everywhere

✔ Event Driven

✔ Queue Heavy Tasks

✔ Database Transactions

✔ Reusable Rules

✔ Queue Notifications

✔ Domain Based Modules

✔ Constructor Injection

✔ Typed Properties

✔ Readable Names

✔ Small Methods

---

# Anti Patterns

Never

Controller

↓

Model

↓

Everything

Never

Rule

↓

Service

Never

Notification

↓

Database Update

Never

Job

↓

Return HTTP Response

Never

Blade

↓

Business Logic

Never

Model

↓

Render HTML

Never

Controller

↓

Notification

Never

Listener

↓

Controller

---

# Architecture Decision Records (ADR)

## ADR-001

Business Logic belongs inside Services.

Reason

Maintainability

Rejected

Fat Controllers

---

## ADR-002

Controllers only orchestrate requests.

Reason

Separation of Concerns

Rejected

CRUD Controllers with business logic

---

## ADR-003

DTO isolates HTTP from Business Layer.

Reason

Reusability

---

## ADR-004

Rules may query Models.

Rules may never call Services.

Reason

Prevent recursive business logic.

---

## ADR-005

Events represent completed business actions.

Reason

Loose Coupling

---

## ADR-006

Listeners perform side effects.

Reason

Single Responsibility

---

## ADR-007

Jobs execute only heavy asynchronous work.

Reason

Performance

---

## ADR-008

Notifications are always dispatched from Listeners.

Reason

Decoupling

---

## ADR-009

Redis is infrastructure.

MySQL remains the primary datastore.

---

## ADR-010

Folder structure follows Domain Organization.

Never UI Organization.

Reason

Long-term scalability.

---

# Future Scalability

The backend architecture is intentionally designed to support future expansion without requiring structural rewrites.

Future modules should integrate seamlessly into the existing architecture.

Potential future modules include

* Realtime Notifications
* WebSocket Broadcasting
* Attendance
* Complaint Escalation
* Audit Trail
* AI Recommendation
* Reporting Dashboard
* Analytics Engine
* Mobile API
* Public API
* Multi Tenancy

Every future module must follow the same architectural principles defined in this document.

---

# Module Development Workflow

Every new feature should follow the same implementation workflow.

```text
Requirement

↓

Planning

↓

Database Migration

↓

Model

↓

Policy

↓

Rule

↓

FormRequest

↓

DTO

↓

Service

↓

Event

↓

Listener

↓

Job (Optional)

↓

Notification (Optional)

↓

Controller

↓

Blade

↓

JavaScript

↓

Testing

↓

Documentation
```

Skipping layers without architectural justification is discouraged.

---

# Development Workflow

Every implementation should begin with the business process rather than the user interface.

Recommended order

1. Database
2. Model
3. Policy
4. Rules
5. DTO
6. Service
7. Event
8. Listener
9. Job
10. Notification
11. Controller
12. Blade
13. JavaScript
14. Testing

Avoid implementing UI before business logic has been completed.

---

# AI Development Guidelines

This project is actively developed using AI coding assistants.

Every AI Agent must follow these rules.

## AI must NEVER

* Move business logic into Controllers.
* Place validation inside Services.
* Duplicate validation rules.
* Introduce new folder structures without justification.
* Replace Event Driven architecture with direct calls.
* Send Notifications directly from Controllers.
* Dispatch heavy Jobs from Blade or JavaScript.
* Bypass Policies or Form Requests.
* Introduce unnecessary packages.
* Rewrite unrelated modules.

---

## AI should ALWAYS

* Preserve existing architecture.
* Respect folder organization.
* Keep methods small and readable.
* Reuse existing Services.
* Reuse existing Rules.
* Dispatch Events after successful business operations.
* Prefer constructor dependency injection.
* Use Laravel best practices.
* Maintain backward compatibility whenever possible.

---

# Pull Request Checklist

Before merging any backend changes, verify the following.

Controllers

* Thin
* No business logic
* Uses DTO
* Uses FormRequest

Services

* Single responsibility
* Business logic only
* Uses transactions where required

Rules

* Reusable
* Deterministic
* No side effects

Events

* Past tense naming
* No business logic

Listeners

* One responsibility
* Queueable when appropriate

Jobs

* Heavy asynchronous tasks only

Notifications

* Queueable
* Triggered by Listeners

General

* No duplicated code
* Naming conventions followed
* Documentation updated

---

# Performance Standards

Backend performance should prioritize responsiveness.

Recommended practices

* Eager Load relationships
* Avoid N+1 queries
* Cache expensive queries
* Queue heavy tasks
* Paginate large datasets
* Use indexes on foreign keys
* Optimize database queries
* Prefer Collections over manual loops when appropriate

Avoid

* Loading unnecessary relationships
* Repeated queries inside loops
* Large synchronous exports
* Blocking HTTP requests

---

# Security Standards

Every module must be secure by default.

Authentication

* Laravel Authentication
* Policies
* Middleware

Authorization

* Policy based

Validation

* FormRequest
* Rules

Sensitive Operations

* Transactions
* Audit Logging (future)

Uploads

* Validate MIME type
* Validate size
* Safe file naming
* Store outside public execution paths when appropriate

Never trust client-side validation.

Always validate on the server.

---

# Error Handling Standards

Expected business failures

↓

Domain Exceptions

Unexpected failures

↓

Application Exceptions

Critical failures

↓

Logging

↓

Queue Failed Jobs

↓

Developer Investigation

Never expose stack traces to end users.

Never leak sensitive configuration.

---

# Testing Strategy

Every new backend feature should eventually support

Unit Tests

* Services
* Rules
* DTO

Feature Tests

* Controllers
* Policies
* Requests

Integration Tests

* Queue
* Notifications
* Events

Future

* Browser Tests
* API Tests
* Load Tests

Testing should validate behavior rather than implementation details.

---

# Documentation Standards

Every major module should provide documentation covering

* Purpose
* Flow
* Business Rules
* Database Relations
* Events
* Notifications
* Jobs
* Extension Points

Documentation must evolve alongside implementation.

---

# Quality Standards

Every new backend code should satisfy the following principles.

Readable

Maintainable

Reusable

Scalable

Secure

Testable

Predictable

Consistent

Simple

Explicit

Avoid clever solutions when simpler solutions exist.

Code should be understandable by another developer without additional explanation.

---

# Architecture Summary

The backend architecture of the Rate Unit System is built upon the following principles.

* Domain-Oriented Design
* Thin Controllers
* Fat Services
* DTO Pattern
* Event Driven Architecture
* Queue Heavy Operations
* Reusable Validation Rules
* Queueable Notifications
* Redis Infrastructure
* Laravel Best Practices

These principles ensure the application remains maintainable, extensible, and production-ready as the system grows.

---

# Final Statement

This document defines the official backend architecture of the Rate Unit System.

All backend implementations, regardless of developer or AI assistant, must adhere to the standards described herein.

Future architectural decisions should extend this document rather than contradict it.

Consistency is considered a feature.

Maintainability is prioritized over short-term convenience.

Architecture exists to support long-term development, not temporary implementation speed.

---

**End of Document**
