<?php

namespace App\Livewire;

use Livewire\Component;

class SearchableDropdownMultiple extends Component
{
    public $options = [];
    public $search = '';
    public $selectedOption = null;
    public $selectedOptions = [];
    public $multiple = false;
    public $placeholder = 'Select an option';
    public $filteredOptions = [];
    public $value;

    protected $listeners = ['valueMultipleUpdated'];

    public function mount($options = [], $multiple = false, $placeholder = null, $value = null)
    {
        $this->options = $options;
        $this->multiple = $multiple;
        $this->placeholder = $placeholder ?? $this->placeholder;
        $this->value = $value;
        
        if ($this->multiple) {
            $this->selectedOptions = is_array($value) ? $value : ($value ? [$value] : []);
        } else {
            $this->selectedOption = $value;
        }
        
        $this->filteredOptions = $options;
    }

    public function selectOption($option)
    {
        $value = is_array($option) ? ($option['value'] ?? $option) : $option;
        
        if ($this->multiple) {
            if (!in_array($value, $this->selectedOptions)) {
                $this->selectedOptions[] = $value;
            }
            $this->value = $this->selectedOptions;
        } else {
            $this->selectedOption = $value;
            $this->value = $value;
        }
        
        $this->search = '';
        $this->dispatch('valueMultipleUpdated', $this->value);
    }

    public function removeOption($value)
    {
        if ($this->multiple) {
            $this->selectedOptions = array_values(array_filter(
                $this->selectedOptions, 
                fn($option) => $option != $value
            ));
            $this->value = $this->selectedOptions;
        } else {
            $this->selectedOption = null;
            $this->value = null;
        }
        
        $this->dispatch('valueMultipleUpdated', $this->value);
    }

    public function valueMultipleUpdated($value)
    {
        if ($this->multiple) {
            $this->selectedOptions = is_array($value) ? $value : [$value];
        } else {
            $this->selectedOption = $value;
        }
        $this->value = $value;
    }

    public function updatedSearch()
    {
        if (empty($this->search)) {
            $this->filteredOptions = $this->options;
            return;
        }

        $this->filteredOptions = collect($this->options)->filter(function ($option) {
            $label = is_array($option) ? ($option['label'] ?? $option['value'] ?? '') : $option;
            return stripos($label, $this->search) !== false;
        })->values()->toArray();
    }

    public function render()
    {
        return view('livewire.searchable-dropdown-multiple');
    }
}