<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('/CSS_Styles/mainCss/base.css') }}">
    <link rel="stylesheet" href="{{ asset('/CSS_Styles/mainCss/billing.css') }}">
    <title>Billing</title>
</head>
<body>
    @include('includes.sidebar')

    <div class="content billing-page">
     
        <header class="page-header">
            <div class="titles">
                <h1>Billing Management</h1>
                <p class="subtitle">Manage invoices, payments, and billing operations</p>
            </div>
            <div class="actions">
                <button class="btn btn-ghost">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M7 13l3 3 6-7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Generate Report
                </button>
                <button class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    New Invoice
                </button>
            </div>
        </header>

        
        <div class="section-divider"></div>

    
        <section class="stats-grid">
            <article class="card kpi">
                <div class="kpi-head">
                    <span class="kpi-label">Total Revenue</span>
                    <span class="kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 3v18M7 8h6a4 4 0 010 8H7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
                <div class="kpi-value">$48,574</div>
                <div class="kpi-trend trend-up">+12.5% from last month</div>
            </article>

            <article class="card kpi">
                <div class="kpi-head">
                    <span class="kpi-label">Outstanding</span>
                    <span class="kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M3 12h5l4 7 4-14h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
                <div class="kpi-value">$12,450</div>
                <div class="kpi-trend">3 invoices pending</div>
            </article>

            <article class="card kpi">
                <div class="kpi-head">
                    <span class="kpi-label">Paid Invoices</span>
                    <span class="kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M5 12l4 4L19 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
                <div class="kpi-value">156</div>
                <div class="kpi-trend trend-up">+8 this week</div>
            </article>

            <article class="card kpi">
                <div class="kpi-head">
                    <span class="kpi-label">Active Customers</span>
                    <span class="kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
                <div class="kpi-value">1,247</div>
                <div class="kpi-trend trend-up">+23 new this month</div>
            </article>
        </section>

   
        <section class="panel">
            <div class="panel-header">
                <h3>Recent Invoices</h3>
            </div>
            <div class="panel-body table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a class="link-strong" href="#">INV-001</a></td>
                            <td>John Doe</td>
                            <td>$1,245.00</td>
                            <td>2024-01-15</td>
                            <td>2024-01-30</td>
                            <td><span class="pill status paid">Paid</span></td>
                            <td class="actions">
                                <button class="icon-btn" aria-label="View">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/></svg>
                                </button>
                                <button class="icon-btn" aria-label="Download">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 3v12m0 0l-4-4m4 4 4-4M5 21h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><a class="link-strong" href="#">INV-002</a></td>
                            <td>Jane Smith</td>
                            <td>$2,150.00</td>
                            <td>2024-01-18</td>
                            <td>2024-02-02</td>
                            <td><span class="pill status pending">Pending</span></td>
                            <td class="actions">
                                <button class="icon-btn" aria-label="View">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/></svg>
                                </button>
                                <button class="icon-btn" aria-label="Download">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 3v12m0 0l-4-4m4 4 4-4M5 21h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><a class="link-strong" href="#">INV-003</a></td>
                            <td>Bob Johnson</td>
                            <td>$875.00</td>
                            <td>2024-01-10</td>
                            <td>2024-01-25</td>
                            <td><span class="pill status overdue">Overdue</span></td>
                            <td class="actions">
                                <button class="icon-btn" aria-label="View">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/></svg>
                                </button>
                                <button class="icon-btn" aria-label="Download">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 3v12m0 0l-4-4m4 4 4-4M5 21h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</body>
</html>