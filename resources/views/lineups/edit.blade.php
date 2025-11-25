<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Build Lineup - {{ $contest->name }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="lineupBuilder()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Player List -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4">Available Players</h3>

                        <!-- Filters -->
                        <div class="mb-4 flex space-x-2">
                            <select x-model="filterPosition" class="rounded-md border-gray-300">
                                <option value="">All Positions</option>
                                <option value="PG">PG</option>
                                <option value="SG">SG</option>
                                <option value="SF">SF</option>
                                <option value="PF">PF</option>
                                <option value="C">C</option>
                            </select>
                        </div>

                        <!-- Player List -->
                        <div class="space-y-2 max-h-[600px] overflow-y-auto">
                            @foreach($players as $player)
                                <div x-show="filterPosition === '' || '{{ $player->position }}' === filterPosition"
                                     class="border rounded-lg p-3 hover:bg-gray-50 cursor-pointer"
                                     @click="addPlayer({{ $player->id }}, '{{ $player->name }}', '{{ $player->position }}', {{ $player->salary }}, '{{ $player->team }}', {{ $player->ppg }})">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-bold">{{ $player->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $player->position }} - {{ $player->team }} | {{ $player->ppg }} PPG</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold">${{ number_format($player->salary) }}</p>
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

                addPlayer(id, name, position, salary, team, ppg) {
                    // Check if player already added
                    if (this.slots.some(slot => slot.player && slot.player.id === id)) {
                        alert('Player already in lineup!');
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
                        alert('No available slot for this player!');
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
                        alert('Please fill all slots and stay under salary cap!');
                    }
                }
            }
        }
    </script>
</x-app-layout>
