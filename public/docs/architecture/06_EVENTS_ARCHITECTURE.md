# 06_EVENTS_ARCHITECTURE.md

---

# Event Architecture

**Project**

Rate Unit System

**Framework**

Laravel 11

**Architecture Version**

1.0.0

**Status**

Production Ready

---

# Purpose

This document defines the official Event-Driven Architecture used throughout the backend.

Every business event inside the application must follow the standards described in this document.

The purpose of Event-Driven Architecture is to decouple business operations from side effects.

Instead of Services directly sending notifications, updating statistics, or dispatching background jobs, Services publish Events.

Consumers react independently through Listeners.

This approach improves scalability, maintainability, extensibility, and testability.

---

# Philosophy

Events describe something that has already happened.

Events never describe intentions.

Correct

```text
MessageSentEvent
```

Incorrect

```text
SendMessageEvent
```

Correct

```text
RatingSubmittedEvent
```

Incorrect

```text
SubmitRatingEvent
```

Past tense is mandatory.

---

# Core Principles

## 1. Events represent facts

Events are immutable facts.

Examples

* Message was sent.
* Rating was submitted.
* QR Code was generated.
* Employee was assigned.

Events should never ask the system to perform work.

---

## 2. Services publish Events

Only Services publish Events.

Controllers must never dispatch Events.

Blade must never dispatch Events.

JavaScript must never dispatch backend Events.

Correct

```text
Controller

↓

Service

↓

dispatch(Event)
```

Incorrect

```text
Controller

↓

dispatch(Event)
```

---

## 3. Events contain no business logic

Events transport information.

Nothing more.

Allowed

* IDs
* Models
* DTO
* Timestamps

Forbidden

* Database queries
* Notifications
* Transactions
* Validation
* HTTP Requests

---

## 4. One Event = One Business Fact

Do not create generic Events.

Good

```text
RatingSubmittedEvent

ReportResolvedEvent

MessageDeletedEvent
```

Bad

```text
SystemUpdatedEvent

GeneralEvent

DataChangedEvent
```

Events should clearly describe what occurred.

---

## 5. Events are Domain-Based

Events belong to business domains.

Example

```text
Conversation/

MessageSentEvent

MessageDeletedEvent

ConversationArchivedEvent
```

Never organize Events by role.

Incorrect

```text
AdminEvents

StudentEvents
```

---

# Event Lifecycle

Standard flow

```text
HTTP Request

↓

Controller

↓

DTO

↓

Service

↓

Database Transaction

↓

Commit

↓

Dispatch Event

↓

Listeners

↓

Jobs (Optional)

↓

Notifications (Optional)
```

Events must always be dispatched after successful persistence.

---

# Why Event-Driven?

Without Events

```text
MessageService

↓

Store Message

↓

Send Notification

↓

Update Statistics

↓

Create Audit Log

↓

Broadcast

↓

Generate Analytics
```

The Service becomes tightly coupled.

With Events

```text
MessageService

↓

Store Message

↓

MessageSentEvent

↓

Listener A

↓

Notification

Listener B

↓

Statistics

Listener C

↓

Audit

Listener D

↓

Broadcast
```

The Service knows nothing about downstream consumers.

This is the preferred architecture.

---

# Event Folder Structure

Events should follow the application's domain organization.

```text
app/
└── Events/
    ├── Conversation/
    ├── Rating/
    ├── Report/
    ├── QRCode/
    ├── Employee/
    ├── Unit/
    └── Auth/
```

Each folder contains Events belonging to that domain only.

Never create miscellaneous folders such as

```text
CommonEvents

GeneralEvents

MiscEvents
```

---

# Event Naming Convention

Every Event must

* end with "Event"
* use Past Tense
* describe a completed action

Examples

```text
MessageSentEvent

MessageDeletedEvent

RatingSubmittedEvent

ReportResolvedEvent

QrCodeGeneratedEvent

EmployeeAssignedEvent
```

Avoid abbreviations unless already standardized across the project.


# Event Payload Standards

Events transport business information between independent layers.

Events should carry only the information required by downstream consumers.

Events may contain

* Model instances
* DTO objects
* IDs
* Collections
* Timestamps
* Metadata

Avoid unnecessary payloads.

Example

Good

```php
new MessageSentEvent($message);
```

Good

```php
new RatingSubmittedEvent($rating);
```

Avoid

```php
new MessageSentEvent(
    $message,
    $conversation,
    $participants,
    $attachments,
    $statistics,
    $notifications,
    $userSettings,
    $logs
);
```

Events should remain lightweight.

---

# Event Dispatch Standards

Only the Service Layer is responsible for dispatching Events.

Correct

```text
Controller

↓

Service

↓

Database

↓

Event
```

Incorrect

```text
Controller

↓

Event
```

Incorrect

```text
Blade

↓

Event
```

Incorrect

```text
JavaScript

↓

Backend Event
```

---

# Dispatch Timing

Events must always represent successful business operations.

Correct sequence

```text
Begin Transaction

↓

Business Logic

↓

Commit

↓

Dispatch Event
```

Incorrect

```text
Dispatch Event

↓

Commit
```

If a transaction rolls back, the Event must never be dispatched.

---

# Event Payload Guidelines

Prefer passing complete Models over primitive IDs whenever downstream listeners require relationships.

Example

Good

```php
new MessageSentEvent($message);
```

Listener

```php
$event->message
```

instead of

```php
new MessageSentEvent(
    $messageId
);
```

unless only the identifier is required.

---

# Event Registration

All Events must be registered inside the application's Event Service Provider.

Example

```text
MessageSentEvent

↓

SendUnreadNotificationListener

↓

UpdateConversationStatisticsListener

↓

BroadcastConversationListener
```

Registration should remain centralized.

Avoid dynamic registration.

---

# Multiple Listeners

One Event may have many Listeners.

Example

```text
MessageSentEvent

↓

Listener A

Send Notification

↓

Listener B

Update Statistics

↓

Listener C

Broadcast

↓

Listener D

Audit Log
```

This is encouraged.

Do not merge unrelated responsibilities into one Listener.

---

# Event Versioning

Events are contracts.

Changing an Event payload is a breaking change.

When significant payload changes are required

Prefer

```text
MessageSentV2Event
```

instead of silently modifying

```text
MessageSentEvent
```

This prevents unexpected failures in existing Listeners.

---

# Event Dependency Rules

Allowed

```text
Event

↓

Model
```

Allowed

```text
Event

↓

DTO
```

Forbidden

```text
Event

↓

Database Query
```

Forbidden

```text
Event

↓

Notification
```

Forbidden

```text
Event

↓

Job
```

Forbidden

```text
Event

↓

Business Logic
```

---

# Event Transaction Rules

Business consistency always has priority.

Events should never execute before database consistency is guaranteed.

Correct

```text
Transaction

↓

Commit

↓

Dispatch Event
```

Incorrect

```text
Dispatch Event

↓

Rollback
```

Never dispatch Events from inside unfinished transactions.

---

# Event Flow Example

Conversation Module

```text
SendMessageData

↓

MessageService

↓

Store Message

↓

Commit

↓

MessageSentEvent

↓

SendUnreadNotificationListener

↓

UpdateConversationStatisticsListener

↓

BroadcastConversationListener
```

Rating Module

```text
SubmitRatingData

↓

RatingService

↓

Store Rating

↓

Commit

↓

RatingSubmittedEvent

↓

UpdateAverageRatingListener

↓

SendRatingNotificationListener

↓

UpdateDashboardStatisticsListener
```

QR Code Module

```text
GenerateQrCodeData

↓

QrCodeService

↓

Create QR Code

↓

Commit

↓

QrCodeGeneratedEvent

↓

GenerateQrImageJob

↓

WriteAuditLogListener
```

---

# Event Design Checklist

Every new Event should satisfy the following.

✓ Uses Past Tense

✓ Represents a completed action

✓ Contains only required payload

✓ Contains no business logic

✓ Is dispatched by a Service

✓ Is dispatched after Commit

✓ Has clear naming

✓ Belongs to a Domain folder

✓ Supports multiple Listeners

---

# Event Best Practices

✔ Keep Events immutable.

✔ Keep payloads minimal.

✔ Prefer Model payloads when relationships are needed.

✔ Register Events centrally.

✔ One Event should support many Listeners.

✔ Services publish Events.

✔ Listeners consume Events.

✔ Events should remain framework-independent whenever possible.

---

# Event Anti Patterns

Never

Controller

↓

Dispatch Event

Never

Event

↓

Database Query

Never

Event

↓

Business Logic

Never

Event

↓

Notification

Never

Event

↓

Job

Never

One Event

↓

One Giant Listener

Never

Generic Event names

```text
DataUpdatedEvent

SystemEvent

GeneralEvent
```

Events must describe concrete business facts.

# Listener Architecture

Listeners consume Events.

Listeners never initiate business operations.

Their responsibility is to react to completed business actions.

A Listener should answer only one question.

> "What should happen after this Event?"

Examples

```text
MessageSentEvent

↓

SendUnreadNotificationListener
```

```text
RatingSubmittedEvent

↓

UpdateAverageRatingListener
```

```text
ReportResolvedEvent

↓

SendReportResolvedNotificationListener
```

---

# Listener Responsibilities

Listeners may

* Send Notifications
* Dispatch Jobs
* Update Statistics
* Write Audit Logs
* Broadcast Events
* Synchronize Cached Data

Listeners must NOT

* Render Blade
* Return HTTP Response
* Access Request()
* Perform Validation
* Execute Primary Business Logic

Primary business logic always belongs to Services.

---

# Single Responsibility Principle

One Listener should perform one action.

Good

```text
MessageSentEvent

↓

SendUnreadNotificationListener
```

Good

```text
MessageSentEvent

↓

UpdateConversationStatisticListener
```

Bad

```text
MessageSentEvent

↓

Notification

↓

Statistics

↓

Analytics

↓

Broadcast

↓

Audit

↓

Cleanup

↓

Everything
```

If multiple side effects exist,

create multiple Listeners.

---

# Queue Strategy

Most Listeners should implement

ShouldQueue

Example

```php
class SendUnreadNotificationListener
implements ShouldQueue
```

Benefits

* Faster HTTP Response

* Better User Experience

* Independent Retry

* Fault Isolation

Exceptions

Very lightweight Listeners that only update memory or cache may remain synchronous.

---

# Retry Strategy

Queue retries should be configured according to task criticality.

Recommended

Notification

3 Attempts

Analytics

5 Attempts

Broadcast

3 Attempts

Export

5 Attempts

Image Processing

5 Attempts

QR Generation

5 Attempts

Retry logic should remain idempotent.

Executing the same Listener twice must not corrupt data.

---

# Failed Listener Strategy

Failed Listeners should never crash the original HTTP request.

Failure Flow

```text
Service

↓

Commit

↓

Dispatch Event

↓

Listener

↓

Queue Failure

↓

Retry

↓

Failed Jobs

↓

Developer Investigation
```

The business operation remains successful.

Only the side effect fails.

---

# Listener Independence

Listeners must never depend on one another.

Wrong

```text
Listener A

↓

Listener B
```

Correct

```text
Event

↓

Listener A

↓

Listener B

↓

Listener C
```

Every Listener subscribes directly to the Event.

---

# Redis Queue Integration

Redis acts as the queue backend.

Flow

```text
Event

↓

Listener

↓

Redis Queue

↓

Worker

↓

Execution
```

Advantages

* Fast

* Reliable

* Distributed

* Production Ready

Redis should never replace MySQL.

Redis stores temporary execution state only.

---

# Broadcast Preparation

Current project status

Broadcast

Future Phase

Current implementation

Database Notifications

Future architecture

```text
MessageSentEvent

↓

BroadcastConversationListener

↓

Broadcast Job

↓

WebSocket

↓

Frontend
```

The Event Architecture already supports future realtime implementation.

No Service modifications will be required.

---

# Notification Preparation

Notification flow

```text
MessageSentEvent

↓

SendUnreadNotificationListener

↓

NewMessageNotification

↓

Database
```

Future

```text
↓

Mail

↓

Push

↓

Broadcast
```

The Event remains unchanged.

Only additional Listeners are introduced.

---

# Analytics Preparation

Analytics should never run inside Services.

Correct

```text
RatingSubmittedEvent

↓

UpdateAnalyticsListener

↓

Redis

↓

Dashboard
```

Future analytics modules can subscribe to existing Events without modifying Services.

---

# Audit Trail Preparation

Audit logging follows the same architecture.

```text
EmployeeAssignedEvent

↓

WriteAuditLogListener

↓

Audit Log
```

Audit logging should remain a side effect.

Business logic should not depend on audit success.

---

# Listener Naming Convention

Every Listener must

* End with Listener
* Describe one responsibility
* Use Verb + Target

Examples

```text
SendUnreadNotificationListener

UpdateUnitStatisticsListener

BroadcastConversationListener

WriteAuditLogListener

GenerateQrImageListener
```

Avoid

```text
ProcessListener

MainListener

GeneralListener

DataListener
```

Names must clearly describe behavior.

---

# Listener Checklist

Every Listener should satisfy

✓ One responsibility

✓ Queueable when appropriate

✓ Small implementation

✓ Idempotent

✓ No HTTP dependencies

✓ No Validation

✓ No Rendering

✓ No Business Logic

✓ Easy to test

✓ Easy to remove

---

# Listener Best Practices

✔ Small classes

✔ Constructor Dependency Injection

✔ Queue heavy work

✔ Independent execution

✔ Idempotent operations

✔ Domain-specific naming

✔ Framework-friendly implementation

---

# Listener Anti Patterns

Never

Listener

↓

Service

↓

Another Event

↓

Another Listener

↓

Infinite Loop

Never

Listener

↓

Validation

Never

Listener

↓

Controller

Never

Listener

↓

Blade

Never

Listener

↓

Primary Business Logic

Never

Listener

↓

Large Transactions

Listeners should remain lightweight and reactive.


# Event Testing Strategy

Every Event should be testable independently.

Testing focuses on behavior rather than implementation.

Recommended tests

## Unit Tests

Verify

* Event payload
* Event construction
* Event properties

## Feature Tests

Verify

* Event dispatched after business operation
* Correct Listeners executed
* No duplicate dispatch

## Queue Tests

Verify

* Listener enters queue
* Queue executes successfully
* Retry mechanism works correctly

---

# Event Performance Standards

Event architecture should improve performance rather than reduce it.

Recommended

✔ Small Event payloads

✔ Queue expensive Listeners

✔ Multiple lightweight Listeners

✔ Independent execution

✔ Lazy loading when appropriate

Avoid

✖ Large payload objects

✖ Nested Event dispatch chains

✖ Blocking synchronous side effects

✖ Long-running Listeners

---

# Event Security Standards

Events must never expose sensitive information.

Never include

* Passwords
* API Keys
* Authentication Tokens
* Session IDs
* Private Files
* Sensitive Personal Data

Preferred payload

```text id="0c0dgi"
User ID

Conversation ID

Message ID
```

instead of

```text id="vcwz9k"
Entire Request Object

Entire Session Object
```

Only transport information required by downstream consumers.

---

# Event Development Workflow

Every new Event should follow this implementation process.

```text id="rg70sl"
Business Requirement

↓

Create Event

↓

Register Listener

↓

Dispatch from Service

↓

Queue Heavy Tasks

↓

Notification (Optional)

↓

Testing

↓

Documentation
```

Never implement an Event without first defining the business requirement it represents.

---

# Event Dependency Graph

The official dependency graph is

```text id="u0s8ga"
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

```text id="fcx4wz"
Service

↓

Event
```

Allowed

```text id="mf5srf"
Event

↓

Many Listeners
```

Allowed

```text id="i9mpn9"
Listener

↓

Notification
```

Allowed

```text id="8zwu8z"
Listener

↓

Job
```

Forbidden

```text id="n1t9ta"
Controller

↓

Event
```

Forbidden

```text id="gcjlwm"
Event

↓

Controller
```

Forbidden

```text id="4fivtv"
Listener

↓

Controller
```

Forbidden

```text id="x78ryp"
Notification

↓

Event
```

---

# Architecture Decision Records

## ADR-011

Services are the only layer allowed to publish Events.

Reason

Maintain strict separation between HTTP and Domain Logic.

---

## ADR-012

Events represent completed business actions only.

Reason

Events describe facts.

They do not request work.

---

## ADR-013

One Event may have multiple Listeners.

Reason

Independent side effects improve scalability.

---

## ADR-014

Listeners remain independent.

Reason

Prevent cascading dependencies.

---

## ADR-015

Heavy Listeners should implement ShouldQueue.

Reason

Reduce HTTP response time.

---

## ADR-016

Redis is the default queue backend.

Reason

Fast

Reliable

Scalable

---

## ADR-017

Notifications are triggered by Listeners.

Never directly from Services.

Reason

Loose coupling.

---

## ADR-018

Events should remain stable contracts.

Breaking payload changes require versioning.

Reason

Backward compatibility.

---

# AI Development Guidelines

Every AI Agent working on this project must follow these rules.

Always

✔ Dispatch Events from Services.

✔ Keep Event payloads minimal.

✔ Register Listeners centrally.

✔ Create one Listener per responsibility.

✔ Queue expensive Listeners.

✔ Use Past Tense naming.

Never

✖ Dispatch Events from Controllers.

✖ Place business logic inside Events.

✖ Couple Listeners together.

✖ Use generic Event names.

✖ Dispatch unnecessary Events.

✖ Modify existing Event payloads without considering compatibility.

---

# Event Review Checklist

Before introducing a new Event, verify the following.

Naming

□ Uses Past Tense

□ Ends with Event

Architecture

□ Dispatched from Service

□ After successful Commit

□ No business logic

Payload

□ Minimal

□ No sensitive data

□ Supports downstream consumers

Listeners

□ Independent

□ Single responsibility

□ Queueable if required

Testing

□ Unit tested

□ Feature tested

□ Queue tested

Documentation

□ Architecture updated

□ Business flow documented

---

# Future Roadmap

The current Event Architecture is designed to support future expansion without structural changes.

Planned integrations include

* Laravel Reverb
* WebSocket Broadcasting
* Push Notifications
* AI Recommendation Engine
* Audit Trail
* Activity Feed
* Live Dashboard
* Event Replay
* Event Metrics
* Distributed Workers

Existing Services will remain unchanged.

Only new Listeners will be added.

---

# Summary

The Event-Driven Architecture establishes a clear separation between business operations and application side effects.

Business operations remain inside Services.

Events communicate completed actions.

Listeners react independently.

Jobs execute heavy asynchronous work.

Notifications communicate with users.

This layered approach provides

* Loose Coupling
* High Maintainability
* Better Scalability
* Easier Testing
* Improved Performance
* Future Realtime Compatibility

The architecture is intentionally designed so that new features can be introduced by adding new Listeners rather than modifying existing Services.

---

# Final Statement

This document defines the official Event Architecture for the Rate Unit System.

All Events, Listeners, Jobs, and Notifications must comply with the principles established in this document.

Future architectural improvements should extend this architecture rather than replace it.

Consistency, simplicity, and long-term maintainability take precedence over short-term implementation convenience.

---

**End of Document**
