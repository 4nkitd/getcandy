<div class="flex-col px-4 py-5 space-y-4 bg-white rounded-md rounded-b-none sm:p-6">
  <x-hub::input.group :label="__('adminhub::inputs.name')" for="name" :error="$errors->first('currency.name')">
    <x-hub::input.text wire:model.defer="shippingZone.name" name="name" id="name" :error="$errors->first('currency.name')" />
  </x-hub::input.group>

  <x-hub::input.group label="Type" for="type"  :error="$errors->first('currency.name')">
    <x-hub::input.select id="type" wire:model="shippingZone.type">
      <option value="unrestricted">Unrestricted</option>
      <option value="countries">Limit to Countries</option>
      <option value="states">Limit to States / Provinces</option>
      <option value="postcodes">Limit to list of Postcodes</option>
    </x-hub::input.select>
  </x-hub::input.group>

  @if($shippingZone->type == 'unrestricted')
    <x-hub::alert>
      This shipping zone has no restrictions in place and will be available to all customers at checkout.
    </x-hub::alert>
  @endif

  @if($shippingZone->type == 'countries')
    @include('shipping::partials.forms.shipping-zone.countries')
  @endif

  @if($shippingZone->type == 'postcodes')
    <x-hub::input.group
      label="Postcodes"
      for="type"
      instructions="List each postcode on a new line. Supports wildcards such as NW*"
      :error="$errors->first('postcodes')"
    >
      <x-hub::input.textarea wire:model="postcodes" rows="10" />
    </x-hub::input.group>
  @endif
</div>
<div class="px-4 py-3 justify-between bg-gray-50 sm:px-6 flex">
  <x-hub::button theme="danger" type="button"  wire:click="$set('showDeleteConfirm', true)">
    Delete Shipping Zone
  </x-hub::button>
  <x-hub::button type="submit">
    @if($shippingZone->id)
      Save shipping zone
    @else
      Create shipping zone
    @endif
  </x-hub::button>
</div>
