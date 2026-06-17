<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="m-0">Pickup &amp; Delivery</h1>
                    <small class="text-muted">Assign zones, schedule pickups and deliveries, and track rider handoffs.</small>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pickup &amp; Delivery</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-5">
                    <div class="card pd-card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 font-weight-bold">Assign Order</h5>
                        </div>
                        <form wire:submit.prevent="assignTask">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Order</label>
                                    <select class="form-control @error('orderId') is-invalid @enderror" wire:model="orderId">
                                        <option value="">Select order</option>
                                        @foreach ($orders as $order)
                                            <option value="{{ $order->id }}">
                                                {{ $order->order_number }} - {{ $order->customer->name ?? 'Customer' }} - {{ $order->service_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('orderId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Zone</label>
                                        <select class="form-control @error('zoneCode') is-invalid @enderror" wire:model="zoneCode">
                                            @foreach ($zones as $code => $label)
                                                <option value="{{ $code }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('zoneCode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Rider / Staff</label>
                                        <select class="form-control @error('riderId') is-invalid @enderror" wire:model="riderId">
                                            <option value="">Unassigned</option>
                                            @foreach ($riders as $rider)
                                                <option value="{{ $rider->id }}">{{ $rider->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('riderId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Pickup Schedule</label>
                                        <input type="datetime-local" class="form-control @error('pickupScheduledAt') is-invalid @enderror" wire:model.defer="pickupScheduledAt">
                                        @error('pickupScheduledAt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Delivery Schedule</label>
                                        <input type="datetime-local" class="form-control @error('deliveryScheduledAt') is-invalid @enderror" wire:model.defer="deliveryScheduledAt">
                                        @error('deliveryScheduledAt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label>Delivery Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" rows="3" wire:model.defer="notes" placeholder="Hostel, room, landmark, preferred contact time..."></textarea>
                                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="card-footer bg-white text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-route mr-1"></i> Assign
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card pd-card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 font-weight-bold">Filters</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-row align-items-end">
                                <div class="form-group col-md-5">
                                    <label>Zone</label>
                                    <select class="form-control" wire:model="zoneFilter">
                                        <option value="">All zones</option>
                                        @foreach ($zones as $code => $label)
                                            <option value="{{ $code }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-5">
                                    <label>Status</label>
                                    <select class="form-control" wire:model="statusFilter">
                                        <option value="">All statuses</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <button type="button" class="btn btn-outline-secondary btn-block" wire:click="clearFilters">
                                        <i class="fa fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="row">
                                @foreach ($zones as $code => $label)
                                    <div class="col-md-4">
                                        <div class="pd-zone-count">
                                            <span>{{ $label }}</span>
                                            <strong>{{ ($tasksByZone[$code] ?? collect())->count() }}</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($zones as $code => $label)
                @php $zoneTasks = $tasksByZone[$code] ?? collect(); @endphp
                @if (!$zoneFilter || $zoneFilter === $code)
                    <div class="card pd-card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0 font-weight-bold">{{ $label }}</h5>
                                <small class="text-muted">{{ $zoneTasks->count() }} active order(s)</small>
                            </div>
                            <span class="badge badge-light border">{{ $code }}</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 pd-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Order</th>
                                            <th>Customer</th>
                                            <th>Pickup</th>
                                            <th>Delivery</th>
                                            <th>Rider</th>
                                            <th>Status</th>
                                            <th>Notes</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($zoneTasks as $task)
                                            @php
                                                $order = $task->order;
                                                $customer = $order->customer;
                                                $phone = $customer->phone ?? '';
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="font-weight-bold">{{ $order->order_number }}</div>
                                                    <small class="text-muted">{{ $order->plan->name ?? 'Package' }} - {{ $order->pieces }} pcs</small>
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold">{{ $customer->name ?? 'Customer removed' }}</div>
                                                    <small class="text-muted">{{ $phone ?: '-' }}</small>
                                                </td>
                                                <td>
                                                    <span>{{ optional($task->pickup_scheduled_at)->format('d M Y') ?: '-' }}</span>
                                                    <small class="d-block text-muted">{{ optional($task->pickup_scheduled_at)->format('h:i A') }}</small>
                                                    @if ($task->pickup_completed_at)
                                                        <small class="d-block text-success">Done {{ $task->pickup_completed_at->format('d M, h:i A') }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span>{{ optional($task->delivery_scheduled_at)->format('d M Y') ?: '-' }}</span>
                                                    <small class="d-block text-muted">{{ optional($task->delivery_scheduled_at)->format('h:i A') }}</small>
                                                    @if ($task->delivery_completed_at)
                                                        <small class="d-block text-success">Done {{ $task->delivery_completed_at->format('d M, h:i A') }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $task->rider->name ?? 'Unassigned' }}</td>
                                                <td><span class="badge {{ $this->statusBadgeClass($task->status) }}">{{ $task->status }}</span></td>
                                                <td class="pd-notes">{{ $task->notes ?: '-' }}</td>
                                                <td class="text-right">
                                                    <div class="pd-actions">
                                                        @if ($phone)
                                                            <a class="btn btn-sm btn-outline-success" href="tel:{{ $phone }}">
                                                                <i class="fa fa-phone mr-1"></i> Contact
                                                            </a>
                                                        @endif
                                                        <button type="button" class="btn btn-sm btn-outline-primary" wire:click="openTaskModal({{ $task->id }})">
                                                            <i class="fa fa-edit mr-1"></i> Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-info" wire:click="markPickupCompleted({{ $task->id }})" @if($task->pickup_completed_at || $task->status === 'Delivered') disabled @endif>
                                                            <i class="fa fa-check mr-1"></i> Pickup
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="markAtLaundry({{ $task->id }})" @if(!in_array($task->status, ['Picked Up'], true)) disabled @endif>
                                                            <i class="fa fa-store mr-1"></i> Laundry
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-success" wire:click="markReadyForDelivery({{ $task->id }})" @if(!in_array($task->status, ['At Laundry'], true)) disabled @endif>
                                                            <i class="fa fa-box mr-1"></i> Ready
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-warning" wire:click="markOutForDelivery({{ $task->id }})" @if(!in_array($task->status, ['Ready for Delivery'], true)) disabled @endif>
                                                            <i class="fa fa-motorcycle mr-1"></i> Out
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-dark" wire:click="markDeliveryCompleted({{ $task->id }})" @if($task->status !== 'Out for Delivery') disabled @endif>
                                                            <i class="fa fa-flag-checkered mr-1"></i> Delivered
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-4">No pickup or delivery orders in this zone.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="modal fade" id="pickupDeliveryModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog modal-lg" role="document">
            <form wire:submit.prevent="updateTask">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Pickup / Delivery</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Zone</label>
                                <select class="form-control @error('zoneCode') is-invalid @enderror" wire:model="zoneCode">
                                    @foreach ($zones as $code => $label)
                                        <option value="{{ $code }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('zoneCode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Status</label>
                                <select class="form-control @error('taskStatus') is-invalid @enderror" wire:model="taskStatus">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                                @error('taskStatus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Rider / Staff</label>
                                <select class="form-control @error('riderId') is-invalid @enderror" wire:model="riderId">
                                    <option value="">Unassigned</option>
                                    @foreach ($riders as $rider)
                                        <option value="{{ $rider->id }}">{{ $rider->name }}</option>
                                    @endforeach
                                </select>
                                @error('riderId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Pickup Schedule</label>
                                <input type="datetime-local" class="form-control @error('pickupScheduledAt') is-invalid @enderror" wire:model.defer="pickupScheduledAt">
                                @error('pickupScheduledAt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label>Delivery Schedule</label>
                                <input type="datetime-local" class="form-control @error('deliveryScheduledAt') is-invalid @enderror" wire:model.defer="deliveryScheduledAt">
                                @error('deliveryScheduledAt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label>Delivery Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" rows="4" wire:model.defer="notes"></textarea>
                            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

    <style>
        .pd-card {
            border: 1px solid #dce5f0;
            border-radius: 8px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, .06);
        }

        .pd-zone-count {
            border: 1px solid #dce5f0;
            border-radius: 8px;
            padding: .8rem;
            background: #f8fbff;
            min-height: 78px;
        }

        .pd-zone-count span {
            display: block;
            color: #607086;
            font-size: .8rem;
        }

        .pd-zone-count strong {
            display: block;
            font-size: 1.5rem;
        }

        .pd-table th {
            font-size: .74rem;
            letter-spacing: .03em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .pd-table td {
            vertical-align: middle;
        }

        .pd-notes {
            max-width: 220px;
            white-space: normal;
        }

        .pd-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: .25rem;
            min-width: 420px;
        }
    </style>

    <script>
        window.addEventListener('show-pickup-delivery-modal', () => $('#pickupDeliveryModal').modal('show'));
        window.addEventListener('hide-pickup-delivery-modal', () => $('#pickupDeliveryModal').modal('hide'));
    </script>
</div>
