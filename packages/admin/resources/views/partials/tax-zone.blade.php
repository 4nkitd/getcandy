<div class="flex-col space-y-4">
  <div class="overflow-hidden shadow sm:rounded-md">
    <div class="flex-col px-4 py-5 space-y-4 bg-white sm:p-6">
      <div class="space-y-4">
        <x-hub::input.group :label="__('adminhub::inputs.name')" for="name" :error="$errors->first('taxZone.name')">
          <x-hub::input.text wire:model="taxZone.name" name="name" id="name" :error="$errors->first('taxZone.name')" />
        </x-hub::input.group>


        <x-hub::input.group label="Type" for="type"  :error="$errors->first('taxZone.zone_type')">
          <x-hub::input.select id="type" wire:model="taxZone.zone_type">
            <option value="country">Limit to Countries</option>
            <option value="states">Limit to States / Provinces</option>
            <option value="postcodes">Limit to list of Postcodes</option>
          </x-hub::input.select>
        </x-hub::input.group>

        <div>
          @if($taxZone->zone_type == 'country')
            @include('adminhub::partials.forms.tax-zones.country')
          @endif
        </div>

        <div>
          @if($taxZone->zone_type == 'states')
            @include('adminhub::partials.forms.tax-zones.states')
          @endif
        </div>

        <div>
          @if($taxZone->zone_type == 'postcodes')
            @include('adminhub::partials.forms.tax-zones.postcode')
          @endif
        </div>
      </div>
    </div>


  </div>

  <div class="overflow-hidden shadow sm:rounded-md">
    <div class="flex-col px-4 py-5 space-y-4 bg-white sm:p-6">
      <div class="space-y-4">
        <h3>Tax Rates</h3>
      </div>

      <div class="space-y-4">
        @foreach($this->taxRates as $taxRateIndex => $rate)
          <div wire:key="tax_rate_{{ $taxRateIndex }}" class="rounded border p-3">
            <div class="flex w-full justify-end">
              <x-hub::button
                size="xs"
                theme="gray"
                type="button"
                wire:click="removeTaxRate({{ $taxRateIndex }})"
                :disabled="count($this->taxRates) == 1"
              >
                <x-hub::icon ref="trash" class="w-4"/>
              </x-hub::button>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <x-hub::input.group label="Name" for="name" required>
                <x-hub::input.text wire:model="taxRates.{{ $taxRateIndex }}.name" />
              </x-hub::input.group>

              <x-hub::input.group label="Priority" for="priority">
                <x-hub::input.text type="number" wire:model="taxRates.{{ $taxRateIndex }}.priority" />
              </x-hub::input.group>
            </div>

            <div class="mt-4">
              <div class="grid grid-cols-2 gap-4">
              @foreach($rate['amounts'] as $amountIndex => $amount)
                <x-hub::input.group
                  for="tr_{{ $taxRateIndex }}_amount_{{ $amountIndex }}"
                  wire:key="tr_{{ $taxRateIndex }}_amount_{{ $amountIndex }}"
                  :label="$amount['tax_class_name']"
                >
                  <x-hub::input.text
                    type="number"
                    wire:model="taxRates.{{ $taxRateIndex }}.amounts.{{ $amountIndex }}.percentage"
                  />
                </x-hub::input.group>
              @endforeach
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <x-hub::button theme="gray" type="button" wire:click="addTaxRate">Add tax rate</x-hub::button>
    </div>
  </div>

  <form wire:submit.prevent="save" class="py-3 justify-between bg-gray-50 flex">
    <div>
      @if($taxZone->id)
        <x-hub::button theme="danger" type="button"  wire:click="$set('showDeleteConfirm', true)">
          Delete tax zone
        </x-hub::button>
      @endif
    </div>
    <x-hub::button type="submit">
      @if($taxZone->id)
        Save tax zone
      @else
        Create tax zone
      @endif
    </x-hub::button>
  </form>
</div>