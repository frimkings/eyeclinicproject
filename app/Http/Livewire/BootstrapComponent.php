<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class BootstrapComponent extends Component
{
	use WithPagination;

	protected $paginationTheme = 'bootstrap';
}