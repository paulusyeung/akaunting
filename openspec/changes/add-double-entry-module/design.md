# Design: Double Entry Module

## Context
Akaunting 2 uses a modular monolith architecture. Core models like `Transaction` are designed for single-entry accounting. The double-entry module will introduce a parallel system or extend the existing one to allow professional ledger entries.

## Goals
- Support full Chart of Accounts.
- Enforce balanced journal entries (Debits = Credits).
- Provide a way to pack the module into a `.zip` or similar for distribution.

## Decisions

### Data Model
- **Account**: Extend or create a new `DoubleEntryAccount` to represent the COA.
- **Journal**: Represent a grouping of entries.
- **Entry**: Individual debit or credit record.

### Module Structure
- Follow the standard Akaunting module pattern:
  - `Providers`: Register routes, views, translations.
  - `Database/Migrations`: For new tables.
  - `Models`: For ledger entities.
  - `Http/Controllers`: For UI interactions.

### Packing Mechanism
- A custom artisan command `php artisan double-entry:pack` to bundle the module files into a distributable format.

## Risks / Trade-offs
- **Complexity**: Double entry is more complex than simple income/expense.
- **Migration**: Syncing existing transactions to the double-entry system might be complex (kept as non-goal for MVP).

## Open Questions
- Should we use a separate table for COA or repurpose `accounts` and `categories`?
- Recommendation: Use separate tables to avoid breaking core functionality.
