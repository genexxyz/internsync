<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;

class AddressSelector extends Component
{
    public $fullAddress = '';

    // After selecting barangay and entering street
    public function updatedSelectedBarangay()
    {
        $this->updateFullAddress();
    }

    public function updatedStreet()
    {
        $this->updateFullAddress();
    }

    protected function updateFullAddress()
    {
        if (!$this->selectedProvince || !$this->selectedCity || !$this->selectedBarangay || !$this->street) {
            $this->fullAddress = '';
            $this->dispatch('address-updated', address: '');
            return;
        }

        $province = collect($this->provinces)->firstWhere('province_code', $this->selectedProvince)['province_name'] ?? '';
        $city = collect($this->cities)->firstWhere('city_code', $this->selectedCity)['city_name'] ?? '';
        $barangay = collect($this->barangays)->firstWhere('brgy_code', $this->selectedBarangay)['brgy_name'] ?? '';

        $this->fullAddress = "{$this->street}, {$barangay}, {$city}, {$province}";

        // Dispatch an event to the parent component
        $this->dispatch('address-updated', address: $this->fullAddress);
    }
    public $provinces = [];
    public $cities = [];
    public $barangays = [];

    public $street;

    public $selectedProvince = null;
    public $selectedCity = null;
    public $selectedBarangay = null;

    public function mount()
    {
        // Load provinces on component initialization and sort alphabetically
        $this->provinces = $this->loadProvinces()
            ->sortBy('province_name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();
    }

    public function loadProvinces()
    {
        $provincesPath = storage_path('app/philippine-addresses/provinces.json');

        if (!File::exists($provincesPath)) {
            return collect([]);
        }

        return collect(json_decode(File::get($provincesPath), true));
    }

    public function updatedSelectedProvince($provinceCode)
    {
        // Reset dependent fields
        $this->selectedCity = null;
        $this->selectedBarangay = null;
        $this->cities = [];
        $this->barangays = [];
        $this->street = '';

        if ($provinceCode) {
            $this->cities = $this->loadCitiesByProvince($provinceCode)
                ->sortBy('city_name', SORT_NATURAL | SORT_FLAG_CASE)
                ->values()
                ->all();
        }
    }

    public function loadCitiesByProvince($provinceCode)
    {
        $citiesPath = storage_path('app/philippine-addresses/cities.json');

        if (!File::exists($citiesPath)) {
            return collect([]);
        }

        $allCities = collect(json_decode(File::get($citiesPath), true));

        return $allCities->filter(function ($city) use ($provinceCode) {
            return $city['province_code'] === $provinceCode;
        });
    }

    public function updatedSelectedCity($cityCode)
    {
        // Reset barangay field
        $this->selectedBarangay = null;
        $this->barangays = [];

        if ($cityCode) {
            $this->barangays = $this->loadBarangaysByCity($cityCode)
                ->sortBy('brgy_name', SORT_NATURAL | SORT_FLAG_CASE)
                ->values()
                ->all();
        }
    }

    public function loadBarangaysByCity($cityCode)
    {
        $barangaysPath = storage_path('app/philippine-addresses/barangays.json');

        if (!File::exists($barangaysPath)) {
            return collect([]);
        }

        $allBarangays = collect(json_decode(File::get($barangaysPath), true));

        return $allBarangays->filter(function ($barangay) use ($cityCode) {
            return $barangay['city_code'] === $cityCode;
        });
    }



    public function render()
    {
        return view('livewire.address-selector', [
            'provinces' => $this->provinces,
            'cities' => $this->cities,
            'barangays' => $this->barangays,
            'street' => $this->street,
        ]);
    }
}
