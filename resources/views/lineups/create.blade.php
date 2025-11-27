<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Build Lineup - {{ $contest->name }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="lineupBuilder()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Error Messages -->
            <x-form-errors />

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Player List -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4">Available Players ({{ count($players) }})</h3>

                        <!-- Search and Filters -->
                        <div class="mb-4 space-y-2">
                            <!-- Search Bar -->
                            <input type="text"
                                   x-model="searchQuery"
                                   placeholder="Search players by name..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                            <!-- Filters Row -->
                            <div class="flex gap-2">
                                <select x-model="filterPosition" class="flex-1 rounded-md border-gray-300 text-sm">
                                    <option value="">All Positions</option>
                                    <option value="PG">PG</option>
                                    <option value="SG">SG</option>
                                    <option value="SF">SF</option>
                                    <option value="PF">PF</option>
                                    <option value="C">C</option>
                                </select>

                                <select x-model="filterTeam" class="flex-1 rounded-md border-gray-300 text-sm">
                                    <option value="">All Teams</option>
                                    @php
                                        $teams = $players->pluck('team')->unique()->sort()->values();
                                    @endphp
                                    @foreach($teams as $team)
                                        <option value="{{ $team }}">{{ $team }}</option>
                                    @endforeach
                                </select>

                                <select x-model="filterSalary" class="flex-1 rounded-md border-gray-300 text-sm">
                                    <option value="">All Salaries</option>
                                    <option value="10000+">$10k+</option>
                                    <option value="8000-10000">$8k-$10k</option>
                                    <option value="6000-8000">$6k-$8k</option>
                                    <option value="4000-6000">$4k-$6k</option>
                                    <option value="0-4000">Under $4k</option>
                                </select>
                            </div>

                            <!-- Sort Options -->
                            <div class="flex gap-2">
                                <select x-model="sortBy" class="flex-1 rounded-md border-gray-300 text-sm">
                                    <option value="salary-desc">Salary (High-Low)</option>
                                    <option value="salary-asc">Salary (Low-High)</option>
                                    <option value="ppg-desc">PPG (High-Low)</option>
                                    <option value="name-asc">Name (A-Z)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Player List -->
                        <div class="space-y-2 max-h-[600px] overflow-y-auto">
                            @foreach($players as $player)
                                <div x-show="filterPlayer({{ $player->id }}, '{{ $player->name }}', '{{ $player->position }}', '{{ $player->team }}', {{ $player->salary }})"
                                     class="border rounded-lg p-3 hover:bg-gray-50 cursor-pointer transition"
                                     @click="addPlayer({{ $player->id }}, '{{ $player->name }}', '{{ $player->position }}', {{ $player->salary }}, '{{ $player->team }}', {{ $player->ppg }}, {{ $player->rpg }}, {{ $player->apg }}, {{ $player->spg }}, {{ $player->bpg }})">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <p class="font-bold">{{ $player->name }}</p>
                                                <span class="text-xs bg-gray-100 px-2 py-0.5 rounded">{{ $player->position }}</span>
                                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">{{ $player->team }}</span>
                                            </div>
                                            <div class="mt-1 flex gap-3 text-xs text-gray-600">
                                                <span title="Points Per Game"><strong>{{ number_format($player->ppg, 1) }}</strong> PPG</span>
                                                <span title="Rebounds Per Game"><strong>{{ number_format($player->rpg, 1) }}</strong> RPG</span>
                                                <span title="Assists Per Game"><strong>{{ number_format($player->apg, 1) }}</strong> APG</span>
                                                @if($player->spg > 0 || $player->bpg > 0)
                                                    <span class="text-green-600" title="Steals/Blocks"><strong>{{ number_format($player->spg, 1) }}</strong> S / <strong>{{ number_format($player->bpg, 1) }}</strong> B</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right ml-3">
                                            <p class="font-bold text-lg">${{ number_format($player->salary) }}</p>
                                            <p class="text-xs text-gray-500">{{ number_format($player->mpg, 1) }} MPG</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Lineup Builder -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4">Your Lineup</h3>

                        <!-- Salary Cap -->
                        <div class="mb-6 p-4 bg-gray-100 rounded-lg">
                            <div class="flex justify-between mb-2">
                                <span class="font-bold">Remaining Salary:</span>
                                <span class="font-bold" :class="remaining < 0 ? 'text-red-600' : 'text-green-600'">
                                    $<span x-text="remaining.toLocaleString()"></span>
                                </span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all" :style="`width: ${(totalSalary / 50000) * 100}%`"></div>
                            </div>
                        </div>

                        <!-- Lineup Slots -->
                        <div class="space-y-2 mb-6">
                            <template x-for="(slot, index) in slots" :key="index">
                                <div class="border rounded-lg p-3" :class="slot.player ? 'bg-blue-50 border-blue-300' : 'bg-gray-50'">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-xs font-bold text-gray-500" x-text="slot.position"></p>
                                            <p class="font-bold" x-text="slot.player ? slot.player.name : 'Empty'"></p>
                                            <p class="text-sm text-gray-600" x-show="slot.player" x-text="slot.player ? `${slot.player.position} - ${slot.player.team}` : ''"></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold" x-show="slot.player" x-text="slot.player ? `$${slot.player.salary.toLocaleString()}` : ''"></p>
                                            <button x-show="slot.player" @click="removePlayer(index)" class="text-red-600 text-sm hover:text-red-800">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Submit Form -->
                        <form method="POST" action="{{ route('lineups.store') }}" @submit="prepareSubmit">
                            @csrf
                            <input type="hidden" name="contest_id" value="{{ $contest->id }}">
                            <input type="hidden" name="lineup_name" x-model="lineupName">
                            <template x-for="(slot, index) in slots" :key="index">
                                <div x-show="slot.player">
                                    <input type="hidden" :name="`players[${index}][player_id]`" :value="slot.player ? slot.player.id : ''">
                                    <input type="hidden" :name="`players[${index}][position_slot]`" :value="slot.position">
                                </div>
                            </template>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lineup Name (optional)</label>
                                <input type="text" x-model="lineupName" class="w-full rounded-md border-gray-300" placeholder="My Lineup">
                            </div>

                            <button type="submit"
                                    :disabled="!isValid()"
                                    :class="isValid() ? 'bg-green-500 hover:bg-green-700' : 'bg-gray-300 cursor-not-allowed'"
                                    class="w-full text-white px-4 py-3 rounded-lg font-bold">
                                Enter Contest
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function lineupBuilder() {
            return {
                filterPosition: '',
                filterTeam: '',
                filterSalary: '',
                sortBy: 'salary-desc',
                searchQuery: '',
                lineupName: '',
                slots: [
                    { position: 'PG', player: null },
                    { position: 'SG', player: null },
                    { position: 'SF', player: null },
                    { position: 'PF', player: null },
                    { position: 'C', player: null },
                    { position: 'G', player: null },
                    { position: 'F', player: null },
                    { position: 'UTIL', player: null }
                ],
                totalSalary: 0,
                remaining: 50000,

                filterPlayer(id, name, position, team, salary) {
                    // Check search query
                    if (this.searchQuery && !name.toLowerCase().includes(this.searchQuery.toLowerCase())) {
                        return false;
                    }

                    // Check position filter
                    if (this.filterPosition && position !== this.filterPosition) {
                        return false;
                    }

                    // Check team filter
                    if (this.filterTeam && team !== this.filterTeam) {
                        return false;
                    }

                    // Check salary filter
                    if (this.filterSalary) {
                        if (this.filterSalary === '10000+' && salary < 10000) {
                            return false;
                        } else if (this.filterSalary === '8000-10000' && (salary < 8000 || salary > 10000)) {
                            return false;
                        } else if (this.filterSalary === '6000-8000' && (salary < 6000 || salary > 8000)) {
                            return false;
                        } else if (this.filterSalary === '4000-6000' && (salary < 4000 || salary > 6000)) {
                            return false;
                        } else if (this.filterSalary === '0-4000' && salary >= 4000) {
                            return false;
                        }
                    }

                    return true;
                },

                addPlayer(id, name, position, salary, team, ppg) {
                    // Check if player already added
                    if (this.slots.some(slot => slot.player && slot.player.id === id)) {
                        this.showError(`${name} is already in your lineup!`);
                        return;
                    }

                    // Check if adding this player would exceed salary cap
                    if (this.totalSalary + salary > 50000) {
                        const over = (this.totalSalary + salary) - 50000;
                        this.showError(`Cannot add ${name}. This would exceed salary cap by $${over.toLocaleString()}.`);
                        return;
                    }

                    // Find empty matching slot
                    let slotIndex = -1;

                    // Try exact position match first
                    slotIndex = this.slots.findIndex(slot => !slot.player && slot.position === position);

                    // Try G slot for guards
                    if (slotIndex === -1 && (position === 'PG' || position === 'SG')) {
                        slotIndex = this.slots.findIndex(slot => !slot.player && slot.position === 'G');
                    }

                    // Try F slot for forwards
                    if (slotIndex === -1 && (position === 'SF' || position === 'PF')) {
                        slotIndex = this.slots.findIndex(slot => !slot.player && slot.position === 'F');
                    }

                    // Try UTIL slot
                    if (slotIndex === -1) {
                        slotIndex = this.slots.findIndex(slot => !slot.player && slot.position === 'UTIL');
                    }

                    if (slotIndex === -1) {
                        this.showError(`No available slot for ${name} (${position}). All compatible positions are filled.`);
                        return;
                    }

                    this.slots[slotIndex].player = { id, name, position, salary, team, ppg };
                    this.updateTotals();
                },

                showError(message) {
                    alert(message);
                },

                removePlayer(index) {
                    this.slots[index].player = null;
                    this.updateTotals();
                },

                updateTotals() {
                    this.totalSalary = this.slots.reduce((sum, slot) => {
                        return sum + (slot.player ? slot.player.salary : 0);
                    }, 0);
                    this.remaining = 50000 - this.totalSalary;
                },

                isValid() {
                    const allFilled = this.slots.every(slot => slot.player !== null);
                    const underCap = this.totalSalary <= 50000;
                    return allFilled && underCap;
                },

                prepareSubmit(e) {
                    if (!this.isValid()) {
                        e.preventDefault();

                        const emptySlots = this.slots.filter(slot => !slot.player).map(slot => slot.position);
                        if (emptySlots.length > 0) {
                            this.showError(`Please fill all lineup slots. Missing: ${emptySlots.join(', ')}`);
                            return;
                        }

                        if (this.totalSalary > 50000) {
                            const over = this.totalSalary - 50000;
                            this.showError(`Your lineup exceeds the salary cap by $${over.toLocaleString()}. Please remove or replace players.`);
                            return;
                        }

                        this.showError('Please complete your lineup before submitting.');
                    }
                }
            }
        }
    </script>
</x-app-layout>
