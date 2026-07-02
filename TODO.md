# TODO - Integrated Payment & Accounting System (Laravel 11)

## Step 1 — Inspect existing code baseline
- [x] Read current migration + services (LedgerService, ReconciliationService)

## Step 2 — Align DB schema to spec (edit present ones)
- [x] Rewrite migration `2026_06_05_000000_create_payment_accounting_tables.php` to create the exact spec tables/columns:
  - [x] accounts
  - [x] transactions
  - [x] journal_entries
  - [x] invoices
  - [x] invoice_payments
  - [x] bank_statements
  - [x] processor_statements
  - [x] reconciliation_reports (+ flagged_items)
  - [x] exchange_rates
  - [x] customers
  - [x] subscriptions


## Step 3 — Models + relationships
- [ ] Update/create Eloquent models for new/renamed tables with relationships + casts.

## Step 4 — Services implementation
- [ ] Implement/upgrade LedgerService methods:
  - [ ] recordInboundPayment(Transaction $txn)
  - [ ] recordOutboundPayout(Transaction $txn)
  - [ ] recordSubscriptionRenewal(Subscription $sub)
- [ ] Implement/upgrade ReconciliationService:
  - [ ] runDailyReconciliation(Carbon $date)
  - [ ] auto-match bank deposits using ±1 day + amount proximity
  - [ ] threshold discrepancy logic
  - [ ] create ReconciliationReport
  - [ ] fire ReconciliationDiscrepancyDetected event
- [ ] Add FeeAccountingService::splitAndRecord(...)
- [ ] Add CurrencyService::getRate/convert/calculateFxGainLoss

## Step 5 — Events & listeners
- [ ] Create ReconciliationDiscrepancyDetected event

## Step 6 — Webhooks
- [ ] Create WebhookController with Stripe + PayPal verification + idempotent posting

## Step 7 — Queues / Horizon / Jobs
- [ ] Add jobs:
  - [ ] RunDailyReconciliationJob (02:00 schedule)
  - [ ] FetchExchangeRatesJob (hourly)
  - [ ] MatchBankStatementJob
  - [ ] ProcessSubscriptionRenewalJob
- [ ] Configure queues to use Redis + Horizon
- [ ] Update Kernel schedule

## Step 8 — API resources
- [ ] Add routes/api.php + controllers + FormRequests:
  - [ ] Transactions
  - [ ] Reconciliation
  - [ ] Invoices
  - [ ] Bank statements
  - [ ] Reports (P&L, FX, fees)

## Step 9 — Dashboard UI
- [ ] Update dashboard to required KPI + pages (Blade + Alpine/Chart.js)

## Step 10 — config/accounting.php + .env keys
- [ ] Create config/accounting.php per spec

## Step 11 — Tests
- [ ] Feature tests for:
  - [ ] Stripe webhook ledger + fee split
  - [ ] Reconciliation discrepancy detection
  - [ ] Multi-currency conversion + FX gain/loss
  - [ ] Bank statement auto-matching

