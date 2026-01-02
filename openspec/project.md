# Project Context

## Purpose
Akaunting is a free, open-source, and online accounting software designed for small businesses and freelancers. It allows users to manage their finances, track income and expenses, and generate reports.

## Tech Stack
- **Backend**: PHP 7.2+, Laravel 7 Framework
- **Frontend**: Vue.js 2, Bootstrap 4, Element UI
- **Database**: MySQL, PostgreSQL, SQLite (managed via Eloquent ORM)
- **API**: Dingo API
- **Build Tools**: Laravel Mix (Webpack)

## Project Conventions

### Code Style
- Follows Laravel and PSR-12 coding standards.
- Naming: `StudlyCase` for Classes, `camelCase` for methods/variables, `snake_case` for database columns.
- Uses `DocBlocks` for documentation.

### Architecture Patterns
- **Modular Monolith**: The core system is a Laravel application, but features are extended via a custom Module system located in the `modules/` directory.
- **Repository/Service Pattern**: Logic is often abstracted into Jobs, Listeners, and Service-like classes in `app/`.
- **Observer Pattern**: Extensive use of Eloquent Observers for side effects.

### Testing Strategy
- **PHPUnit**: Used for both Unit and Feature testing. Tests are located in the `tests/` directory.
- **Mocking**: Extensive use of Mockery for dependency isolation.

### Git Workflow
- Standard GitHub Flow: Feature branches, Pull Requests, and code reviews.
- Continuous Integration via GitHub Actions (inferred).

## Domain Context
- Accounting: Ledger, Journals (Double-entry foundation), Accounts Payable/Receivable.
- Financial Entities: Invoices, Bills, Customers, Vendors, Revenues, Payments.
- Multi-currency and Multi-company support.

## Important Constraints
- Compatibility with various PHP versions (7.2 to 8.x).
- Database-agnostic implementations (using Eloquent).
- Performance: Must handle large datasets for financial reporting.

## External Dependencies
- Akaunting App Store (for module updates).
- Payment Gateways (PayPal, Stripe, etc.).
- PDF Generation (DomPDF).
