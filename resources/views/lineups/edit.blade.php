<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-white">
                Edit Lineup - {{ $lineup->contest->name }}
            </h2>
            <a href="{{ route('lineups.show', $lineup->id) }}" class="text-green-400 hover:text-green-300 text-sm font-bold">
                ← Back to Lineup
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="lineupBuilder()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-form-errors />

            <div class="mb-6 bg-gray-800 border border-gray-700 rounded-xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <span class="text-sm text-gray-400">Salary Remaining</span>
                        <p class="text-3xl font-black" :class="totalSalary > 50000 ? 'text-red-400' : 'accent-green'">
                            $<span x-text="(50000 - totalSalary).toLocaleString()"></span>
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm text-gray-400">Players Selected</span>
                        <p class="text-3xl font-black text-white">
                            <span x-text="slots.filter(s => s.player).length"></span><span class="text-gray-500">/8</span>
                        </p>
                    </div>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-3 overflow-hidden">
                    <div class="h-full transition-all duration-300"
                         :class="totalSalary > 50000 ? 'bg-red-500' : 'bg-accent-green'"
                         :style="`width: ${Math.min((totalSalary / 50000) * 100, 100)}%`">
                    </div>
                </div>
                <div class="flex justify-between mt-2 text-xs text-gray-500">
                    <span>$0</span>
                    <span>$50,000</span>
                </div>
            </div>

            <div class="grid lg:grid-cols-5 gap-6">
                <div class="lg:col-span-3">
                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                        <div class="p-6 border-b border-gray-700">
                            <h3 class="text-xl font-black text-white mb-4">Player Pool</h3>

                            <input type="text"
                                   x-model="searchQuery"
                                   placeholder="🔍 Search players..."
                                   class="w-full mb-4 bg-gray-900 border-gray-600 text-white placeholder-gray-500 rounded-lg focus:border-green-500 focus:ring-green-500">

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                <select x-model="filterPosition" class="bg-gray-900 border-gray-600 text-white text-sm rounded-lg focus:border-green-500">
                                    <option value="">All Positions</option>
                                    <option value="PG">PG</option>
                                    <option value="SG">SG</option>
                                    <option value="SF">SF</option>
                                    <option value="PF">PF</option>
                                    <option value="C">C</option>
                                </select>

                                <select x-model="filterTeam" class="bg-gray-900 border-gray-600 text-white text-sm rounded-lg focus:border-green-500">
                                    <option value="">All Teams</option>
                                    @php
                                        $teams = $players->pluck('team')->unique()->sort()->values();
                                    @endphp
                                    @foreach($teams as $team)
                                        <option value="{{ $team }}">{{ $team }}</option>
                                    @endforeach
                                </select>

                                <select x-model="filterSalary" class="bg-gray-900 border-gray-600 text-white text-sm rounded-lg focus:border-green-500">
                                    <option value="">All Salaries</option>
                                    <option value="10000+">$10k+</option>
                                    <option value="8000-10000">$8k-$10k</option>
                                    <option value="6000-8000">$6k-$8k</option>
                                    <option value="4000-6000">$4k-$6k</option>
                                    <option value="0-4000">Under $4k</option>
                                </select>

                                <select x-model="sortBy" class="bg-gray-900 border-gray-600 text-white text-sm rounded-lg focus:border-green-500">
                                    <option value="salary-desc">$ High-Low</option>
                                    <option value="salary-asc">$ Low-High</option>
                                    <option value="ppg-desc">PPG High-Low</option>
                                    <option value="name-asc">Name A-Z</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-4 space-y-2 max-h-[700px] overflow-y-auto">
                            @foreach($players as $player)
                                <div x-show="filterPlayer({{ $player->id }}, '{{ addslashes($player->name) }}', '{{ $player->position }}', '{{ $player->team }}', {{ $player->salary }})"
                                     class="bg-gray-900 border border-gray-700 rounded-lg p-4 hover:border-green-500 cursor-pointer transition group"
                                     @click="addPlayer({{ $player->id }}, '{{ addslashes($player->name) }}', '{{ $player->position }}', {{ $player->salary }}, '{{ $player->team }}', {{ $player->ppg }})">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="font-black text-white text-lg group-hover:text-green-400 transition">{{ $player->name }}</span>
                                                <span class="text-xs font-bold px-2 py-1 bg-blue-500/10 text-blue-400 rounded">{{ $player->position }}</span>
                                                <span class="text-xs font-semibold px-2 py-1 bg-gray-800 text-gray-300 rounded">{{ $player->team }}</span>
                                            </div>
                                            <div class="flex gap-4 text-xs text-gray-400">
                                                <span><strong class="text-white">{{ number_format($player->ppg, 1) }}</strong> PPG</span>
                                                <span><strong class="text-white">{{ number_format($player->rpg, 1) }}</strong> RPG</span>
                                                <span><strong class="text-white">{{ number_format($player->apg, 1) }}</strong> APG</span>
                                                <span class="text-green-400"><strong>{{ number_format($player->spg, 1) }}</strong>S <strong>{{ number_format($player->bpg, 1) }}</strong>B</span>
                                            </div>
                                        </div>
                                        <div class="text-right ml-4">
                                            <p class="font-black text-white text-xl">${{ number_format($player->salary) }}</p>
                                            <p class="text-xs text-gray-500">{{ number_format($player->mpg, 1) }} MPG</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden sticky top-6">
                        <div class="p-6 border-b border-gray-700">
                            <h3 class="text-xl font-black text-white">Your Lineup</h3>
                        </div>

                        <div class="p-4 space-y-2">
                            <template x-for="(slot, index) in slots" :key="index">
                                <div class="bg-gray-900 border border-gray-700 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-black text-gray-400" x-text="slot.position"></span>
                                        <template x-if="slot.player">
                                            <button @click="removePlayer(index)" class="text-red-400 hover:text-red-300 text-xs font-bold">
                                                REMOVE
                                            </button>
                                        </template>
                                    </div>

                                    <template x-if="!slot.player">
                                        <div class="text-center py-6 border-2 border-dashed border-gray-700 rounded-lg">
                                            <svg class="w-8 h-8 mx-auto text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <p class="text-xs text-gray-500 font-semibold">Select Player</p>
                                        </div>
                                    </template>

                                    <template x-if="slot.player">
                                        <div>
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <p class="font-black text-white" x-text="slot.player.name"></p>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <span class="text-xs font-bold px-2 py-0.5 bg-blue-500/10 text-blue-400 rounded" x-text="slot.player.position"></span>
                                                        <span class="text-xs font-semibold text-gray-400" x-text="slot.player.team"></span>
                                                    </div>
                                                    <p class="text-xs text-gray-400 mt-2">
                                                        <span x-text="slot.player.ppg.toFixed(1)"></span> PPG
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-black text-white text-lg">$<span x-text="slot.player.salary.toLocaleString()"></span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="p-6 border-t border-gray-700">
                            <form method="POST" action="{{ route('lineups.update', $lineup->id) }}" @submit="prepareSubmit($event)">
                                @csrf
                                @method('PUT')

                                <input type="text"
                                       name="lineup_name"
                                       x-model="lineupName"
                                       placeholder="Lineup Name (Optional)"
                                       class="w-full mb-4 bg-gray-900 border-gray-600 text-white rounded-lg focus:border-green-500 focus:ring-green-500">

                                <template x-for="(slot, index) in slots">
                                    <template x-if="slot.player">
                                        <div>
                                            <input type="hidden" :name="'players[' + index + '][player_id]'" :value="slot.player.id">
                                            <input type="hidden" :name="'players[' + index + '][position_slot]'" :value="slot.position">
                                        </div>
                                    </template>
                                </template>

                                <button type="submit"
                                        :disabled="!isValid()"
                                        :class="isValid() ? 'bg-accent-green hover:bg-green-600 text-black cursor-pointer' : 'bg-gray-700 text-gray-500 cursor-not-allowed'"
                                        class="w-full py-4 rounded-xl font-black text-lg transition shadow-lg">
                                    <span x-show="isValid()">UPDATE LINEUP</span>
                                    <span x-show="!isValid()">COMPLETE LINEUP TO UPDATE</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function lineupBuilder() {
            return {
                slots: [
                    {position: 'PG', player: null},
                    {position: 'SG', player: null},
                    {position: 'SF', player: null},
                    {position: 'PF', player: null},
                    {position: 'C', player: null},
                    {position: 'G', player: null},
                    {position: 'F', player: null},
                    {position: 'UTIL', player: null}
                ],
                totalSalary: 0,
                filterPosition: '',
                filterTeam: '',
                filterSalary: '',
                sortBy: 'salary-desc',
                searchQuery: '',
                lineupName: '{{ $lineup->lineup_name ?? "" }}',

                init() {
                    const existingPlayers = @json($existingLineup);

                    existingPlayers.forEach(item => {
                        const slotIndex = this.slots.findIndex(slot => slot.position === item.position_slot);
                        if (slotIndex !== -1) {
                            this.slots[slotIndex].player = item.player;
                        }
                    });

                    this.updateTotals();
                },

                addPlayer(id, name, position, salary, team, ppg) {
                    if (this.slots.some(slot => slot.player && slot.player.id === id)) {
                        this.showError(`${name} is already in your lineup!`);
                        return;
                    }

                    if (this.totalSalary + salary > 50000) {
                        const over = (this.totalSalary + salary) - 50000;
                        this.showError(`Cannot add ${name}. This would exceed salary cap by $${over.toLocaleString()}.`);
                        return;
                    }

                    let slotIndex = -1;

                    slotIndex = this.slots.findIndex(slot => !slot.player && slot.position === position);

                    if (slotIndex === -1 && (position === 'PG' || position === 'SG')) {
                        slotIndex = this.slots.findIndex(slot => !slot.player && slot.position === 'G');
                    }

                    if (slotIndex === -1 && (position === 'SF' || position === 'PF')) {
                        slotIndex = this.slots.findIndex(slot => !slot.player && slot.position === 'F');
                    }

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

                removePlayer(index) {
                    this.slots[index].player = null;
                    this.updateTotals();
                },

                updateTotals() {
                    this.totalSalary = this.slots.reduce((sum, slot) => {
                        return sum + (slot.player ? slot.player.salary : 0);
                    }, 0);
                },

                isValid() {
                    return this.slots.every(slot => slot.player !== null) && this.totalSalary <= 50000;
                },

                filterPlayer(id, name, position, team, salary) {
                    if (this.searchQuery && !name.toLowerCase().includes(this.searchQuery.toLowerCase())) {
                        return false;
                    }

                    if (this.filterPosition && position !== this.filterPosition) {
                        return false;
                    }

                    if (this.filterTeam && team !== this.filterTeam) {
                        return false;
                    }

                    if (this.filterSalary) {
                        if (this.filterSalary === '10000+' && salary < 10000) return false;
                        if (this.filterSalary === '8000-10000' && (salary < 8000 || salary >= 10000)) return false;
                        if (this.filterSalary === '6000-8000' && (salary < 6000 || salary >= 8000)) return false;
                        if (this.filterSalary === '4000-6000' && (salary < 4000 || salary >= 6000)) return false;
                        if (this.filterSalary === '0-4000' && salary >= 4000) return false;
                    }

                    return true;
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
                },

                showError(message) {
                    alert(message);
                }
            }
        }
    </script>
</x-app-layout>
