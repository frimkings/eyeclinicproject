<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="m-0">New Order</h1>
                    <small class="text-muted">Create a customer order, preview the receipt, then save.</small>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">New Order</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            @if ($savedOrder)
                <div class="alert alert-success d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $savedOrder->order_number }}</strong> has been saved.
                        <span class="ml-2">Status: {{ $savedOrder->status }}</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-success mr-2" onclick="window.print()">
                            <i class="fa fa-print mr-1"></i> Print Receipt
                        </button>
                        <button type="button" class="btn btn-sm btn-success" wire:click="resetOrderForm">
                            <i class="fa fa-plus mr-1"></i> New Order
                        </button>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="card jw-card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 font-weight-bold">Customer</h5>
                        </div>
                        <div class="card-body">
                            <div class="btn-group btn-group-toggle mb-3" data-toggle="buttons">
                                <label class="btn btn-outline-primary {{ $customerMode === 'existing' ? 'active' : '' }}">
                                    <input type="radio" wire:model="customerMode" value="existing"> Existing customer
                                </label>
                                <label class="btn btn-outline-primary {{ $customerMode === 'new' ? 'active' : '' }}">
                                    <input type="radio" wire:model="customerMode" value="new"> New customer
                                </label>
                            </div>

                            @if ($customerMode === 'existing')
                                <div class="form-row">
                                    <div class="form-group col-md-5">
                                        <label>Search</label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="customerSearch" placeholder="Name, phone, or customer no.">
                                    </div>
                                    <div class="form-group col-md-7">
                                        <label>Customer <span class="text-danger">*</span></label>
                                        <select class="form-control @error('customerId') is-invalid @enderror" wire:model="customerId">
                                            <option value="">Select customer</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">
                                                    {{ $customer->name }} - {{ $customer->phone ?: $customer->customer_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('customerId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            @else
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('customerName') is-invalid @enderror" wire:model.defer="customerName">
                                        @error('customerName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Phone</label>
                                        <input type="text" class="form-control @error('customerPhone') is-invalid @enderror" wire:model.defer="customerPhone">
                                        @error('customerPhone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email</label>
                                        <input type="email" class="form-control @error('customerEmail') is-invalid @enderror" wire:model.defer="customerEmail">
                                        @error('customerEmail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Address</label>
                                        <input type="text" class="form-control @error('customerAddress') is-invalid @enderror" wire:model.defer="customerAddress">
                                        @error('customerAddress') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card jw-card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 font-weight-bold">Order Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-7">
                                    <label>Service / Package <span class="text-danger">*</span></label>
                                    <select class="form-control @error('planId') is-invalid @enderror" wire:model="planId">
                                        <option value="">Select package</option>
                                        @foreach ($plans as $plan)
                                            <option value="{{ $plan->id }}">{{ $plan->display_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('planId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group col-md-5">
                                    <label>Number of pieces <span class="text-danger">*</span></label>
                                    <input type="number" min="1" step="1" class="form-control @error('pieces') is-invalid @enderror" wire:model="pieces">
                                    @error('pieces') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Add-ons</label>
                                <div class="row">
                                    @foreach ($addOns as $key => $addOn)
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox jw-addon">
                                                <input type="checkbox" class="custom-control-input" id="addon-{{ $key }}" wire:model="selectedAddOns" value="{{ $key }}">
                                                <label class="custom-control-label d-flex justify-content-between" for="addon-{{ $key }}">
                                                    <span>{{ $addOn['name'] }}</span>
                                                    <strong>GHS {{ number_format($addOn['price'], 2) }}</strong>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('selectedAddOns.*') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Service Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('serviceType') is-invalid @enderror" wire:model="serviceType">
                                        <option>Walk-in</option>
                                        <option>Pickup</option>
                                        <option>Delivery</option>
                                    </select>
                                    @error('serviceType') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Zone</label>
                                    <select class="form-control @error('zone') is-invalid @enderror" wire:model="zone" @if($serviceType === 'Walk-in') disabled @endif>
                                        <option value="">Select zone</option>
                                        @foreach ($zones as $zoneName => $fee)
                                            <option value="{{ $zoneName }}">{{ $zoneName }} - GHS {{ number_format($fee, 2) }}</option>
                                        @endforeach
                                    </select>
                                    @error('zone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" rows="3" wire:model.defer="notes" placeholder="Color, stains, special handling, delivery direction..."></textarea>
                                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label>Clothing Photo</label>
                                <input type="file" class="form-control-file @error('photo') is-invalid @enderror" wire:model="photo" accept="image/*">
                                @error('photo') <div class="text-danger small">{{ $message }}</div> @enderror
                                <div wire:loading wire:target="photo" class="small text-muted mt-2">Uploading photo...</div>
                                @if ($photo)
                                    <div class="mt-3">
                                        <img src="{{ $photo->temporaryUrl() }}" alt="Clothing preview" class="jw-photo-preview">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card jw-card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 font-weight-bold">Payment</h5>
                        </div>
                        <div class="card-body">
                            <div class="custom-control custom-switch mb-3">
                                <input type="checkbox" class="custom-control-input" id="recordPayment" wire:model="recordPayment">
                                <label class="custom-control-label" for="recordPayment">Record payment now</label>
                            </div>

                            @if ($recordPayment)
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Amount Paid</label>
                                        <input type="number" min="0" step="0.01" class="form-control @error('paidAmount') is-invalid @enderror" wire:model.defer="paidAmount">
                                        @error('paidAmount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Method</label>
                                        <select class="form-control @error('paymentMethod') is-invalid @enderror" wire:model="paymentMethod">
                                            <option value="cash">Cash</option>
                                            <option value="mobile_money">Mobile Money</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="credit">Credit</option>
                                        </select>
                                        @error('paymentMethod') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Reference</label>
                                        <input type="text" class="form-control @error('paymentReference') is-invalid @enderror" wire:model.defer="paymentReference">
                                        @error('paymentReference') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card jw-card jw-summary-card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 font-weight-bold">Total</h5>
                        </div>
                        <div class="card-body">
                            <div class="jw-total-row">
                                <span>Package</span>
                                <strong>GHS {{ number_format($totals['package_total'], 2) }}</strong>
                            </div>
                            <div class="jw-total-row">
                                <span>Add-ons</span>
                                <strong>GHS {{ number_format($totals['add_ons_total'], 2) }}</strong>
                            </div>
                            <div class="jw-total-row">
                                <span>Zone fee</span>
                                <strong>GHS {{ number_format($totals['zone_fee'], 2) }}</strong>
                            </div>
                            <div class="jw-total-row jw-grand-total">
                                <span>Total</span>
                                <strong>GHS {{ number_format($totals['total'], 2) }}</strong>
                            </div>

                            <button type="button" class="btn btn-primary btn-block mt-3" wire:click="previewReceipt" wire:loading.attr="disabled">
                                <i class="fa fa-receipt mr-1"></i> Preview Receipt
                            </button>

                            @if ($showReceiptPreview && !$savedOrder)
                                <button type="button" class="btn btn-success btn-block mt-2" wire:click="saveOrder" wire:loading.attr="disabled">
                                    <i class="fa fa-save mr-1"></i> Save Order
                                </button>
                            @endif
                        </div>
                    </div>

                    @if ($showReceiptPreview || $savedOrder)
                        <div class="card jw-card receipt-print-area">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 font-weight-bold">Receipt Preview</h5>
                                @if ($savedOrder)
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                                        <i class="fa fa-print"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="card-body jw-receipt">
                                <div class="text-center mb-3">
                                    <h4 class="mb-0">{{ $savedOrder->order_number ?? 'JW-' . now()->format('Ymd') . '-0000' }}</h4>
                                    <small class="text-muted">{{ now()->format('d M Y, h:i A') }}</small>
                                </div>

                                <div class="mb-3">
                                    <div><strong>Customer:</strong>
                                        @if ($savedOrder)
                                            {{ $savedOrder->customer->name ?? 'Customer' }}
                                        @elseif ($customerMode === 'existing')
                                            {{ optional($customers->firstWhere('id', (int) $customerId))->name ?? 'Selected customer' }}
                                        @else
                                            {{ $customerName ?: 'New customer' }}
                                        @endif
                                    </div>
                                    <div><strong>Service:</strong> {{ $serviceType }}</div>
                                    @if ($serviceType !== 'Walk-in')
                                        <div><strong>Zone:</strong> {{ $zone ?: '-' }}</div>
                                    @endif
                                    <div><strong>Pieces:</strong> {{ $pieces }}</div>
                                </div>

                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td>Package</td>
                                            <td class="text-right">GHS {{ number_format($totals['package_total'], 2) }}</td>
                                        </tr>
                                        @foreach ($this->selectedAddOnRows() as $addOn)
                                            <tr>
                                                <td>{{ $addOn['name'] }} x {{ $addOn['quantity'] }}</td>
                                                <td class="text-right">GHS {{ number_format($addOn['line_total'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>Zone fee</td>
                                            <td class="text-right">GHS {{ number_format($totals['zone_fee'], 2) }}</td>
                                        </tr>
                                        <tr class="font-weight-bold">
                                            <td>Total</td>
                                            <td class="text-right">GHS {{ number_format($totals['total'], 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Paid</td>
                                            <td class="text-right">GHS {{ number_format($recordPayment ? min((float) ($paidAmount ?: 0), $totals['total']) : 0, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                @if ($notes)
                                    <div class="small">
                                        <strong>Notes:</strong> {{ $notes }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .jw-card {
            border: 1px solid #dce5f0;
            border-radius: 8px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, .06);
        }

        .jw-summary-card {
            position: sticky;
            top: 12px;
        }

        .jw-addon {
            border: 1px solid #e3eaf3;
            border-radius: 8px;
            padding: .65rem .85rem .65rem 2.25rem;
            background: #fbfdff;
        }

        .jw-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .45rem 0;
            border-bottom: 1px solid #eef2f7;
        }

        .jw-grand-total {
            font-size: 1.15rem;
            border-bottom: 0;
        }

        .jw-photo-preview {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #dce5f0;
        }

        .jw-receipt {
            font-size: .92rem;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .receipt-print-area,
            .receipt-print-area * {
                visibility: visible;
            }

            .receipt-print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
                border: 0;
            }
        }
    </style>
</div>
