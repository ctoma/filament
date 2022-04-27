<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div {{ $attributes->merge($getExtraAttributes())->class([
        'space-y-6 bg-gray-50 p-6 rounded-xl filament-forms-builder-component',
        'dark:bg-gray-900' => config('forms.dark_mode'),
    ]) }}>
        @if (count($containers = $getChildComponentContainers()))
            <ul
                class="space-y-12"
                wire:sortable
                wire:end.stop="dispatchFormEvent('builder::moveItems', '{{ $getStatePath() }}', $event.target.sortable.toArray())"
            >
                @foreach ($containers as $uuid => $item)
                    @php($withBlockLabels = $shouldShowBlockLabels())

                    <li
                        x-data="{ isCreateButtonDropdownOpen: false, isCreateButtonVisible: false }"
                        x-on:click="isCreateButtonVisible = true"
                        x-on:mouseenter="isCreateButtonVisible = true"
                        x-on:click.away="isCreateButtonVisible = false"
                        x-on:mouseleave="isCreateButtonVisible = false"
                        wire:key="{{ $item->getStatePath() }}"
                        wire:sortable.item="{{ $uuid }}"
                        @class([
                            'bg-white border border-gray-300 shadow-sm rounded-xl relative',
                            'dark:bg-gray-800 dark:border-gray-600' => config('forms.dark_mode'),
                        ])
                    >
                        <header @class([
                            'flex items-center h-10 overflow-hidden border-b bg-gray-50 rounded-t-xl',
                            'dark:bg-gray-800 dark:border-gray-700' => config('forms.dark_mode'),
                        ])>
                            @unless ($isItemMovementDisabled())
                                <button
                                    wire:sortable.handle
                                    wire:keydown.prevent.arrow-up="dispatchFormEvent('builder::moveItemUp', '{{ $getStatePath() }}', '{{ $uuid }}')"
                                    wire:keydown.prevent.arrow-down="dispatchFormEvent('builder::moveItemDown', '{{ $getStatePath() }}', '{{ $uuid }}')"
                                    type="button"
                                    @class([
                                        'flex items-center justify-center flex-none w-10 h-10 text-gray-400 border-r transition hover:text-gray-300',
                                        'dark:text-gray-400 dark:border-gray-700 dark:hover:text-gray-500' => config('forms.dark_mode'),
                                    ])
                                >
                                    <span class="sr-only">
                                        {{ __('forms::components.repeater.buttons.move_item_down.label') }}
                                    </span>

                                    <x-heroicon-s-switch-vertical class="w-4 h-4" />
                                </button>
                            @endunless

                            @if($withBlockLabels)
                                <p @class([
                                    'flex-none px-4 text-xs font-medium text-gray-600 truncate',
                                    'dark:text-gray-400' => config('forms.dark_mode'),
                                ])>
                                    {{ $item->getParentComponent()->getLabel() }}

                                    <small class="font-mono">{{ $loop->iteration }}</small>
                                </p>
                            @endif

                            <div class="flex-1"></div>

                            @unless ($isItemDeletionDisabled())
                                <button
                                    wire:click="dispatchFormEvent('builder::deleteItem', '{{ $getStatePath() }}', '{{ $uuid }}')"
                                    type="button"
                                    @class([
                                        'flex items-center justify-center flex-none w-10 h-10 text-danger-600 transition hover:text-danger-500',
                                        'dark:text-danger-500 dark:hover:text-danger-400' => config('forms.dark_mode'),
                                    ])
                                >
                                    <span class="sr-only">
                                        {{ __('forms::components.repeater.buttons.delete_item.label') }}
                                    </span>

                                    <x-heroicon-s-trash class="w-4 h-4" />
                                </button>
                            @endunless
                        </header>

                        <div class="p-6">
                            {{ $item }}
                        </div>

                        @if ((! $loop->last) && (! $isItemCreationDisabled()) && (! $isItemMovementDisabled()) && (blank($getMaxItems()) || ($getMaxItems() > $getItemsCount())))
                            <div
                                x-show="isCreateButtonVisible || isCreateButtonDropdownOpen"
                                x-transition
                                class="absolute inset-x-0 bottom-0 z-10 flex items-center justify-center h-12 -mb-12"
                            >
                                <div class="relative flex justify-center">
                                    <button
                                        x-on:click="isCreateButtonDropdownOpen = true"
                                        type="button"
                                        @class([
                                            'flex items-center justify-center w-8 h-8 text-gray-500 bg-white border border-gray-300 rounded-full shadow-sm transition',
                                            'hover:bg-gray-50 focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset focus:ring-offset-white focus:ring-primary-600 focus:text-primary-600 focus:bg-primary-50',
                                            'dark:text-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:focus:ring-offset-gray-800 dark:focus:text-primary-400' => config('forms.dark_mode'),
                                        ])
                                    >
                                        <span class="sr-only">
                                            {{ $getCreateItemBetweenButtonLabel() }}
                                        </span>

                                        <x-heroicon-o-plus class="w-5 h-5" />
                                    </button>

                                    <x-forms::builder.block-picker
                                        :blocks="$getBlocks()"
                                        :create-after-item="$uuid"
                                        :state-path="$getStatePath()"
                                    />
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        @if ((! $isItemCreationDisabled()) && (blank($getMaxItems()) || ($getMaxItems() > $getItemsCount())))
            <div x-data="{ isCreateButtonDropdownOpen: false }" class="relative flex justify-center">
                <x-filament::button
                    x-on:click="isCreateButtonDropdownOpen = true"
                    type="button"
                >
                    {{ $getCreateItemButtonLabel() }}
                </x-filament::button>

                <x-forms::builder.block-picker
                    :blocks="$getBlocks()"
                    :state-path="$getStatePath()"
                />
            </div>
        @endif
    </div>
</x-dynamic-component>
