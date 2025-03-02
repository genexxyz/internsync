<div class="">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">

        <div>
            <select name="province" wire:model.live="selectedProvince" id="province" required
                class="form-control block mt-1 w-full text-gray-600 border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                <option value="">Select Province</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province['province_code'] }}"
                        {{ $province['province_code'] == old('province', $selectedProvince) ? 'selected' : '' }}>
                        {{ $province['province_name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        
            <div>
                <select name="city" wire:model.live="selectedCity" id="city" required
                    class="form-control block mt-1 w-full text-gray-600 border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                    <option value="">Select City</option>
                    @if ($selectedProvince)
                    @foreach ($cities as $city)
                        <option value="{{ $city['city_code'] }}"
                            {{ $city['city_code'] == old('city', $selectedCity) ? 'selected' : '' }}>
                            {{ $city['city_name'] }}
                        </option>
                    @endforeach
                    @endif
                </select>
            </div>
        

        
            <div>
                <select name="barangay" wire:model.live="selectedBarangay" id="barangay" required
                    class="form-control block mt-1 w-full text-gray-600 border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                    <option value="">Select Barangay</option>
                    @if ($selectedCity)
                    @foreach ($barangays as $barangay)
                        <option value="{{ $barangay['brgy_code'] }}"
                            {{ $barangay['brgy_code'] == old('barangay', $selectedBarangay) ? 'selected' : '' }}>
                            {{ $barangay['brgy_name'] }}
                        </option>
                    @endforeach
                    @endif
                </select>
            </div>
        
    </div>

    
        <div class="mt-3">
            <x-text-input required wire:model.live="street" icon="fa fa-location-dot" id="street" class="block mt-1 w-full"
                type="text" name="street" :value="old('street')" autofocus autocomplete="street" placeholder="House/Apartment No., Street Name" />
            <x-input-error :messages="$errors->get('street')" class="mt-2" />
        </div>
    

    {{-- @if ($selectedProvince && $selectedCity && $selectedBarangay && $street)
        <div>
            <h3>Selected Address Details:</h3>
            <p>
                Province:
                {{ collect($provinces)->firstWhere('province_code', $selectedProvince)['province_name'] ?? '' }}
            </p>
            <p>
                City:
                {{ collect($cities)->firstWhere('city_code', $selectedCity)['city_name'] ?? '' }}
            </p>
            <p>
                Barangay:
                {{ collect($barangays)->firstWhere('brgy_code', $selectedBarangay)['brgy_name'] ?? '' }}
            </p>

            Street: {{ $street ?? '' }}</p>
        </div>
    @endif --}}
</div>
