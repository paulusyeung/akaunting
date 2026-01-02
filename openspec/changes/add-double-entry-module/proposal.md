# Change: Add Double Entry Module

## Why
Akaunting's core provides simplified accounting (income/expense). Many businesses require a full double-entry ledger system for compliance and professional financial management. This module will fill that gap.

## What Changes
- **NEW** Module structure in `modules/DoubleEntry`.
- **NEW** Database schema for Journal Entries (General Ledger).
- **NEW** Chart of Accounts (COA) management.
- **NEW** Logic to ensure every transaction has balanced debits and credits.
- **NEW** Packing script to bundle the module for installation on other servers.

## Impact
- Affected specs: `double-entry-ledger` (NEW)
- Affected code: `modules/DoubleEntry/*`
