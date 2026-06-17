<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="m-0">Orders <span class="badge badge-secondary">{{ $orders->total() }}</span></h1>
                    <small class="text-muted">Track work status, payments, receipts, and clothing tags.</small>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card orders-card">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label>Search</label>
                                <input type="text"
                                    class="form-control"
                                    wire:model.debounce.300ms="search"
                                    placeholder="Order no., customer name, phone">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" wire:model="statusFilter">
                                    <option value="">All statuses</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <div class="form-group">
                                <label>Service Type</label>
                                <select class="form-control" wire:model="serviceTypeFilter">
                                    <option value="">All types</option>
                                    @foreach ($serviceTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
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
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="clearFilters">
                                <i class="fa fa-sync-alt mr-1"></i> Reset
                            </button>
                            <a href="{{ route('orders.new') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus mr-1"></i> New Order
                            </a>
                        </div>
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

            <div class="card orders-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 orders-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-right">Balance</th>
                                    <th>Created</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody wire:loading.class="text-muted">
                                @forelse ($orders as $order)
                                    <tr>
                                        <td>
                                            <div class="font-weight-bold">{{ $order->order_number }}</div>
                                            <small class="text-muted">{{ $order->plan->name ?? 'No package' }} - {{ $order->pieces }} pcs</small>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold">{{ $order->customer->name ?? 'Customer removed' }}</div>
                                            <small class="text-muted">{{ $order->customer->phone ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <span>{{ $order->service_type }}</span>
                                            @if ($order->zone)
                                                <small class="d-block text-muted">{{ $order->zone }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $this->statusBadgeClass($order->status) }}">{{ $order->status }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $this->paymentBadgeClass($order->payment_status) }}">{{ ucfirst($order->payment_status) }}</span>
                                            <small class="d-block text-muted">Paid GHS {{ number_format((float) $order->paid_amount, 2) }}</small>
                                        </td>
                                        <td class="text-right font-weight-bold">GHS {{ number_format((float) ($order->total_amount ?? $order->total), 2) }}</td>
                                        <td class="text-right">
                                            @php $balance = $this->balanceDue($order); @endphp
                                            <span class="{{ $balance > 0 ? 'text-danger font-weight-bold' : 'text-success' }}">
                                                GHS {{ number_format($balance, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span>{{ optional($order->created_at)->format('d M Y') }}</span>
                                            <small class="d-block text-muted">{{ optional($order->created_at)->format('h:i A') }}</small>
                                        </td>
                                        <td class="text-right">
                                            <div class="orders-actions">
                                                <button type="button" class="btn btn-sm btn-outline-info" wire:click="viewOrder({{ $order->id }})">
                                                    <i class="fa fa-eye mr-1"></i> View
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="openEditOrder({{ $order->id }})" @if(!$this->canEdit($order)) disabled @endif>
                                                    <i class="fa fa-edit mr-1"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="openStatusModal({{ $order->id }})">
                                                    <i class="fa fa-tasks mr-1"></i> Status
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-success" wire:click="markAsPaid({{ $order->id }})" @if($balance <= 0 || $order->status === 'Cancelled') disabled @endif>
                                                    <i class="fa fa-check-circle mr-1"></i> Paid
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" wire:click="openPaymentModal({{ $order->id }})" @if($balance <= 0 || $order->status === 'Cancelled') disabled @endif>
                                                    <i class="fa fa-coins mr-1"></i> Balance
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-dark" wire:click="printReceipt({{ $order->id }})">
                                                    <i class="fa fa-print mr-1"></i> Receipt
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-dark" wire:click="printTag({{ $order->id }})">
                                                    <i class="fa fa-tag mr-1"></i> Tag
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="openCancelModal({{ $order->id }})" @if($order->status === 'Cancelled' || $order->status === 'Delivered') disabled @endif>
                                                    <i class="fa fa-ban mr-1"></i> Cancel
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-5">
                                            <i class="fa fa-box-open fa-3x mb-3 d-block"></i>
                                            No orders found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                @if ($selectedOrder)
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title">{{ $selectedOrder->order_number }}</h5>
                            <small class="text-muted">{{ $selectedOrder->customer->name ?? 'Customer removed' }}</small>
                        </div>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="font-weight-bold">Order</h6>
                                <dl class="row mb-0">
                                    <dt class="col-5">Status</dt>
                                    <dd class="col-7"><span class="badge {{ $this->statusBadgeClass($selectedOrder->status) }}">{{ $selectedOrder->status }}</span></dd>
                                    <dt class="col-5">Package</dt>
                                    <dd class="col-7">{{ $selectedOrder->plan->name ?? '-' }}</dd>
                                    <dt class="col-5">Pieces</dt>
                                    <dd class="col-7">{{ $selectedOrder->pieces }}</dd>
                                    <dt class="col-5">Service</dt>
                                    <dd class="col-7">{{ $selectedOrder->service_type }} {{ $selectedOrder->zone ? '(' . $selectedOrder->zone . ')' : '' }}</dd>
                                    <dt class="col-5">Notes</dt>
                                    <dd class="col-7">{{ $selectedOrder->notes ?: '-' }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h6 class="font-weight-bold">Payment</h6>
                                <dl class="row mb-0">
                                    <dt class="col-5">Total</dt>
                                    <dd class="col-7">GHS {{ number_format((float) ($selectedOrder->total_amount ?? $selectedOrder->total), 2) }}</dd>
                                    <dt class="col-5">Paid</dt>
                                    <dd class="col-7">GHS {{ number_format((float) $selectedOrder->paid_amount, 2) }}</dd>
                                    <dt class="col-5">Balance</dt>
                                    <dd class="col-7">GHS {{ number_format($this->balanceDue($selectedOrder), 2) }}</dd>
                                    <dt class="col-5">Payment</dt>
                                    <dd class="col-7"><span class="badge {{ $this->paymentBadgeClass($selectedOrder->payment_status) }}">{{ ucfirst($selectedOrder->payment_status) }}</span></dd>
                                    @if ($selectedOrder->status === 'Cancelled')
                                        <dt class="col-5">Cancel reason</dt>
                                        <dd class="col-7">{{ $selectedOrder->cancel_reason }}</dd>
                                    @endif
                                </dl>
                            </div>
                        </div>

                        <hr>
                        <h6 class="font-weight-bold">Add-ons</h6>
                        @forelse (($selectedOrder->add_ons ?: []) as $addOn)
                            <span class="badge badge-light border mr-1 mb-1">{{ $addOn['name'] ?? 'Add-on' }} - GHS {{ number_format((float) ($addOn['line_total'] ?? 0), 2) }}</span>
                        @empty
                            <span class="text-muted">No add-ons.</span>
                        @endforelse

                        <hr>
                        <h6 class="font-weight-bold">Payments</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Reference</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($selectedOrder->payments as $payment)
                                        <tr>
                                            <td>{{ optional($payment->paid_at)->format('d M Y, h:i A') }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                            <td>{{ $payment->reference ?: '-' }}</td>
                                            <td class="text-right">GHS {{ number_format((float) $payment->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-muted">No payments recorded.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" wire:click="printReceipt({{ $selectedOrder->id }})">
                            <i class="fa fa-print mr-1"></i> Receipt
                        </button>
                        <button type="button" class="btn btn-outline-dark" wire:click="printTag({{ $selectedOrder->id }})">
                            <i class="fa fa-tag mr-1"></i> Tag
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="editOrderModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog modal-lg" role="document">
            <form wire:submit.prevent="updateOrder">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Order</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-7">
                                <label>Package</label>
                                <select class="form-control @error('editPlanId') is-invalid @enderror" wire:model="editPlanId">
                                    <option value="">Select package</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}">{{ $plan->display_name }}</option>
                                    @endforeach
                                </select>
                                @error('editPlanId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-5">
                                <label>Pieces</label>
                                <input type="number" class="form-control @error('editPieces') is-invalid @enderror" min="1" wire:model="editPieces">
                                @error('editPieces') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <label>Add-ons</label>
                        <div class="row">
                            @foreach ($addOns as $key => $addOn)
                                <div class="col-md-6 mb-2">
                                    <div class="custom-control custom-checkbox orders-addon">
                                        <input type="checkbox" class="custom-control-input" id="edit-addon-{{ $key }}" wire:model="editSelectedAddOns" value="{{ $key }}">
                                        <label class="custom-control-label d-flex justify-content-between" for="edit-addon-{{ $key }}">
                                            <span>{{ $addOn['name'] }}</span>
                                            <strong>GHS {{ number_format($addOn['price'], 2) }}</strong>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-row mt-2">
                            <div class="form-group col-md-6">
                                <label>Service Type</label>
                                <select class="form-control @error('editServiceType') is-invalid @enderror" wire:model="editServiceType">
                                    @foreach ($serviceTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('editServiceType') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label>Zone</label>
                                <select class="form-control @error('editZone') is-invalid @enderror" wire:model="editZone" @if($editServiceType === 'Walk-in') disabled @endif>
                                    <option value="">Select zone</option>
                                    @foreach ($zones as $zoneName => $fee)
                                        <option value="{{ $zoneName }}">{{ $zoneName }} - GHS {{ number_format($fee, 2) }}</option>
                                    @endforeach
                                </select>
                                @error('editZone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea class="form-control @error('editNotes') is-invalid @enderror" rows="3" wire:model.defer="editNotes"></textarea>
                            @error('editNotes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="alert alert-light border d-flex justify-content-between mb-0">
                            <span>Updated Total</span>
                            <strong>GHS {{ number_format($editTotals['total'], 2) }}</strong>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save mr-1"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <form wire:submit.prevent="updateOrderStatus">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Status</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control @error('newStatus') is-invalid @enderror" wire:model="newStatus">
                                @foreach ($workflowStatuses as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                            @error('newStatus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check mr-1"></i> Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <form wire:submit.prevent="cancelOrder">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Order</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Cancellation Reason</label>
                            <textarea class="form-control @error('cancelReason') is-invalid @enderror" rows="4" wire:model.defer="cancelReason"></textarea>
                            @error('cancelReason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-ban mr-1"></i> Cancel Order
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <form wire:submit.prevent="recordBalancePayment">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Record Balance Payment</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" step="0.01" min="0.01" class="form-control @error('paymentAmount') is-invalid @enderror" wire:model.defer="paymentAmount">
                            @error('paymentAmount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label>Method</label>
                            <select class="form-control @error('paymentMethod') is-invalid @enderror" wire:model="paymentMethod">
                                <option value="cash">Cash</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="credit">Credit</option>
                            </select>
                            @error('paymentMethod') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label>Reference</label>
                            <input type="text" class="form-control @error('paymentReference') is-invalid @enderror" wire:model.defer="paymentReference">
                            @error('paymentReference') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-coins mr-1"></i> Record Payment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if ($printOrder)
        <div class="orders-print-area">
            @if ($printMode === 'tag')
                <div class="orders-tag">
                    <div class="tag-brand">JumpWash</div>
                    <div class="tag-order-number">{{ $printOrder->order_number }}</div>

                    <div class="tag-row">
                        <span>Customer</span>
                        <strong>{{ $printOrder->customer->name ?? 'Customer' }}</strong>
                    </div>
                    <div class="tag-row">
                        <span>Phone</span>
                        <strong>{{ $printOrder->customer->phone ?? '-' }}</strong>
                    </div>
                    <div class="tag-row">
                        <span>Package / Service</span>
                        <strong>{{ $printOrder->plan->name ?? 'Package' }} - {{ $printOrder->service_type }}</strong>
                    </div>
                    <div class="tag-row tag-row-split">
                        <div>
                            <span>Pieces</span>
                            <strong>{{ $printOrder->pieces }}</strong>
                        </div>
                        <div>
                            <span>Status</span>
                            <strong>{{ $printOrder->status }}</strong>
                        </div>
                    </div>
                    <div class="tag-row tag-row-split">
                        <div>
                            <span>Date Received</span>
                            <strong>{{ optional($printOrder->created_at)->format('d M Y') }}</strong>
                        </div>
                        <div>
                            <span>Expected</span>
                            <strong>{{ $this->expectedCompletionDate($printOrder) }}</strong>
                        </div>
                    </div>

                    <div class="tag-barcode" aria-label="Order number barcode style">
                        <div class="tag-barcode-bars"></div>
                        <div class="tag-barcode-text">{{ $printOrder->order_number }}</div>
                    </div>
                </div>
            @else
                <div class="orders-receipt">
                    <div class="text-center mb-3">
                        <h4 class="mb-0">{{ $printOrder->order_number }}</h4>
                        <small>{{ optional($printOrder->created_at)->format('d M Y, h:i A') }}</small>
                    </div>
                    <p class="mb-1"><strong>Customer:</strong> {{ $printOrder->customer->name ?? 'Customer' }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $printOrder->customer->phone ?? '-' }}</p>
                    <p class="mb-1"><strong>Service:</strong> {{ $printOrder->service_type }} {{ $printOrder->zone ? '(' . $printOrder->zone . ')' : '' }}</p>
                    <table class="table table-sm mt-3">
                        <tbody>
                            <tr>
                                <td>{{ $printOrder->plan->name ?? 'Package' }} x {{ $printOrder->pieces }}</td>
                                <td class="text-right">GHS {{ number_format((float) $printOrder->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Zone fee</td>
                                <td class="text-right">GHS {{ number_format((float) $printOrder->zone_fee, 2) }}</td>
                            </tr>
                            <tr class="font-weight-bold">
                                <td>Total</td>
                                <td class="text-right">GHS {{ number_format((float) ($printOrder->total_amount ?? $printOrder->total), 2) }}</td>
                            </tr>
                            <tr>
                                <td>Paid</td>
                                <td class="text-right">GHS {{ number_format((float) $printOrder->paid_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Balance</td>
                                <td class="text-right">GHS {{ number_format($this->balanceDue($printOrder), 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    @if ($printOrder->notes)
                        <p><strong>Notes:</strong> {{ $printOrder->notes }}</p>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <style>
        .orders-card {
            border: 1px solid #dce5f0;
            border-radius: 8px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, .06);
        }

        .orders-table th {
            font-size: .74rem;
            letter-spacing: .03em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .orders-table td {
            vertical-align: middle;
        }

        .orders-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: .25rem;
        }

        .badge-purple {
            color: #fff;
            background: #6f42c1;
        }

        .orders-addon {
            border: 1px solid #e3eaf3;
            border-radius: 8px;
            padding: .65rem .85rem .65rem 2.25rem;
            background: #fbfdff;
        }

        .orders-print-area {
            display: none;
        }

        .orders-tag {
            width: 58mm;
            min-height: 80mm;
            padding: 3mm;
            color: #000;
            background: #fff;
            border: 1px solid #000;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            line-height: 1.25;
        }

        .tag-brand {
            text-align: center;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            padding-bottom: 2mm;
            margin-bottom: 2mm;
        }

        .tag-order-number {
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 2mm;
        }

        .tag-row {
            border-bottom: 1px dashed #000;
            padding: 1.5mm 0;
        }

        .tag-row span {
            display: block;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .tag-row strong {
            display: block;
            font-size: 11px;
            font-weight: 800;
            word-break: break-word;
        }

        .tag-row-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2mm;
        }

        .tag-barcode {
            margin-top: 3mm;
            text-align: center;
        }

        .tag-barcode-bars {
            height: 15mm;
            border: 1px solid #000;
            background:
                repeating-linear-gradient(
                    90deg,
                    #000 0,
                    #000 1px,
                    #fff 1px,
                    #fff 3px,
                    #000 3px,
                    #000 5px,
                    #fff 5px,
                    #fff 7px
                );
        }

        .tag-barcode-text {
            margin-top: 1mm;
            font-family: "Courier New", Courier, monospace;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        @media print {
            @page {
                size: 58mm auto;
                margin: 2mm;
            }

            body * {
                visibility: hidden;
            }

            .orders-print-area,
            .orders-print-area * {
                visibility: visible;
            }

            .orders-print-area {
                display: block;
                position: absolute;
                left: 0;
                top: 0;
                width: 58mm;
                padding: 0;
            }

            .orders-receipt {
                max-width: 360px;
                margin: 0 auto;
            }
        }
    </style>

    <script>
        window.addEventListener('show-order-details-modal', () => $('#orderDetailsModal').modal('show'));
        window.addEventListener('show-edit-order-modal', () => $('#editOrderModal').modal('show'));
        window.addEventListener('hide-edit-order-modal', () => $('#editOrderModal').modal('hide'));
        window.addEventListener('show-status-modal', () => $('#statusModal').modal('show'));
        window.addEventListener('hide-status-modal', () => $('#statusModal').modal('hide'));
        window.addEventListener('show-cancel-modal', () => $('#cancelModal').modal('show'));
        window.addEventListener('hide-cancel-modal', () => $('#cancelModal').modal('hide'));
        window.addEventListener('show-payment-modal', () => $('#paymentModal').modal('show'));
        window.addEventListener('hide-payment-modal', () => $('#paymentModal').modal('hide'));
        window.addEventListener('print-orders-document', () => {
            setTimeout(() => window.print(), 250);
        });
    </script>
</div>
