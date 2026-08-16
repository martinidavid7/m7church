<x-layouts.app :title="__('messages.View') . ' - ' . __('messages.Discipleships')">
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ __('messages.View') }} - {{ __('messages.Discipleships') }}</h1>
            <a href="{{ route('discipleships.index') }}"
                class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ __('messages.Cancel') }}
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900/30 p-4 text-sm text-green-700 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="max-w-3xl space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('messages.Discipleship') }}</h2>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        {{ $discipleship->status === 'active'
                            ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300'
                            : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $discipleship->status === 'active' ? __('messages.Active') : __('messages.Inactive') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Discipler') }}</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $discipleship->discipulador?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Start Date') }}</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $discipleship->started_at?->format('d/m/Y') }}</p>
                    </div>
                    @if ($discipleship->ended_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.End Discipleship') }}</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $discipleship->ended_at->format('d/m/Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <div class="flex items-center gap-2 mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $discipleship->discipulado?->name ?? '-' }}</h2>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                        {{ $discipleship->discipulado_type === 'visitor'
                            ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300'
                            : 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300' }}">
                        {{ $discipleship->discipulado_type === 'visitor' ? __('messages.Visitor') : __('messages.Person') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Phone') }}</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $discipleship->discipulado?->mobile_phone ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Email') }}</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $discipleship->discipulado?->mail ?? '-' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Address') }}</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">
                            @php
                                $target = $discipleship->discipulado;
                                $addressParts = array_filter([
                                    $target?->address,
                                    $target?->number,
                                    $target?->neighborhood,
                                ]);
                            @endphp
                            {{ $addressParts ? implode(', ', $addressParts) : '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.Notes History') }}</h2>

                <form action="{{ route('discipleships.notes.store', $discipleship) }}" method="POST" class="mb-6 space-y-3">
                    @csrf
                    <textarea name="body" rows="3" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        placeholder="{{ __('messages.Add Note') }}">{{ old('body') }}</textarea>
                    @error('body')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <button type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        {{ __('messages.Add Note') }}
                    </button>
                </form>

                <div class="space-y-4">
                    @forelse ($discipleship->notesHistory as $note)
                        @php
                            $canEditNote = $isMinistryAdmin || ($currentPersonId && $note->author_id === $currentPersonId);
                        @endphp
                        <div class="border-l-2 border-indigo-200 dark:border-indigo-800 pl-4" x-data="{ editing: false }">
                            <div x-show="!editing">
                                <p class="text-sm text-gray-900 dark:text-white whitespace-pre-line">{{ $note->body }}</p>
                                <div class="mt-1 flex items-center justify-between">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $note->author?->name ?? '-' }} · {{ $note->created_at->format('d/m/Y H:i') }}
                                        @if ($note->created_at->ne($note->updated_at))
                                            · {{ __('messages.Edit') }}
                                        @endif
                                    </p>
                                    @if ($canEditNote)
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="editing = true"
                                                class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                                {{ __('messages.Edit') }}
                                            </button>
                                            <form action="{{ route('discipleships.notes.destroy', [$discipleship, $note]) }}" method="POST"
                                                onsubmit="return confirm('{{ __('messages.Confirm Delete Note') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-xs font-medium text-red-600 dark:text-red-400 hover:underline">
                                                    {{ __('messages.Delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if ($canEditNote)
                                <form x-show="editing" x-cloak
                                    action="{{ route('discipleships.notes.update', [$discipleship, $note]) }}" method="POST"
                                    class="space-y-2">
                                    @csrf
                                    @method('PUT')
                                    <textarea name="body" rows="3" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">{{ $note->body }}</textarea>
                                    <div class="flex items-center gap-2">
                                        <button type="submit"
                                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-1.5 px-3 text-xs font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                            {{ __('messages.Save') }}
                                        </button>
                                        <button type="button" @click="editing = false"
                                            class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-1.5 px-3 text-xs font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                            {{ __('messages.Cancel') }}
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.No notes yet') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('discipleships.edit', $discipleship) }}"
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('messages.Edit') }}
                </a>
                @if ($discipleship->status === 'active')
                    <form action="{{ route('discipleships.end', $discipleship) }}" method="POST"
                        onsubmit="return confirm('{{ __('messages.End Discipleship') }}?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            {{ __('messages.End Discipleship') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
