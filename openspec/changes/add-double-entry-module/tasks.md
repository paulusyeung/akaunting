# Tasks: Add Double Entry Module

## 1. Research & Scaffold
- [x] 1.1 Research exact module registration in Akaunting 2
- [x] 1.2 Scaffold basic module structure using `artisan module:make` (if available) or manual creation

## 2. Data Layer
- [x] 2.1 Create migrations for `double_entry_accounts`, `double_entry_journals`, and `double_entry_entries`
- [x] 2.2 Implement Models with necessary relationships
- [x] 2.3 Add validation logic for balanced entries (Debits == Credits)

## 3. Business Logic & UI
- [x] 3.1 Implement Chart of Accounts management UI
- [x] 3.2 Implement Journal Entry creation UI
- [x] 3.3 Add basic Ledger report

## 4. Packing & Deployment
- [x] 4.1 Create `module.json` and ensure all providers are registered
- [x] 4.2 Implement packing script/command
- [x] 4.3 Verify installation on a fresh instance (simulated)

## 5. Verification
- [x] 5.1 Write unit tests for balanced entry logic
- [x] 5.2 Manual verification of full flow
