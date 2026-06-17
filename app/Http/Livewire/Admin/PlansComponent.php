<?php

namespace App\Http\Livewire\Admin;

use App\Models\Plan;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class PlansComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $searchTerm = '';
    public ?Plan $editingPlan = null;
    public ?int $planIdBeingRemoved = null;
    public bool $showEditModal = false;

    public array $state = [
        'name' => '',
        'price' => '',
        'billing_period' => 'semester',
        'description' => '',
        'sort_order' => 0,
        'is_active' => true,
    ];

    public function updatedSearchTerm(): void
    {
        $this->resetPage();
    }

    public function openPlanModal(): void
    {
        $this->resetPlanForm();
        $this->dispatchBrowserEvent('show-plan-modal');
    }

    public function createPlan(): void
    {
        Plan::create($this->validatedPlan());

        $this->resetPlanForm();
        $this->dispatchBrowserEvent('hide-plan-modal');
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Plan added successfully.']);
    }

    public function editPlanModal(Plan $plan): void
    {
        $this->resetValidation();
        $this->editingPlan = $plan;
        $this->showEditModal = true;
        $this->state = [
            'name' => $plan->name,
            'price' => (string) $plan->price,
            'billing_period' => $plan->billing_period,
            'description' => $plan->description ?? '',
            'sort_order' => $plan->sort_order,
            'is_active' => (bool) $plan->is_active,
        ];

        $this->dispatchBrowserEvent('show-plan-modal');
    }

    public function updatePlan(): void
    {
        if (!$this->editingPlan) {
            return;
        }

        $this->editingPlan->update($this->validatedPlan($this->editingPlan->id));

        $this->resetPlanForm();
        $this->dispatchBrowserEvent('hide-plan-modal');
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Plan updated successfully.']);
    }

    public function togglePlanStatus(int $planId): void
    {
        $plan = Plan::findOrFail($planId);
        $plan->update(['is_active' => !$plan->is_active]);

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => $plan->is_active ? 'Plan activated.' : 'Plan deactivated.',
        ]);
    }

    public function confirmPlanDeletion(int $planId): void
    {
        $this->planIdBeingRemoved = $planId;
        $this->dispatchBrowserEvent('show-plan-delete-confirmation');
    }

    public function confirmPlanDelete(): void
    {
        if (!$this->planIdBeingRemoved) {
            return;
        }

        Plan::findOrFail($this->planIdBeingRemoved)->delete();
        $this->planIdBeingRemoved = null;

        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Plan deleted successfully.']);
    }

    private function validatedPlan(?int $ignoreId = null): array
    {
        $data = $this->validate([
            'state.name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('plans', 'name')->ignore($ignoreId),
            ],
            'state.price' => 'required|numeric|min:0|max:999999.99',
            'state.billing_period' => 'required|string|max:50',
            'state.description' => 'nullable|string|max:500',
            'state.sort_order' => 'nullable|integer|min:0|max:9999',
            'state.is_active' => 'boolean',
        ], [], [
            'state.name' => 'plan name',
            'state.price' => 'price',
            'state.billing_period' => 'billing period',
            'state.description' => 'description',
            'state.sort_order' => 'sort order',
            'state.is_active' => 'active status',
        ])['state'];

        $data['price'] = round((float) $data['price'], 2);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        return $data;
    }

    private function resetPlanForm(): void
    {
        $this->resetValidation();
        $this->editingPlan = null;
        $this->showEditModal = false;
        $this->state = [
            'name' => '',
            'price' => '',
            'billing_period' => 'semester',
            'description' => '',
            'sort_order' => 0,
            'is_active' => true,
        ];
    }

    public function render()
    {
        $plans = Plan::query()
            ->when($this->searchTerm, function ($query) {
                $query->where(function ($searchQuery) {
                    $searchQuery->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('billing_period', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->ordered()
            ->paginate(10);

        return view('livewire.admin.plans-component', compact('plans'))
            ->layout('layouts.admin.admin-layout');
    }
}
