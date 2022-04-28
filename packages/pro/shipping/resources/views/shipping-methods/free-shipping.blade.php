<form method="POST" wire:submit.prevent="save">
  <div class="space-y-4">
    <x-hub::input.group label="Display Name" for="name" :error="$errors->first('shippingMethod.name')">
      <x-hub::input.text wire:model="shippingMethod.name" name="name" id="name" :error="$errors->first('shippingMethod.name')" />
    </x-hub::input.group>

    <x-hub::input.group label="Description" for="description" :error="$errors->first('shippingMethod.description')">
      <x-hub::input.textarea wire:model="shippingMethod.description" name="description" id="description" :error="$errors->first('shippingMethod.description')" />
    </x-hub::input.group>

    <x-hub::input.group label="Code" for="code" :error="$errors->first('shippingMethod.code')">
      <div class="flex">
        <x-hub::input.text wire:model="shippingMethod.code" name="code" id="code" :error="$errors->first('shippingMethod.code')" />

        <select wire:change="setCurrency($event.target.value)" class="block w-full py-1 pl-2 pr-8 text-base text-gray-600 bg-gray-100 border-none rounded-md form-select focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
          @foreach($this->currencies as $c)
            <option value="{{ $c->id }}" @if($currency->id == $c->id) selected @endif>{{ $c->code }}</option>
          @endforeach
        </select>
      </div>
    </x-hub::input.group>

    <x-hub::input.group label="Minimum Spend" for="minimum_spend" :error="$errors->first('data.minimum_spend')">
      <x-hub::input.text placeholder="0.00" wire:model="data.minimum_spend" name="minimum_spend" id="minimum_spend" :error="$errors->first('data.minimum_spend')" />
    </x-hub::input.group>

    <x-hub::input.group label="Use discounted amount" for="use_discount_amount" :error="$errors->first('data.use_discount_amount')">
      <x-hub::input.toggle wire:model="data.use_discount_amount" id="use_discount_amount" />
    </x-hub::input.group>

    @include('shipping::partials.forms.product-exclusions')

    <x-hub::button>Save Method</x-hub::button>
  </div>
</div>