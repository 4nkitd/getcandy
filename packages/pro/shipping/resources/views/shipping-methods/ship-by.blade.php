<form method="POST" wire:submit.prevent="save">
  <div class="space-y-4">
    @include('shipping::partials.forms.shipping-method-top')

    <x-hub::input.group label="Charge by" required for="chargeBy" :error="$errors->first('data.charge_by')">
      <x-hub::input.select id="chargeBy" wire:model="data.charge_by"  required>
        <option value>Select an option</option>
        <option value="cart_total">Cart total</option>
        <option value="weight">Weight</option>
      </x-hub::input.select>
    </x-hub::input.group>

    <header class="flex items-center justify-between">
      <div>
        <h3 class="text-lg font-medium leading-6 text-gray-900">
          {{ __('adminhub::partials.pricing.title') }}
        </h3>
      </div>
      <div class="flex items-center space-x-2">
        <div>
          <select wire:change="setCurrency($event.target.value)" class="block w-full py-1 pl-2 pr-8 text-base text-gray-600 bg-gray-100 border-none rounded-md form-select focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @foreach($this->currencies as $c)
              <option value="{{ $c->id }}" @if($currency->id == $c->id) selected @endif>{{ $c->code }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </header>

    <x-hub::input.group
      label="Default rate (optional)"
      instructions="This is the default rate when none of the tiers below are met."
      for="basePrice"
      :errors="$errors->get('basePrices.*.price')"
    >
      <x-hub::input.price
        wire:model="basePrices.{{ $this->currency->code }}.price"
        :symbol="$this->currency->format"
        :currencyCode="$this->currency->code"
      />
    </x-hub::input.group>

    <div class="flex items-center justify-between pt-4 border-t">
      <div>
        <strong>{{ __('adminhub::partials.pricing.tiers.title') }}</strong>
      </div>
      <x-hub::button :disabled="!$currency->default" wire:click.prevent="addTier" theme="gray" size="sm" type="button">
        {{ __('adminhub::partials.pricing.tiers.add_tier_btn') }}
      </x-hub::button>
    </div>

    <div class="space-y-4">
      @if(count($tieredPrices))
        <div>
        @if(!$this->currency->default)
          <x-hub::alert>
            {{ __('adminhub::partials.pricing.non_default_currency_alert') }}
          </x-hub::alert>
        @endif
        </div>
        <div class="space-y-2">
          <div class="grid grid-cols-3 gap-4">
            <label class="block text-sm font-medium text-gray-700">{{ __('adminhub::global.customer_group') }}</label>
            <label class="block text-sm font-medium text-gray-700">{{ __('adminhub::global.lower_limit') }}</label>
            <label class="block text-sm font-medium text-gray-700">
              {{ __('adminhub::global.unit_price_excl_tax') }}
            </label>
          </div>

          <div class="space-y-2">
            @foreach($tieredPrices as $index => $tier)
              <div wire:key="tier_{{ $index }}">
                <div class="flex items-center">
                  <div class="grid grid-cols-3 gap-4">
                      <x-hub::input.select wire:model='tieredPrices.{{ $index }}.customer_group_id' :disabled="!$this->currency->default">
                        <option value="*">{{ __('adminhub::global.any') }}</option>
                        @foreach($this->customerGroups as $group)
                          <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                      </x-hub::input.select>

                      <x-hub::input.text
                        id="tier_field_{{ $index }}"
                        wire:model='tieredPrices.{{ $index }}.tier'
                        type="number"
                        min="2"
                        steps="1"
                        required
                        onkeydown="return event.keyCode !== 190"
                        :disabled="!$this->currency->default"
                        :error="$errors->first('tieredPrices.'.$index.'.tier')"
                      />

                    <x-hub::input.price wire:model="tieredPrices.{{ $index }}.prices.{{ $currency->code }}.price" :symbol="$this->currency->format" :currencyCode="$this->currency->code" />
                  </div>
                  <div class="ml-4">
                    <button class="text-gray-500 hover:text-red-500" wire:click.prevent="removeTier('{{ $index }}')"><x-hub::icon ref="trash" class="w-4" /></button>
                  </div>
                </div>
                @foreach($errors->get('tieredPrices.'.$index.'*') as $error)
                  @foreach($error as $text)
                    <p class="mt-2 text-sm text-red-600">{{ $text }}</p>
                  @endforeach
                @endforeach
              </div>
            @endforeach
          </div>
        </div>
      @else
      @endif
    </div>


    <x-hub::button>Save Method</x-hub::button>
  </div>
</form>
