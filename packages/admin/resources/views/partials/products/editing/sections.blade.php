<div>
    <h1 class="text-xl font-bold md:text-xl">
        @if ($product->id)
            {{ $product->translateAttribute('name') }}
        @else
            {{ __('adminhub::global.new_product') }}
        @endif
    </h1>
</div>

<form wire:submit.prevent="save"
      class="fixed bottom-0 right-0 left-auto z-40 p-6 border-t border-gray-100 bg-white/75"
      :class="{ 'w-[calc(100vw_-_12rem)]': showExpandedMenu, 'w-[calc(100vw_-_5rem)]': !showExpandedMenu }">
    <div class="flex justify-end w-full space-x-6">
        @include('adminhub::partials.products.status-bar')
        <x-hub::button type="submit">{{ __('adminhub::catalogue.products.show.save_btn') }}</x-hub::button>
    </div>
</form>

<div class="pb-24 mt-8 lg:gap-8 lg:flex">
    <div class="space-y-6 lg:flex-1">
        <div>
            @if (!$this->hasChannelAvailability)
                <x-hub::alert level="danger">
                    {{ __('adminhub::catalogue.products.show.no_channel_availability') }}
                </x-hub::alert>
            @endif
        </div>

        @foreach ($this->getSlotsByPosition('top') as $slot)
            <div id="{{ $slot->handle }}">
                <div>@livewire($slot->component, ['slotModel' => $product], key('top-slot-{{ $slot->handle }}'))</div>
            </div>
        @endforeach

        <div id="basic-information">
            @include('adminhub::partials.products.editing.basic-information')
        </div>

        <div id="attributes">
            @include('adminhub::partials.attributes')
        </div>

        <div id="images">
            @include('adminhub::partials.image-manager', [
                'existing' => $images,
                'wireModel' => 'imageUploadQueue',
                'filetypes' => ['image/*'],
            ])
        </div>

        <div id="availability">
            @include('adminhub::partials.availability', [
                'channels' => true,
                'customerGroups' => true,
            ])
        </div>

        @if (!$this->variantsDisabled)
            <div id="variants">
                @include('adminhub::partials.products.editing.variants')
            </div>
        @endif

        @if ($this->getVariantsCount() <= 1)
            <div id="pricing">
                @include('adminhub::partials.pricing')
            </div>

            <div id="identifiers">
                @include('adminhub::partials.products.variants.identifiers')
            </div>

            <div id="inventory">
                @include('adminhub::partials.products.variants.inventory')
            </div>

            <div id="shipping">
                @include('adminhub::partials.shipping')
            </div>
        @endif

        <div id="urls">
            @include('adminhub::partials.urls')
        </div>

        <div id="associations">
            @include('adminhub::partials.products.editing.associations')
        </div>

        <div id="collections">
            @include('adminhub::partials.products.editing.collections')
        </div>

        @foreach ($this->getSlotsByPosition('bottom') as $slot)
            <div id="{{ $slot->handle }}">
                <div>@livewire($slot->component, ['slotModel' => $product], key('bottom-slot-{{ $slot->handle }}'))</div>
            </div>
        @endforeach

        @if ($product->id)
            <div class="bg-white border border-red-300 rounded shadow">
                <header class="px-6 py-4 text-red-700 bg-white border-b border-red-300 rounded-t">
                    {{ __('adminhub::inputs.danger_zone.title') }}
                </header>

                <div class="p-6 text-sm">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 lg:col-span-8">
                            <strong>
                                {{ __('adminhub::inputs.danger_zone.label', ['model' => 'product']) }}
                            </strong>

                            <p class="text-xs text-gray-600">
                                {{ __('adminhub::catalogue.products.show.delete_strapline') }}
                            </p>
                        </div>

                        <div class="col-span-6 text-right lg:col-span-4">
                            <x-hub::button :disabled="false"
                                           wire:click="$set('showDeleteConfirm', true)"
                                           type="button"
                                           theme="danger">
                                {{ __('adminhub::global.delete') }}
                            </x-hub::button>
                        </div>
                    </div>
                </div>
            </div>

            <x-hub::modal.dialog wire:model="showDeleteConfirm">
                <x-slot name="title">
                    {{ __('adminhub::catalogue.products.show.delete_title') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('adminhub::catalogue.products.show.delete_strapline') }}
                </x-slot>

                <x-slot name="footer">
                    <div class="flex items-center justify-end space-x-4">
                        <x-hub::button theme="gray"
                                       type="button"
                                       wire:click.prevent="$set('showDeleteConfirm', false)">
                            {{ __('adminhub::global.cancel') }}
                        </x-hub::button>

                        <x-hub::button wire:click="delete"
                                       theme="danger">
                            {{ __('adminhub::catalogue.products.show.delete_btn') }}
                        </x-hub::button>
                    </div>
                </x-slot>
            </x-hub::modal.dialog>
        @endif

        <div class="pt-12 mt-12 border-t">
            @livewire('hub.components.activity-log-feed', [
                'subject' => $product,
            ])
        </div>
    </div>

    <aside class="sticky hidden h-full lg:block lg:flex-shrink-0 top-8">
        <div class="flex flex-col overflow-y-auto bg-white rounded-lg shadow sm:rounded-md w-72">
            <div class="px-4 py-6">
                <nav class="space-y-2"
                     aria-label="Sidebar"
                     x-data="{ activeAnchorLink: '' }"
                     x-init="activeAnchorLink = window.location.hash">
                    @foreach ($this->getSlotsByPosition('top') as $slot)
                        <a href="#{{ $slot->handle }}"
                           @class([
                               'flex items-center gap-2 p-2 rounded text-gray-500',
                               'hover:bg-blue-50 hover:text-blue-700' => empty(
                                   $this->getSlotErrorsByHandle($slot->handle)
                               ),
                               'text-red-600' => !empty($this->getSlotErrorsByHandle($slot->handle)),
                           ])
                           aria-current="page"
                           x-data="{ linkId: '#{{ $item['id'] }}' }"
                           :class="{
                               'bg-blue-50 text-blue-700 hover:text-blue-600': linkId === activeAnchorLink
                           }"
                           x-on:click="activeAnchorLink = linkId">
                            @if (!empty($this->getSlotErrorsByHandle($slot->handle)))
                                <x-hub::icon ref="exclamation-circle"
                                             class="w-4 mr-1 text-red-600" />
                            @endif

                            <span class="text-sm font-medium">
                                {{ $slot->title }}
                            </span>
                        </a>
                    @endforeach

                    @foreach ($this->sideMenu as $item)
                        <a href="#{{ $item['id'] }}"
                           @class([
                               'flex items-center gap-2 p-2 rounded text-gray-500',
                               'hover:bg-blue-50 hover:text-blue-700' => empty($item['has_errors']),
                               'text-red-600 bg-red-50' => !empty($item['has_errors']),
                           ])
                           aria-current="page"
                           x-data="{ linkId: '#{{ $item['id'] }}' }"
                           :class="{
                               'bg-blue-50 text-blue-700 hover:text-blue-600': linkId === activeAnchorLink
                           }"
                           x-on:click="activeAnchorLink = linkId">
                            @if (!empty($item['has_errors']))
                                <x-hub::icon ref="exclamation-circle"
                                             class="w-4 text-red-600" />
                            @endif

                            <span class="text-sm font-medium">
                                {{ $item['title'] }}
                            </span>
                        </a>
                    @endforeach

                    @foreach ($this->getSlotsByPosition('bottom') as $slot)
                        <a href="#{{ $slot->handle }}"
                           @class([
                               'flex items-center gap-2 p-2 rounded text-gray-500',
                               'hover:bg-blue-50 hover:text-blue-700' => empty(
                                   $this->getSlotErrorsByHandle($slot->handle)
                               ),
                               'text-red-600' => !empty($this->getSlotErrorsByHandle($slot->handle)),
                           ])
                           aria-current="page"
                           x-data="{ linkId: '#{{ $item['id'] }}' }"
                           :class="{
                               'bg-blue-50 text-blue-700 hover:text-blue-600': linkId === activeAnchorLink
                           }"
                           x-on:click="activeAnchorLink = linkId">
                            @if (!empty($this->getSlotErrorsByHandle($slot->handle)))
                                <x-hub::icon ref="exclamation-circle"
                                             class="w-4 mr-1 text-red-600" />
                            @endif

                            <span class="text-sm font-medium">
                                {{ $slot->title }}
                            </span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>
    </aside>
</div>
