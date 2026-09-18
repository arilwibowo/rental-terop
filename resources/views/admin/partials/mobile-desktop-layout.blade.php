    .sidebar {
        display: flex;
        flex-direction: column;
    }

    .sidebar .mt-5.pt-4.border-top {
        margin-top: auto !important;
    }

@media (max-width: 575.98px) {
    html {
        min-width: 360px;
    }

    body {
        overflow-x: auto;
        background: #f5f7fb;
    }

    .container-fluid {
        padding-left: 0;
        padding-right: 0;
    }

    .container-fluid > .row {
        flex-wrap: nowrap;
        margin-left: 0;
        margin-right: 0;
        align-items: stretch;
    }

    .sidebar {
        width: 88px !important;
        flex: 0 0 88px !important;
        max-width: 88px !important;
        min-height: 100vh;
        padding: .65rem .45rem !important;
    }

    .sidebar a.d-block.mb-4 {
        margin-bottom: .8rem !important;
    }

    .sidebar .fs-5 {
        font-size: .58rem !important;
        line-height: 1.15;
    }

    .sidebar .small {
        font-size: .46rem !important;
        line-height: 1.25;
        word-break: break-word;
    }

    .sidebar .text-white.fw-semibold {
        font-size: .48rem !important;
        line-height: 1.2;
        word-break: break-word;
    }

    .sidebar .nav {
        gap: .2rem !important;
    }

    .sidebar .nav-link {
        padding: .38rem .42rem !important;
        border-radius: 6px !important;
        font-size: .52rem;
        line-height: 1.15;
        white-space: nowrap;
    }

    .sidebar .mt-5.pt-4.border-top {
        margin-top: auto !important;
    }

    .sidebar .pt-4 {
        padding-top: .8rem !important;
    }

    .sidebar .btn-sm {
        padding: .18rem .25rem;
        font-size: .48rem;
        border-radius: 4px;
    }

    main.col-md-9,
    main.col-lg-10 {
        width: calc(100vw - 88px) !important;
        flex: 0 0 calc(100vw - 88px) !important;
        max-width: calc(100vw - 88px) !important;
        min-height: 100vh;
        padding: .75rem .6rem !important;
    }

    main h1,
    main .h3 {
        font-size: .95rem !important;
        line-height: 1.15;
    }

    main h5 {
        font-size: .72rem !important;
    }

    main p,
    main .text-muted,
    main .small,
    main li,
    main label,
    main .form-label {
        font-size: .55rem !important;
        line-height: 1.35;
    }

    main .btn {
        padding: .28rem .45rem;
        font-size: .52rem;
        border-radius: 5px;
    }

    main .form-control,
    main .form-select {
        min-height: auto;
        padding: .28rem .4rem;
        font-size: .55rem;
        border-radius: 6px;
    }

    .stat-card,
    .content-card {
        border-radius: 10px !important;
        box-shadow: 0 8px 22px rgba(15, 23, 42, .08) !important;
    }

    .stat-card .card-body,
    .content-card .card-body {
        padding: .65rem !important;
    }

    .display-6 {
        font-size: 1rem !important;
    }

    main > .d-flex.mb-4,
    main > .d-flex.flex-column {
        margin-bottom: .75rem !important;
        gap: .4rem !important;
    }

    main > .d-flex .d-flex.gap-2 {
        gap: .25rem !important;
    }

    main .row {
        --bs-gutter-x: .45rem;
        --bs-gutter-y: .45rem;
    }

    main .row.mb-4 > [class*="col-md-4"] {
        width: 33.333333%;
        flex: 0 0 auto;
    }

    main .row > [class*="col-lg-8"],
    main .row > [class*="col-lg-4"] {
        width: 100%;
        flex: 0 0 auto;
    }

    .table-responsive {
        overflow-x: visible;
    }

    .table {
        margin-bottom: 0;
        font-size: .52rem;
    }

    .table > :not(caption) > * > * {
        padding: .32rem .28rem;
        white-space: normal;
        vertical-align: middle;
    }

    .badge {
        font-size: .42rem;
        padding: .16rem .28rem;
    }

    .product-thumb,
    .qris-thumb {
        width: 34px !important;
        height: 34px !important;
        border-radius: 7px !important;
    }
}
