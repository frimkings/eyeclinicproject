<?php

namespace App\Http\Livewire\PickupDelivery;

use App\Models\AuditTrail;
use App\Models\Order;
use App\Models\OrderActivityLog;
use App\Models\PickupDeliveryTask;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PickupDeliveryComponent extends Component
{
    public string $zoneFilter = '';
    public string $statusFilter = '';

    public string $orderId = '';
    public string $zoneCode = 'A';
    public string $pickupScheduledAt = '';
    public string $deliveryScheduledAt = '';
    public string $riderId = '';
    public string $notes = '';

    public ?int $editingTaskId = null;
    public string $taskStatus = 'Pickup Requested';

    public function zones(): array
    {
        return [
            'A' => 'Zone A - Nearest Hostels',
            'B' => 'Zone B - Mid-Distance Hostels',
            'C' => 'Zone C - Far Hostels',
        ];
    }

    public function assignTask(): void
    {
        $this->validate($this->taskRules());

        $order = Order::with('customer')->findOrFail((int) $this->orderId);
        $task = PickupDeliveryTask::updateOrCreate(
            ['order_id' => $order->id],
            [
                'zone_code' => $this->zoneCode,
                'zone_name' => $this->zones()[$this->zoneCode],
                'pickup_scheduled_at' => $this->pickupScheduledAt ?: null,
                'delivery_scheduled_at' => $this->deliveryScheduledAt ?: null,
                'rider_id' => $this->riderId ?: null,
                'assigned_by' => Auth::id(),
                'status' => 'Pickup Requested',
                'notes' => $this->notes ?: null,
            ]
        );

        $order->update([
            'zone' => $this->zones()[$this->zoneCode],
            'service_type' => $order->service_type === 'Walk-in' ? 'Pickup' : $order->service_type,
        ]);

        $this->logOrder($order, 'pickup_delivery_assigned', "Pickup/delivery task assigned to {$task->zone_name}.", [
            'task_id' => $task->id,
            'zone' => $task->zone_name,
            'rider_id' => $task->rider_id,
        ]);
        AuditTrail::record('pickup_delivery.assigned', "Assigned {$order->order_number} to {$task->zone_name}", $task, [], $task->toArray(), null, true);

        $this->resetTaskForm();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Pickup/delivery task assigned.']);
    }

    public function openTaskModal(int $taskId): void
    {
        $task = PickupDeliveryTask::findOrFail($taskId);
        $this->editingTaskId = $task->id;
        $this->orderId = (string) $task->order_id;
        $this->zoneCode = $task->zone_code;
        $this->pickupScheduledAt = optional($task->pickup_scheduled_at)->format('Y-m-d\TH:i') ?: '';
        $this->deliveryScheduledAt = optional($task->delivery_scheduled_at)->format('Y-m-d\TH:i') ?: '';
        $this->riderId = $task->rider_id ? (string) $task->rider_id : '';
        $this->notes = $task->notes ?: '';
        $this->taskStatus = $task->status;
        $this->resetValidation();
        $this->dispatchBrowserEvent('show-pickup-delivery-modal');
    }

    public function updateTask(): void
    {
        $task = PickupDeliveryTask::with('order')->findOrFail((int) $this->editingTaskId);

        $this->validate([
            'zoneCode' => ['required', Rule::in(array_keys($this->zones()))],
            'pickupScheduledAt' => ['nullable', 'date'],
            'deliveryScheduledAt' => ['nullable', 'date'],
            'riderId' => ['nullable', 'exists:users,id'],
            'taskStatus' => ['required', Rule::in(PickupDeliveryTask::STATUSES)],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $old = $task->toArray();
        $task->update([
            'zone_code' => $this->zoneCode,
            'zone_name' => $this->zones()[$this->zoneCode],
            'pickup_scheduled_at' => $this->pickupScheduledAt ?: null,
            'delivery_scheduled_at' => $this->deliveryScheduledAt ?: null,
            'rider_id' => $this->riderId ?: null,
            'status' => $this->taskStatus,
            'notes' => $this->notes ?: null,
        ]);

        $this->syncOrderFromTask($task->fresh('order'));
        $this->logOrder($task->order, 'pickup_delivery_updated', "Pickup/delivery task updated to {$task->status}.", [
            'task_id' => $task->id,
            'status' => $task->status,
        ]);
        AuditTrail::record('pickup_delivery.updated', "Updated pickup/delivery for {$task->order->order_number}", $task, $old, $task->fresh()->toArray(), null, true);

        $this->dispatchBrowserEvent('hide-pickup-delivery-modal');
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Pickup/delivery task updated.']);
    }

    public function markPickupCompleted(int $taskId): void
    {
        $task = PickupDeliveryTask::with('order')->findOrFail($taskId);
        $task->update([
            'status' => 'Picked Up',
            'pickup_completed_at' => now(),
        ]);
        $task->order->update(['status' => 'Received']);

        $this->logOrder($task->order, 'pickup_completed', "Pickup completed for {$task->order->order_number}.", ['task_id' => $task->id]);
        AuditTrail::record('pickup_delivery.pickup_completed', "Pickup completed for {$task->order->order_number}", $task, [], $task->fresh()->toArray(), null, true);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Pickup marked completed.']);
    }

    public function markAtLaundry(int $taskId): void
    {
        $task = PickupDeliveryTask::with('order')->findOrFail($taskId);
        $task->update(['status' => 'At Laundry']);
        $task->order->update(['status' => 'Received']);

        $this->logOrder($task->order, 'arrived_at_laundry', "Order arrived at laundry.", ['task_id' => $task->id]);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Order marked at laundry.']);
    }

    public function markReadyForDelivery(int $taskId): void
    {
        $task = PickupDeliveryTask::with('order')->findOrFail($taskId);
        $task->update(['status' => 'Ready for Delivery']);
        $task->order->update(['status' => 'Ready']);

        $this->logOrder($task->order, 'ready_for_delivery', "Order ready for delivery.", ['task_id' => $task->id]);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Order marked ready for delivery.']);
    }

    public function markOutForDelivery(int $taskId): void
    {
        $task = PickupDeliveryTask::with('order')->findOrFail($taskId);
        $task->update(['status' => 'Out for Delivery']);
        $task->order->update(['status' => 'Out for Delivery']);

        $this->logOrder($task->order, 'out_for_delivery', "Order out for delivery.", ['task_id' => $task->id]);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Order marked out for delivery.']);
    }

    public function markDeliveryCompleted(int $taskId): void
    {
        $task = PickupDeliveryTask::with('order')->findOrFail($taskId);
        $task->update([
            'status' => 'Delivered',
            'delivery_completed_at' => now(),
        ]);
        $task->order->update(['status' => 'Delivered']);

        $this->logOrder($task->order, 'delivery_completed', "Delivery completed for {$task->order->order_number}.", ['task_id' => $task->id]);
        AuditTrail::record('pickup_delivery.delivery_completed', "Delivery completed for {$task->order->order_number}", $task, [], $task->fresh()->toArray(), null, true);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Delivery marked completed.']);
    }

    public function statusBadgeClass(string $status): string
    {
        return match ($status) {
            'Pickup Requested' => 'badge-secondary',
            'Picked Up' => 'badge-primary',
            'At Laundry' => 'badge-info',
            'Ready for Delivery' => 'badge-success',
            'Out for Delivery' => 'badge-warning',
            'Delivered' => 'badge-dark',
            default => 'badge-light',
        };
    }

    public function clearFilters(): void
    {
        $this->reset(['zoneFilter', 'statusFilter']);
    }

    private function taskRules(): array
    {
        return [
            'orderId' => ['required', 'exists:orders,id'],
            'zoneCode' => ['required', Rule::in(array_keys($this->zones()))],
            'pickupScheduledAt' => ['nullable', 'date'],
            'deliveryScheduledAt' => ['nullable', 'date'],
            'riderId' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    private function resetTaskForm(): void
    {
        $this->orderId = '';
        $this->zoneCode = 'A';
        $this->pickupScheduledAt = '';
        $this->deliveryScheduledAt = '';
        $this->riderId = '';
        $this->notes = '';
        $this->editingTaskId = null;
        $this->taskStatus = 'Pickup Requested';
        $this->resetValidation();
    }

    private function syncOrderFromTask(PickupDeliveryTask $task): void
    {
        $status = match ($task->status) {
            'Pickup Requested' => 'Pending',
            'Picked Up', 'At Laundry' => 'Received',
            'Ready for Delivery' => 'Ready',
            'Out for Delivery' => 'Out for Delivery',
            'Delivered' => 'Delivered',
            default => $task->order->status,
        };

        $task->order->update([
            'zone' => $task->zone_name,
            'status' => $status,
        ]);
    }

    private function logOrder(Order $order, string $event, string $description, array $properties = []): void
    {
        OrderActivityLog::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'event' => $event,
            'description' => $description,
            'properties' => $properties ?: null,
        ]);
    }

    private function layoutForUser(): string
    {
        $user = Auth::user();

        return match (true) {
            $user->hasRole(['Super Admin', 'Manager']) => 'layouts.admin.admin-layout',
            default => 'layouts.secretary.secretary-layout',
        };
    }

    public function render()
    {
        $tasks = PickupDeliveryTask::with(['order.customer', 'order.plan', 'rider'])
            ->when($this->zoneFilter, fn ($query) => $query->where('zone_code', $this->zoneFilter))
            ->when($this->statusFilter, fn ($query) => $query->where('status', $this->statusFilter))
            ->orderByRaw("FIELD(status, 'Pickup Requested', 'Picked Up', 'At Laundry', 'Ready for Delivery', 'Out for Delivery', 'Delivered')")
            ->orderBy('pickup_scheduled_at')
            ->get()
            ->groupBy('zone_code');

        $orders = Order::with(['customer', 'plan', 'pickupDeliveryTask'])
            ->whereNotIn('status', ['Delivered', 'Cancelled'])
            ->latest()
            ->limit(100)
            ->get();

        $riders = User::query()
            ->orderBy('name')
            ->get(['id', 'name', 'phone', 'staff_id']);

        return view('livewire.pickup-delivery.pickup-delivery-component', [
            'tasksByZone' => $tasks,
            'orders' => $orders,
            'riders' => $riders,
            'zones' => $this->zones(),
            'statuses' => PickupDeliveryTask::STATUSES,
        ])->layout($this->layoutForUser());
    }
}
