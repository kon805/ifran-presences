<div x-data="{ open: false, notifications: [], unreadCount: 0 }"
    x-init="() => {
        fetch('{{ route('notifications.count') }}')
            .then(response => response.json())
            .then(data => unreadCount = data.count);

        setInterval(() => {
            fetch('{{ route('notifications.count') }}')
                .then(response => response.json())
                .then(data => unreadCount = data.count);
        }, 60000);
    }"
    class="relative">

    <!-- Cloche de notification -->
    <button @click="
        open = !open;
        if (open) {
            fetch('{{ route('notifications.index') }}')
                .then(response => response.json())
                .then(data => notifications = data);
        }"
        class="relative p-2 text-gray-600 hover:text-blue-600 focus:outline-none">
        <i class="fas fa-bell text-xl"></i>

        <!-- Badge de notifications non lues -->
        <div x-show="unreadCount > 0"
            x-transition
            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
            <span x-text="unreadCount"></span>
        </div>
    </button>

    <!-- Modal des notifications -->
    <div x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.away="open = false"
        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">

        <div class="max-h-96 overflow-y-auto">
            <div class="p-4 border-b border-gray-200">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                    <div class="space-x-2">
                        @if(config('app.debug'))
                        <a href="{{ url('/test-notification') }}" class="text-xs text-blue-600 hover:text-blue-800">
                            Test
                        </a>
                        @endif
                        <button
                            @click="fetch('{{ route('notifications.deleteAll') }}', {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                }
                            }).then(() => {
                                notifications = [];
                                unreadCount = 0;
                            })"
                            class="text-xs text-red-600 hover:text-red-800">
                            Tout supprimer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Liste des notifications -->
            <div class="divide-y divide-gray-200">
                <template x-if="notifications.length === 0">
                    <div class="p-4 text-center text-gray-500">
                        Aucune notification
                    </div>
                </template>

                <template x-for="notif in notifications" :key="notif.id">
                    <div class="p-4 hover:bg-gray-50" :class="{ 'bg-blue-50': !notif.lu }">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <template x-if="notif.type === 'absence'">
                                    <i class="fas fa-user-clock text-red-500"></i>
                                </template>
                                <template x-if="notif.type === 'status'">
                                    <template x-if="notif.data && notif.data.new_status">
                                        <i class="fas fa-check-circle text-green-500"></i>
                                    </template>
                                    <template x-if="notif.data && !notif.data.new_status">
                                        <i class="fas fa-user-slash text-yellow-500"></i>
                                    </template>
                                </template>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <p class="text-sm text-gray-900" x-text="notif.message"></p>
                                <p class="mt-1 text-xs text-gray-500" x-text="new Date(notif.created_at).toLocaleString()"></p>
                            </div>
                            <div class="flex space-x-2">
                                <button @click="
                                    fetch(`/notifications/${notif.id}/read`, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                        }
                                    })
                                    .then(() => {
                                        notif.lu = true;
                                        unreadCount = Math.max(0, unreadCount - 1);
                                    })"
                                    x-show="!notif.lu"
                                    class="text-blue-600 hover:text-blue-800"
                                    title="Marquer comme lu">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button @click="
                                    fetch(`/notifications/${notif.id}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                        }
                                    })
                                    .then(() => {
                                        notifications = notifications.filter(n => n.id !== notif.id);
                                        if (!notif.lu) {
                                            unreadCount = Math.max(0, unreadCount - 1);
                                        }
                                    })"
                                    class="text-red-600 hover:text-red-800"
                                    title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
