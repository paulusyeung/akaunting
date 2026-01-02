## ADDED Requirements

### Requirement: Chart of Accounts Management
The system SHALL allow users to manage a Chart of Accounts, including creating accounts with specific types (Asset, Liability, Equity, Revenue, Expense).

#### Scenario: Create a new account
- **WHEN** user provides a name, code, and type
- **THEN** a new account is created in the COA

### Requirement: Balanced Journal Entry
The system SHALL ensure that every journal entry has at least two entries (one debit, one credit) and that the sum of debits equals the sum of credits.

#### Scenario: Save balanced entry
- **WHEN** user adds a debit of 100 and a credit of 100
- **THEN** the journal entry is saved successfully

#### Scenario: Reject unbalanced entry
- **WHEN** user adds a debit of 100 and a credit of 50
- **THEN** the system SHALL return a validation error

### Requirement: Module Packing
The system SHALL provide a mechanism to pack the `DoubleEntry` module into an installable package.

#### Scenario: Run packing command
- **WHEN** user runs the packing command
- **THEN** a distributable file is generated containing all module assets
