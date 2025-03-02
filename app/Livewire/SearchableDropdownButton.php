<?php

namespace App\Livewire;

use Livewire\Component;

class SearchableDropdownButton extends Component
{
public $selectedOption;
    public function handleButtonClick($value)
{
    // Handle the button click for a specific option
    $this->dispatchBrowserEvent('alert', ['message' => "Action triggered for value: $value"]);
}

public function selectOption($option)
{
    $this->selectedOption = $option['value'] ?? $option;
    $this->dispatchBrowserEvent('alert', ['message' => "You selected: {$option['label']}"]);
}
public $search = '';
    public $options = [];
    public $filteredOptions = [];
    public $placeholder;

    public function mount($options = [], $placeholder = 'Search...')
    {
        $this->options = $options;
        $this->placeholder = $placeholder;
        $this->filterOptions();
    }

    public function updatedSearch()
    {
        $this->filterOptions();
    }

    public function filterOptions()
    {
        $this->filteredOptions = array_filter($this->options, function ($option) {
            return stripos($option['label'], $this->search) !== false;
        });
    }

    public function render()
    {
        return view('livewire.searchable-dropdown-button');
    }
}
