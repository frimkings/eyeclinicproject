<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="m-0">Payments <span class="badge badge-secondary">{{ $payments->total() }}</span></h1>
                    <small class="text-muted">Record collections, track balances, refund payments, and reprint receipts.</small>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Payments</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-5">
                    <div class="card jw-pay-card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 font-weight-bold">Record Payment</h5>
                        </div>
                        <form wire:submit.prevent="recordPayment">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Order</label>
                                    <select class="form-control @error('orderId') is-invalid @enderror" wire:model="orderId">
                                        <option value="">Select unpaid / part-paid order</option>
                                        @foreach ($openOrders as $order)
                                            <option value="{{ $order->id }}">
                                                {{ $order->order_number }} - {{ $order->customer->name ?? 'Customer' }} - Balance GHS {{ number_format($this->balanceDue($order), 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('orderId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label>Payment Type</label>
                                    <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                        <label class="btn btn-outline-primary {{ $paymentMode === 'full' ? 'active' : '' }}">
                                            <input type="radio" wire:model="paymentMode" value="full"> Full payment
                                        </label>
                                        <label class="btn btn-outline-primary {{ $paymentMode === 'part' ? 'active' : '' }}">
                                            <input type="radio" wire:model="paymentMode" value="part"> Part payment
                                        </label>
                                    </div>
                                    @error('paymentMode') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Amount Paid</label>
                                        <input type="number" step="0.01" min="0.01" class="form-control @error('amount') is-invalid @enderror" wire:model.defer="amount" @if($paymentMode === 'full') readonly @endif>
                                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Method</label>
                                        <select class="form-control @error('paymentMethod') is-invalid @enderror" wire:model="paymentMethod">
                                            @foreach ($paymentMethods as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('paymentMethod') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Reference</label>
                                    <input type="text" class="form-control @error('reference') is-invalid @enderror" wire:model.defer="reference" placeholder="MoMo transaction ID, bank ref, note...">
                                    @error('reference') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="card-footer bg-white text-right">
                                <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                    <i class="fa fa-receipt mr-1"></i> Save & Print Receipt
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card jw-pay-card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bold">Daily Collections</h5>
                            <input type="date" class="form-control form-control-sm jw-date-input" wire:model="collectionDate">
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="jw-kpi">
                                        <span>Gross Collections</span>
                                        <strong>GHS {{ number_format($dailyGross, 2) }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="jw-kpi danger">
                                        <span>Refunds</span>
                                        <strong>GHS {{ number_format($dailyRefunds, 2) }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="jw-kpi success">
                                        <span>Net Collections</span>
                                        <strong>GHS {{ number_format($dailyGross - $dailyRefunds, 2) }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mt-3">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Method</th>
                                            <th class="text-center">Count</th>
                                            <th class="text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($dailyPayments as $row)
                                            <tr>
                                                <td>{{ $paymentMethods[$row->payment_method] ?? ucfirst(str_replace('_', ' ', $row->payment_method)) }}</td>
                                                <td class="text-center">{{ $row->count }}</td>
                                                <td class="text-right font-weight-bold">GHS {{ number_format((float) $row->total, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="text-center text-muted">No collections for this date.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card jw-pay-card">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label>Search</label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="search" placeholder="Customer, phone, receipt no., order no.">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <div class="form-group">
                                <label>From</label>
                                <input type="date" class="form-control" wire:model="dateFrom">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <div class="form-group">
                                <label>To</label>
                                <input type="date" class="form-control" wire:model="dateTo">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <div class="form-group">
                                <label>Method</label>
                                <select class="form-control" wire:model="methodFilter">
                                    <option value="">All methods</option>
                                    @foreach ($paymentMethods as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control" wire:model="typeFilter">
                                    <option value="">All</option>
                                    <option value="payment">Payments</option>
                                    <option value="refund">Refunds</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="clearFilters">
                            <i class="fa fa-sync-alt mr-1"></i> Reset
                        </button>
                        <div class="form-inline">
                            <label class="mr-2 text-muted small">Rows</label>
                            <select class="form-control form-control-sm" wire:model="perPage">
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card jw-pay-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 jw-pay-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Receipt</th>
                                    <th>Customer</th>
                                    <th>Order</th>
                                    <th>Type</th>
                                    <th>Method</th>
                                    <th class="text-right">Amount</th>
                                    <th class="text-right">Balance</th>
                                    <th>Cashier</th>
                                    <th>Date</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody wire:loading.class="text-muted">
                                @forelse ($payments as $payment)
                                    @php
                                        $order = $payment->order;
                                        $isRefund = $payment->type === 'refund';
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="font-weight-bold">{{ $payment->receipt_number }}</div>
                                            @if ($payment->reference)
                                                <small class="text-muted">{{ $payment->reference }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="font-weight-bold">{{ $order->customer->name ?? 'Customer removed' }}</div>
                                            <small class="text-muted">{{ $order->customer->phone ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $order->order_number }}</div>
                                            <small class="text-muted">{{ $order->plan->name ?? 'Package' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $isRefund ? 'badge-danger' : 'badge-success' }}">{{ $isRefund ? 'Refund' : 'Payment' }}</span>
                                        </td>
                                        <td>{{ $paymentMethods[$payment->payment_method] ?? ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                        <td class="text-right font-weight-bold {{ $isRefund ? 'text-danger' : 'text-success' }}">
                                            {{ $isRefund ? '-' : '' }}GHS {{ number_format((float) $payment->amount, 2) }}
                                        </td>
                                        <td class="text-right">GHS {{ number_format($this->balanceDue($order), 2) }}</td>
                                        <td>{{ $payment->receiver->name ?? 'System' }}</td>
                                        <td>
                                            <span>{{ optional($payment->paid_at)->format('d M Y') }}</span>
                                            <small class="d-block text-muted">{{ optional($payment->paid_at)->format('h:i A') }}</small>
                                        </td>
                                        <td class="text-right">
                                            <button type="button" class="btn btn-sm btn-outline-dark" wire:click="printReceipt({{ $payment->id }})">
                                                <i class="fa fa-print mr-1"></i> Reprint
                                            </button>
                                            @if (!$isRefund)
                                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="openRefundModal({{ $payment->id }})" @if($this->refundableAmount($payment) <= 0) disabled @endif>
                                                    <i class="fa fa-undo mr-1"></i> Refund
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-5">
                                            <i class="fa fa-receipt fa-3x d-block mb-3"></i>
                                            No payments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentRefundModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <form wire:submit.prevent="recordRefund">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Record Refund</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Refund Amount</label>
                            <input type="number" step="0.01" min="0.01" class="form-control @error('refundAmount') is-invalid @enderror" wire:model.defer="refundAmount">
                            @error('refundAmount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label>Reason</label>
                            <textarea class="form-control @error('refundReason') is-invalid @enderror" rows="4" wire:model.defer="refundReason"></textarea>
                            @error('refundReason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-undo mr-1"></i> Save Refund
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if ($printPayment)
        @php
            $receiptOrder = $printPayment->order;
            $receiptCustomer = $receiptOrder->customer;
            $isRefundReceipt = $printPayment->type === 'refund';
        @endphp
        <div class="jw-receipt-print">
            <div class="jw-receipt-paper">
                <div class="receipt-center">
                    <div class="receipt-brand">{{ $receiptSettings['name'] }}</div>
                    <div>{{ $receiptSettings['address'] }}</div>
                    <div>Tel: {{ $receiptSettings['phone'] }}</div>
                    <div class="receipt-title">{{ $isRefundReceipt ? 'REFUND RECEIPT' : 'PAYMENT RECEIPT' }}</div>
                </div>

                <div class="receipt-line"><span>Receipt No.</span><strong>{{ $printPayment->receipt_number }}</strong></div>
                <div class="receipt-line"><span>Customer</span><strong>{{ $receiptCustomer->name ?? 'Customer' }}</strong></div>
                <div class="receipt-line"><span>Order No.</span><strong>{{ $receiptOrder->order_number }}</strong></div>
                <div class="receipt-line"><span>Service</span><strong>{{ $receiptOrder->plan->name ?? 'Package' }} x {{ $receiptOrder->pieces }}</strong></div>

                @if (($receiptOrder->add_ons ?: []))
                    <div class="receipt-items">
                        @foreach (($receiptOrder->add_ons ?: []) as $addOn)
                            <div>{{ $addOn['name'] ?? 'Add-on' }} x {{ $addOn['quantity'] ?? 1 }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="receipt-total">
                    <span>{{ $isRefundReceipt ? 'Refund Amount' : 'Amount Paid' }}</span>
                    <strong>{{ $isRefundReceipt ? '-' : '' }}GHS {{ number_format((float) $printPayment->amount, 2) }}</strong>
                </div>
                <div class="receipt-line"><span>Balance</span><strong>GHS {{ number_format($this->balanceDue($receiptOrder), 2) }}</strong></div>
                <div class="receipt-line"><span>Payment Method</span><strong>{{ $paymentMethods[$printPayment->payment_method] ?? ucfirst(str_replace('_', ' ', $printPayment->payment_method)) }}</strong></div>
                <div class="receipt-line"><span>Cashier</span><strong>{{ $printPayment->receiver->name ?? 'System' }}</strong></div>
                <div class="receipt-line"><span>Date / Time</span><strong>{{ optional($printPayment->paid_at)->format('d M Y, h:i A') }}</strong></div>
                @if ($isRefundReceipt && $printPayment->refund_reason)
                    <div class="receipt-note">Reason: {{ $printPayment->refund_reason }}</div>
                @endif

                <div class="receipt-footer">Thank you for choosing JumpWash.</div>
            </div>
        </div>
    @endif

    <style>
        .jw-pay-card {
            border: 1px solid #dce5f0;
            border-radius: 8px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, .06);
        }

        .jw-date-input {
            max-width: 170px;
        }

        .jw-kpi {
            border: 1px solid #dce5f0;
            border-radius: 8px;
            padding: .9rem;
            background: #f8fbff;
        }

        .jw-kpi span {
            display: block;
            color: #5f6f86;
            font-size: .8rem;
        }

        .jw-kpi strong {
            display: block;
            font-size: 1.25rem;
            color: #0f172a;
        }

        .jw-kpi.success strong {
            color: #15803d;
        }

        .jw-kpi.danger strong {
            color: #b91c1c;
        }

        .jw-pay-table th {
            font-size: .74rem;
            letter-spacing: .03em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .jw-pay-table td {
            vertical-align: middle;
        }

        .jw-receipt-print {
            display: none;
        }

        .jw-receipt-paper {
            width: 80mm;
            padding: 4mm;
            color: #000;
            background: #fff;
            font-family: "Courier New", Courier, monospace;
            font-size: 11px;
            line-height: 1.35;
        }

        .receipt-center {
            text-align: center;
        }

        .receipt-brand {
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .receipt-title {
            margin-top: 5px;
            padding-top: 5px;
            border-top: 1px dashed #000;
            font-weight: 800;
        }

        .receipt-line,
        .receipt-total {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            border-top: 1px dashed #000;
            padding-top: 4px;
            margin-top: 4px;
        }

        .receipt-line strong {
            text-align: right;
        }

        .receipt-total {
            font-size: 13px;
            font-weight: 800;
        }

        .receipt-items {
            border-top: 1px dashed #000;
            margin-top: 4px;
            padding-top: 4px;
        }

        .receipt-note {
            border-top: 1px dashed #000;
            margin-top: 4px;
            padding-top: 4px;
        }

        .receipt-footer {
            text-align: center;
            border-top: 1px dashed #000;
            margin-top: 8px;
            padding-top: 6px;
        }

        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            body * {
                visibility: hidden;
            }

            .jw-receipt-print,
            .jw-receipt-print * {
                visibility: visible;
            }

            .jw-receipt-print {
                display: block;
                position: absolute;
                left: 0;
                top: 0;
                width: 80mm;
                margin: 0;
                padding: 0;
            }
        }
    </style>

    <script>
        window.addEventListener('show-payment-refund-modal', () => $('#paymentRefundModal').modal('show'));
        window.addEventListener('hide-payment-refund-modal', () => $('#paymentRefundModal').modal('hide'));
        window.addEventListener('print-payment-receipt', () => {
            setTimeout(() => window.print(), 250);
        });
    </script>
</div>
