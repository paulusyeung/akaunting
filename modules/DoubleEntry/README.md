# Double Entry Module for Akaunting 2

A professional double-entry ledger system for Akaunting 2, providing balanced journal entries, comprehensive Chart of Accounts, and enterprise-grade financial reporting.

## 🎯 Key Features

### 1. Chart of Accounts (COA)

Manage professional accounts with five account types:

- **Asset** - Resources owned by the business
- **Liability** - Obligations owed to others
- **Equity** - Owner's stake in the business
- **Revenue** - Income from operations
- **Expense** - Costs of doing business

### 2. Balanced Journal Entries

Enforces strict double-entry accounting rules:

- **Real-time Validation**: UI prevents saving unbalanced entries (Debits must equal Credits)
- **Server-side Validation**: Business logic ensures data integrity using database transactions
- **Atomic Operations**: All-or-nothing approach prevents partial entries
- **Precision**: Uses `decimal(15,4)` for exact financial calculations
- **Entry Validation**: Each entry must have either debit OR credit (not both, not neither)

### 3. Comprehensive Reporting

- **General Ledger**: View detailed account activity with running balances
- **Trial Balance**: Verify accounting equation (Assets = Liabilities + Equity)
- **Income Statement**: Profitability report showing revenue, expenses, and net income for a period
- **Balance Sheet**: Financial position report showing assets, liabilities, and equity at a point in time
- **CSV Export**: Download ledger data for external analysis

### 4. Security & Compliance

- **Role-based Access Control**: Granular permissions for all operations
- **Audit Trail**: Tracks who created/updated each record
- **Request Validation**: Prevents invalid data entry
- **Company Isolation**: Multi-company support with proper data segregation
- **Company Ownership Validation**: All operations verify company ownership before execution
- **Account Code Uniqueness**: Account codes are unique per company (not globally)

### 5. Data Integrity

- **Account Deletion Protection**: Prevents deletion of accounts with existing journal entries
- **Custom Exception Handling**: Specialized exceptions for better error messages
- **Optimized Queries**: Eager loading prevents N+1 query problems in reports

### 6. Deployment Utility

Package the module for distribution:

```bash
php artisan double-entry:pack
```

Excludes test files, git directories, and other unnecessary files from the package.

---

## 📋 Technical Details

### Architecture

- **Framework**: Laravel-based modular structure
- **Frontend**: Blade templates with jQuery for dynamic interactions
- **Backend**: Laravel Jobs for business logic, Eloquent for data modeling
- **Database**: MySQL/PostgreSQL compatible

### Database Tables

- `double_entry_accounts` - Chart of Accounts
- `double_entry_journals` - Journal entry headers
- `double_entry_entries` - Individual debit/credit lines

### Permissions Required

- `read-double-entry-accounts`
- `create-double-entry-accounts`
- `update-double-entry-accounts`
- `delete-double-entry-accounts`
- `read-double-entry-journals`
- `create-double-entry-journals`
- `update-double-entry-journals`
- `delete-double-entry-journals`
- `read-double-entry-reports`

---

## 🚀 Installation

1. **Upload Module**

   ```bash
   # Place files in modules/DoubleEntry directory
   ```
2. **Enable Module**

   ```bash
   php artisan module:enable double-entry
   ```
3. **Run Migrations**

   ```bash
   php artisan migrate
   ```
4. **Configure Permissions**

   - Assign permissions to appropriate roles via Akaunting admin panel

---

## 📊 Usage

### Creating Accounts

1. Navigate to **Double Entry > Accounts**
2. Click **Add New**
3. Enter account details:
   - Name (e.g., "Cash")
   - Code (e.g., "1000") - Must be unique within your company
   - Type (Asset, Liability, Equity, Revenue, Expense)
4. Save

**Note**: Account codes must be unique per company. Different companies can use the same account codes.

### Creating Journal Entries

1. Navigate to **Double Entry > Journals**
2. Click **Add New**
3. Enter journal details:
   - Date
   - Reference (optional)
   - Description (optional)
4. Add entry lines:
   - Select account
   - Enter debit OR credit amount (not both, not neither)
5. Ensure debits = credits (submit button disabled until balanced)
6. Save

**Validation Rules**:

- Minimum 2 entries required
- Each entry must have either debit OR credit (not both)
- Total debits must equal total credits

### Viewing Reports

- **General Ledger**: Double Entry > Reports > General Ledger
- **Trial Balance**: Double Entry > Reports > Trial Balance
- **Income Statement**: Double Entry > Reports > Income Statement
  - Select date range (start date to end date)
  - View revenue, expenses, and net income for the period
- **Balance Sheet**: Double Entry > Reports > Balance Sheet
  - Select "as of" date
  - View assets, liabilities, equity, and financial position
- **Export**: Click "Export CSV" on any report

---

## 🔧 Advanced Features

### Balance Calculation

The module correctly handles different account types:

- **Assets & Expenses**: Debit normal balance (Debits - Credits)
- **Liabilities, Equity, Revenue**: Credit normal balance (Credits - Debits)

### Audit Trail

All accounts and journals track:

- `created_by` - User who created the record
- `updated_by` - User who last updated the record
- Timestamps for all changes

### Pagination

Large datasets are automatically paginated (25 records per page) for optimal performance.

### Query Optimization

- Eager loading of relationships prevents N+1 queries
- Company-scoped queries ensure data isolation
- Optimized report generation with proper relationship loading

---

## 🧪 Testing

Run the test suite:

```bash
php artisan test modules/DoubleEntry/Tests
```

**Test Coverage**:

- ✅ Balanced journal entry creation
- ✅ Unbalanced entry rejection
- ✅ Account balance calculation (all types)
- ✅ Transaction rollback on errors

---

## 📦 Deployment

### Packaging for Distribution

```bash
php artisan double-entry:pack
```

This generates `DoubleEntry.zip` in your project root, ready for installation on other servers.

**What's Excluded**:

- Test files (`Tests/` directory)
- Version control (`.git/` directory)
- Node modules (`node_modules/`)
- Environment files (`.env`)
- System files (`.DS_Store`, `Thumbs.db`)

### Installation on Target Server

1. Upload `DoubleEntry.zip` via Akaunting's module installer
2. Enable the module
3. Run migrations
4. Configure permissions

---

## 🔐 Security Features

- **Request Validation**: All inputs validated before processing
- **Authorization Middleware**: Permission checks on every controller action
- **SQL Injection Protection**: Eloquent ORM prevents SQL injection
- **CSRF Protection**: Laravel's built-in CSRF protection
- **Transaction Safety**: Database transactions ensure data integrity
- **Company Ownership Validation**: All operations verify company ownership
- **Account Deletion Protection**: Prevents accidental deletion of accounts with entries

---

## 📈 Performance

- **Eager Loading**: Relationships loaded efficiently to prevent N+1 queries
- **Pagination**: Large datasets handled gracefully
- **Indexes**: Database indexes on foreign keys and company_id for fast queries
- **Decimal Precision**: Financial calculations use exact decimal arithmetic
- **Optimized Reports**: Efficient query patterns for financial reports

---

## 🆕 Version History

### v1.3.0 (2026-01-03)

**Enhancements**:

- ✅ **Company-scoped Account Codes**: Account codes are now unique per company, not globally
- ✅ **Account Deletion Protection**: Prevents deletion of accounts with existing journal entries
- ✅ **Enhanced Entry Validation**: Each entry must have either debit OR credit (not both, not neither)
- ✅ **Company Ownership Validation**: All controller actions verify company ownership
- ✅ **Custom Exception Handling**: Specialized `UnbalancedJournalException` for better error handling
- ✅ **Query Optimization**: Eager loading of relationships in all reports to prevent N+1 queries
- ✅ **Constants Extraction**: Balance tolerance extracted to `Balance::FLOAT_TOLERANCE` constant
- ✅ **Improved Pack Command**: Excludes test files, git directories, and unnecessary files from packages
- ✅ **Explicit Company Scoping**: All queries explicitly scope by company_id for better data isolation

**Bug Fixes**:

- ✅ Fixed account code uniqueness to be company-scoped
- ✅ Fixed potential N+1 queries in reports
- ✅ Fixed missing company validation in edit/update/destroy methods

### v1.2.0 (2026-01-03)

**New Features**:

- ✅ Added Income Statement report (Profit & Loss)
- ✅ Added Balance Sheet report
- ✅ Date range filtering for Income Statement
- ✅ Point-in-time reporting for Balance Sheet
- ✅ Net income automatically calculated and included in equity

**Improvements**:

- ✅ Complete financial reporting suite
- ✅ Professional accounting standards compliance
- ✅ Enhanced translation support for financial reports

### v1.1.0 (2026-01-03)

**Enhancements**:

- ✅ Added comprehensive request validation
- ✅ Implemented role-based access control
- ✅ Fixed balance calculation for different account types
- ✅ Added audit trail (created_by, updated_by)
- ✅ Implemented pagination for large datasets
- ✅ Added Trial Balance report
- ✅ Added CSV export functionality
- ✅ Created test suite for core functionality
- ✅ Changed to decimal precision for financial accuracy

**Bug Fixes**:

- ✅ Fixed missing `enabled()` scope
- ✅ Fixed `company_id` injection in journal creation
- ✅ Fixed route naming consistency

### v1.0.0 (2026-01-02)

- Initial release
- Basic Chart of Accounts
- Journal entry creation
- General Ledger report
- Module packing command

---

## 🤝 Support

For issues, feature requests, or questions:

1. Check existing documentation
2. Review test files for usage examples
3. Contact module maintainer

---

## 📄 License

This module follows Akaunting's licensing terms.

---

## 🎓 Accounting Principles

This module follows **Generally Accepted Accounting Principles (GAAP)**:

- **Double-entry bookkeeping**: Every transaction affects at least two accounts
- **Accounting equation**: Assets = Liabilities + Equity
- **Accrual basis**: Transactions recorded when they occur, not when cash changes hands
- **Consistency**: Account types have consistent normal balances

---

## 🔍 Code Quality

### Architecture Patterns

- **Separation of Concerns**: Business logic in Jobs, data access in Models
- **Observer Pattern**: Audit trail handled via model observers
- **Custom Exceptions**: Specialized exceptions for domain-specific errors
- **Constants**: Magic numbers extracted to named constants

### Best Practices

- Company-scoped queries for multi-tenant support
- Eager loading to prevent N+1 queries
- Transaction safety for data integrity
- Comprehensive validation at multiple layers

---

**Built with ❤️ for professional accounting in Akaunting 2**
