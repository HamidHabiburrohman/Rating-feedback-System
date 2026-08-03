# 07_RULES_ARCHITECTURE.md

---

# Rules Architecture

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

This document defines the official Rule Architecture used throughout the backend.

Laravel Validation Rules are one of the core architectural components of this project.

Every reusable validation must be implemented as a dedicated Rule instead of being duplicated inside Controllers, FormRequests, Services, or Models.

Rules improve

* Reusability
* Maintainability
* Testability
* Readability
* Separation of Concerns

This document acts as the official reference for every custom Rule implemented in the project.

---

# Philosophy

Rules validate.

Rules do not perform business operations.

A Rule answers one question only.

> "Is this value valid?"

Nothing more.

Rules should never change application state.

---

# Core Principles

## 1. Validation Only

Rules exist only for validation.

Allowed

* Database lookup
* Cache lookup
* Read-only queries
* Configuration lookup

Forbidden

* Insert
* Update
* Delete
* Notification
* Queue
* Event
* Business Logic

---

## 2. Reusable

A Rule should be reusable across multiple FormRequests.

Correct

```text id="c8v4jl"
ValidQrToken

↓

Scan QR

↓

Attendance

↓

Rating

↓

Check In
```

Wrong

```text id="h8jqtc"
ScanQrValidationRule

(usable only once)
```

Rules belong to the domain.

Not to the page.

---

## 3. Independent

Rules must not depend on

* Controllers
* Services
* Blade
* JavaScript

Rules should remain framework-friendly.

---

## 4. Deterministic

The same input should always produce the same validation result.

Example

```text id="dz9dcp"
QR Token

↓

Valid

↓

true
```

Same token

↓

Always

↓

true

until the underlying data changes.

---

## 5. Side Effect Free

Validation should never modify data.

Forbidden

```text id="ijx56g"
Validate QR

↓

Update Database
```

Forbidden

```text id="i0s7z5"
Validate GPS

↓

Insert Log
```

Validation never changes application state.

---

# Why Rules?

Without Rules

```text id="r7wvbd"
Controller

↓

Validation

↓

Controller

↓

Validation

↓

Service

↓

Validation
```

Validation becomes duplicated.

With Rules

```text id="k0s5vn"
FormRequest

↓

Rule

↓

Validation
```

Reusable.

Clean.

Maintainable.

---

# Rule Lifecycle

Official flow

```text id="jlwm5r"
HTTP Request

↓

FormRequest

↓

Rule

↓

DTO

↓

Controller

↓

Service
```

Rules execute before business logic.

Services assume validated input.

---

# Folder Structure

Rules are organized by Domain.

```text id="vblt4y"
app/

└── Rules/

    ├── Attachment/

    ├── Employee/

    ├── GPS/

    ├── QRCode/

    ├── Rating/

    ├── Security/

    ├── Unit/

    └── User/
```

This structure scales naturally.

Avoid

```text id="xhmu2g"
Rules/

↓

Misc/

↓

Helpers/

↓

General/
```

---

# Domain Overview

Attachment

Responsible for

* Upload Validation
* Count Validation
* Size Validation

---

Employee

Responsible for

* Assignment Validation
* Employee Status

---

GPS

Responsible for

* Radius
* Coordinates
* Spoofing

---

QRCode

Responsible for

* Token
* Signature
* Assignment
* Expiration
* Status

---

Rating

Responsible for

* Rating Permission
* Cooldown
* Score Validation

---

Security

Responsible for

* API Token
* Signed Request
* Safe Filename

---

Unit

Responsible for

* Department Validation
* Unit Constraints

---

User

Responsible for

* Assignment
* Ownership
* Identity Validation

---

# Naming Convention

Rules should describe what is being validated.

Correct

```text id="1qx2x0"
ValidQrToken

ActiveQrCode

AlreadyRatedToday

WithinUnitRadius

SafeFilename
```

Incorrect

```text id="2w59p2"
ValidateRule

GeneralRule

RuleOne

RuleHelper
```

Rule names should answer

"What is being validated?"

---

# Rule Responsibilities

Rules MAY

✔ Query Models

✔ Query Database

✔ Query Cache

✔ Read Config

✔ Read Environment Variables

Rules MUST NOT

✖ Update Records

✖ Delete Records

✖ Dispatch Events

✖ Dispatch Jobs

✖ Send Notifications

✖ Render Views

✖ Call Services

Rules validate.

Nothing else.

# Rule Dependency Matrix

Rules exist inside the Validation Layer.

They should remain isolated from business logic.

Official dependency graph

```text
HTTP Request

↓

FormRequest

↓

Rule

↓

Database (Read Only)

↓

Validation Result
```

Rules should never continue into the business layer.

---

# Allowed Dependencies

Rules MAY depend on

* Models
* Database Query Builder
* Cache (Read Only)
* Config
* Carbon
* Enums
* Value Objects
* Collections

Example

```text
Rule

↓

Model

↓

Database
```

Example

```text
Rule

↓

Cache

↓

Redis
```

Read-only access only.

---

# Forbidden Dependencies

Rules MUST NEVER depend on

* Services
* Controllers
* Blade
* Notifications
* Jobs
* Events
* Listeners
* Policies
* JavaScript
* Session
* Response

Forbidden

```text
Rule

↓

Service
```

Forbidden

```text
Rule

↓

Notification
```

Forbidden

```text
Rule

↓

Job
```

Validation should never trigger application behavior.

---

# Constructor Standards

Rules may receive configuration through the constructor.

Example

```text
WithinUnitRadius

↓

Latitude

Longitude

Allowed Radius
```

Another example

```text
MaxAttachmentCount

↓

Maximum Count
```

Constructors should receive immutable values only.

Avoid passing Request objects or Services.

---

# State Management

Rules should remain stateless whenever possible.

Correct

```text
New Rule Instance

↓

Validate

↓

Destroy
```

Avoid storing temporary application state inside Rules.

---

# Database Query Standards

Database queries inside Rules should remain lightweight.

Good

* exists()
* count()
* value()
* where()

Avoid

* multiple joins
* nested loops
* large collections
* expensive aggregations

If validation requires heavy business processing,

move it into the Service layer.

---

# Cache Usage

Rules may read cache.

Rules must never write cache.

Correct

```text
Rule

↓

Cache::get()
```

Incorrect

```text
Rule

↓

Cache::put()
```

Validation should never modify infrastructure state.

---

# Redis Usage

Redis may be used only for temporary validation data.

Examples

* Rate limiting
* Cooldown validation
* Temporary tokens
* One-time validation cache

Redis must never become the primary validation source.

Database remains the source of truth.

---

# Error Message Standards

Every Rule should provide clear error messages.

Good

```text
The QR Code has expired.
```

Good

```text
You have already submitted a rating today.
```

Bad

```text
Validation failed.
```

Bad

```text
Error.
```

Messages should explain

* what failed
* why it failed

Avoid exposing implementation details.

---

# Localization

Rules should support localization.

Preferred

```text
validation.qrcode.expired
```

instead of

```text
"The QR Code has expired."
```

Use Laravel language files whenever possible.

---

# Rule Performance Standards

Validation should remain fast.

Target

* Single database lookup
* Indexed queries
* Read-only operations
* Small memory footprint

Avoid

* Loading large relationships
* Recursive validation
* Heavy loops
* Complex calculations

Validation happens before every request.

Performance matters.

---

# Rule Design Standards

Every Rule should answer exactly one validation question.

Good

```text
ActiveQrCode

↓

Is the QR Code active?
```

Good

```text
WithinUnitRadius

↓

Is the user inside the allowed radius?
```

Bad

```text
QrValidation

↓

Token

↓

Signature

↓

Expiration

↓

Assignment

↓

Permission

↓

Everything
```

Split complex validation into multiple Rules.

---

# Single Responsibility Principle

One Rule

↓

One Validation

Examples

Good

```text
ValidQrToken
```

Good

```text
QrNotExpired
```

Good

```text
AssignedQrCode
```

Avoid

```text
ValidateEverythingRule
```

Smaller Rules are easier to test and reuse.

---

# Rule Checklist

Every Rule should satisfy

✓ One responsibility

✓ Read-only

✓ Reusable

✓ Deterministic

✓ Lightweight

✓ Independent

✓ No side effects

✓ Clear error messages

✓ Supports localization

✓ Easy to test

---

# Best Practices

✔ Constructor Injection

✔ Read-only Queries

✔ Domain-based Organization

✔ Small Validation Logic

✔ Typed Properties

✔ Reusable Rules

✔ Localized Messages

✔ Stateless Design

✔ Indexed Database Queries

---

# Anti Patterns

Never

Rule

↓

Service

Never

Rule

↓

Notification

Never

Rule

↓

Job

Never

Rule

↓

Database Update

Never

Rule

↓

Dispatch Event

Never

Rule

↓

Delete Record

Never

Rule

↓

Render Blade

Rules exist only to validate.

# Rule Testing Strategy

Every Rule must be independently testable.

Rules should be validated through automated tests whenever possible.

Recommended tests

## Unit Tests

Verify

* Valid input
* Invalid input
* Edge cases
* Constructor parameters
* Error messages

Example

```text id="3dr5d1"
WithinUnitRadius

↓

Inside Radius

↓

Pass
```

```text id="2vdb0y"
WithinUnitRadius

↓

Outside Radius

↓

Fail
```

---

# GPS Rule Standards

GPS validation protects physical attendance and QR-based rating.

Current Rules

```text id="2d1q7j"
ValidCoordinates

WithinUnitRadius

LocationSpoofing
```

Responsibilities

## ValidCoordinates

Validate

* Latitude format
* Longitude format
* Coordinate range

Does NOT

* Check distance
* Check spoofing

---

## WithinUnitRadius

Validate

* Distance
* Allowed Radius

Uses

* Unit Coordinates
* User Coordinates

Does NOT

* Detect fake GPS
* Validate QR Code

---

## LocationSpoofing

Validate

* Mock Location
* Emulator Detection (Future)
* Impossible Location

Should remain independent.

---

# QR Code Rule Standards

QR validation consists of multiple small Rules.

Current Rules

```text id="k57oxt"
ValidQrToken

ActiveQrCode

AssignedQrCode

QrNotExpired

ValidQrSignature
```

Each Rule validates one thing.

Never combine them.

Example

Good

```text id="8cx5o0"
Token

↓

Signature

↓

Expiration

↓

Assignment
```

Bad

```text id="9nrzjr"
One Rule

↓

Everything
```

---

## ValidQrToken

Checks

* Token exists
* Token format

Does NOT

* Check expiration
* Check assignment

---

## ActiveQrCode

Checks

* Active status

Does NOT

* Validate token

---

## AssignedQrCode

Checks

* Assigned Unit
* Assignment validity

Does NOT

* Validate expiration

---

## QrNotExpired

Checks

* Expiration Date
* Expiration Time

Nothing more.

---

## ValidQrSignature

Checks

* Signature Integrity
* Hash Verification

Does NOT

* Check permissions

---

# Rating Rule Standards

Current Rules

```text id="6ljs7z"
CanRateUnit

AlreadyRatedToday

RatingCooldown

ValidRatingScore
```

Each Rule validates a single rating constraint.

---

## CanRateUnit

Checks

* Permission
* Eligibility

---

## AlreadyRatedToday

Checks

* Duplicate daily submission

---

## RatingCooldown

Checks

* Cooldown period

May use Redis for temporary lookup.

---

## ValidRatingScore

Checks

* Score range

Example

1–5

Nothing more.

---

# Attachment Rule Standards

Current Rules

```text id="ofgqcf"
ValidAttachment

MaxAttachmentCount

MaxAttachmentSize
```

---

## ValidAttachment

Checks

* MIME Type
* Extension

---

## MaxAttachmentCount

Checks

Maximum number of uploaded files.

---

## MaxAttachmentSize

Checks

Maximum file size.

Rules should not store uploaded files.

---

# Employee Rule Standards

Current Rules

```text id="s5oqz0"
ActiveEmployee

AssignedToUnit
```

---

## ActiveEmployee

Checks

* Employee Status

Only.

---

## AssignedToUnit

Checks

* Assignment relationship

Does not update assignments.

---

# Security Rule Standards

Current Rules

```text id="wywz7p"
SignedRequest

ValidApiToken

SafeFilename
```

---

## SignedRequest

Checks

Laravel signed URL integrity.

---

## ValidApiToken

Checks

Token validity.

Never refreshes tokens.

---

## SafeFilename

Checks

* Dangerous characters
* Reserved filenames
* Path traversal attempts

Never renames files.

---

# Rule Composition

Complex validation should be built by combining multiple Rules.

Example

```text id="j2z2r5"
Scan QR Request

↓

ValidQrToken

↓

ActiveQrCode

↓

QrNotExpired

↓

AssignedQrCode

↓

WithinUnitRadius

↓

LocationSpoofing
```

Each Rule remains independent.

---

# Rule Reusability

Rules should support multiple modules.

Example

```text id="nmnj98"
WithinUnitRadius

↓

Attendance

↓

QR Scan

↓

Rating

↓

Future Check-In
```

Avoid creating duplicate Rules for different modules.

---

# AI Development Guidelines

AI Agents should

✔ Reuse existing Rules whenever possible.

✔ Create new Rules only when validation responsibility is unique.

✔ Keep one Rule = one validation.

✔ Use constructor injection.

✔ Avoid duplicated validation logic.

✔ Keep Rule names descriptive.

AI Agents must NEVER

✖ Merge multiple responsibilities into one Rule.

✖ Perform business logic.

✖ Update database records.

✖ Dispatch Events.

✖ Call Services.

✖ Send Notifications.

---

# Architecture Decision Records

## ADR-021

Rules validate only.

Reason

Single Responsibility Principle.

---

## ADR-022

Rules remain read-only.

Reason

Validation should not modify application state.

---

## ADR-023

Large validation should be decomposed into multiple Rules.

Reason

Maintainability and reusability.

---

## ADR-024

Rules may query Models directly.

Reason

Read-only database access is acceptable.

---

## ADR-025

Rules must never depend on Services.

Reason

Prevent business logic leakage.

---

## ADR-026

Rules are organized by business domain.

Reason

Scalability.

---

## ADR-027

Validation must occur before entering the Service Layer.

Reason

Services should receive trusted input.

---

## ADR-028

Rule names must describe exactly one validation responsibility.

Reason

Clarity and consistency.

# Rule Performance Standards

Validation executes before every business operation.

For this reason, Rules must remain lightweight and efficient.

Recommended

✔ Indexed database lookups

✔ exists()

✔ value()

✔ count()

✔ Simple where() queries

✔ Small memory footprint

Avoid

✖ Full table scans

✖ Loading unnecessary relationships

✖ Large Collection processing

✖ Nested loops

✖ Heavy aggregations

Validation should never become a performance bottleneck.

---

# Rule Security Standards

Rules protect application integrity.

Every Rule should assume that incoming data is untrusted.

Always validate

* IDs
* UUIDs
* Coordinates
* Tokens
* Uploaded Files
* Numeric Ranges
* Enum Values

Never trust

* Hidden Inputs
* JavaScript Validation
* Browser State
* Client-generated values

Server-side validation is always authoritative.

---

# Rule Development Workflow

Every new Rule should follow the same implementation workflow.

```text id="w5z3yk"
Business Requirement

↓

Determine Validation Responsibility

↓

Create Rule

↓

Implement Validation Logic

↓

Write Error Message

↓

Register in FormRequest

↓

Testing

↓

Documentation
```

Rules should not be created without a clearly defined validation responsibility.

---

# Rule Review Checklist

Before merging a new Rule, verify the following.

Architecture

□ One responsibility only

□ Read-only

□ Independent

□ Domain-based folder

Dependencies

□ No Service dependency

□ No Controller dependency

□ No Notification

□ No Event

□ No Job

Validation

□ Deterministic

□ Lightweight

□ Reusable

□ Localized error message

Performance

□ Indexed query

□ Minimal database access

□ No unnecessary relationships

Security

□ Server-side validation

□ Reject invalid input

□ No state modification

Testing

□ Unit tested

□ Edge cases covered

Documentation

□ Naming follows convention

□ Domain placement is correct

---

# Rule Composition Strategy

Complex validation should always be composed from multiple focused Rules.

Example

```text id="e9kzq7"
Scan QR

↓

ValidQrToken

↓

ActiveQrCode

↓

QrNotExpired

↓

AssignedQrCode

↓

WithinUnitRadius

↓

LocationSpoofing
```

Each Rule remains reusable.

Future modules may reuse the same Rules without modification.

---

# Rule Lifecycle

Official lifecycle

```text id="kqxmbs"
HTTP Request

↓

FormRequest

↓

Custom Rules

↓

Validation Passed

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
```

Rules always execute before entering the business layer.

Once validation succeeds,

Services should never repeat the same validation.

---

# Future Rule Expansion

The architecture is prepared for future Rules.

Examples

QRCode

```text id="5wxvyr"
QrUsageLimit

QrDeviceValidation

QrIpRestriction
```

GPS

```text id="6h5h9n"
ValidAltitude

AllowedGeoFence

TrustedDeviceLocation
```

Rating

```text id="ykg9tr"
CanEditRating

RatingTimeWindow

MinimumCommentLength
```

Security

```text id="c5m8bn"
TrustedDevice

BlockedIPAddress

ValidCsrfContext
```

Employee

```text id="ths8lx"
EmployeeShiftActive

EmployeeHasPermission
```

New Rules should extend existing domains.

Avoid creating unnecessary new folders.

---

# AI Development Guidelines

Every AI assistant working on this project must follow these principles.

Always

✔ Reuse existing Rules before creating new ones.

✔ Keep one Rule = one validation.

✔ Organize Rules by Domain.

✔ Prefer composition over large Rules.

✔ Write descriptive names.

✔ Support localization.

✔ Keep validation read-only.

Never

✖ Create "ValidateEverythingRule"

✖ Perform business logic

✖ Modify database state

✖ Dispatch Events

✖ Dispatch Jobs

✖ Send Notifications

✖ Depend on Services

✖ Depend on Controllers

---

# Architecture Decision Records

## ADR-029

Every reusable validation belongs in a Custom Rule.

Reason

Prevent duplicated validation logic.

---

## ADR-030

Rules remain immutable during execution.

Reason

Predictable behavior.

---

## ADR-031

Rules never modify application state.

Reason

Validation is observational, not operational.

---

## ADR-032

Rule composition is preferred over monolithic validation.

Reason

Maintainability.

---

## ADR-033

Rules are organized by Domain.

Reason

Long-term scalability.

---

## ADR-034

Rules may query Models directly for read-only validation.

Reason

Simple architecture with minimal abstraction.

---

## ADR-035

Business decisions remain inside Services.

Rules validate only.

Reason

Clear separation of concerns.

---

## ADR-036

Every Rule should be independently testable.

Reason

Higher confidence and easier maintenance.

---

# Architecture Summary

The Rule Architecture establishes a dedicated validation layer between incoming requests and business logic.

Its primary goals are

* Reusability
* Predictability
* Performance
* Security
* Maintainability

Rules answer only one question:

"Is this value valid?"

Nothing more.

Business decisions belong to Services.

Side effects belong to Events and Listeners.

This separation ensures that validation remains simple, reusable, and scalable as the project grows.

---

# Final Statement

This document defines the official Rule Architecture for the Rate Unit System.

All validation logic across QR Code, GPS, Rating, Employee, Attachment, Security, Unit, User, and future modules must comply with the principles described in this document.

Future Rules should extend this architecture rather than introduce new validation patterns.

Consistency is considered a feature.

Simple, reusable Rules are preferred over complex validation logic.

---

**End of Document**
