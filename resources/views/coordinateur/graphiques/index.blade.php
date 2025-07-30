<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Graphiques statistiques') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtres -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <form id="filterForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Classe</label>
                        <select name="classe" id="classe" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Toutes les classes</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date début</label>
                        <input type="date" name="date_debut" id="date_debut" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date fin</label>
                        <input type="date" name="date_fin" id="date_fin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </form>
            </div>

            <!-- Graphique de taux de présence par étudiant -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Taux de présence par étudiant</h3>
                <canvas id="presenceEtudiantChart"></canvas>
            </div>

            <!-- Graphique de taux de présence par classe -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Taux de présence par classe</h3>
                <canvas id="presenceClasseChart"></canvas>
            </div>

            <!-- Graphique de volume de cours dispensés -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Volume de cours dispensés par type</h3>
                <canvas id="volumeCoursChart"></canvas>
            </div>

            <!-- Graphique cumulé de volume de cours -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Volume cumulé de cours dispensés</h3>
                <canvas id="volumeCoursCumuleChart"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Debug : afficher les données dans la console
        async function debugData() {
            const formData = new FormData(document.getElementById('filterForm'));
            const urls = [
                '/coordinateur/graphiques/presence-etudiant',
                '/coordinateur/graphiques/presence-classe',
                '/coordinateur/graphiques/volume-cours',
                '/coordinateur/graphiques/volume-cumule'
            ];

            for (const url of urls) {
                try {
                    const response = await fetch(`${url}?${new URLSearchParams(formData)}`);
                    const data = await response.json();
                    console.log(`Données de ${url}:`, data);
                } catch (error) {
                    console.error(`Erreur pour ${url}:`, error);
                }
            }
        }
    </script>
    <script>
        // Fonctions utilitaires pour les couleurs
        function getPresenceColor(taux) {
            if (taux >= 70) return '#1a5d1a'; // Vert foncé
            if (taux > 50) return '#90EE90'; // Vert clair
            if (taux > 30) return '#FFA500'; // Orange
            return '#FF0000'; // Rouge
        }

        // Initialisation des graphiques
        let presenceEtudiantChart, presenceClasseChart, volumeCoursChart, volumeCoursCumuleChart;

        // Fonction pour mettre à jour les graphiques
        function updateCharts() {
            const formData = new FormData(document.getElementById('filterForm'));

            // Mise à jour du graphique des présences par étudiant
            fetch(`/coordinateur/graphiques/presence-etudiant?${new URLSearchParams(formData)}`)
                .then(response => response.json())
                .then(data => {
                    if (presenceEtudiantChart) presenceEtudiantChart.destroy();
                    presenceEtudiantChart = new Chart(document.getElementById('presenceEtudiantChart'), {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.data,
                                backgroundColor: data.data.map(taux => getPresenceColor(taux))
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false },
                                title: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    title: { display: true, text: 'Taux de présence (%)' }
                                }
                            }
                        }
                    });
                });

            // Mise à jour du graphique des présences par classe
            fetch(`/coordinateur/graphiques/presence-classe?${new URLSearchParams(formData)}`)
                .then(response => response.json())
                .then(data => {
                    if (presenceClasseChart) presenceClasseChart.destroy();
                    presenceClasseChart = new Chart(document.getElementById('presenceClasseChart'), {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.data,
                                backgroundColor: '#4F46E5'
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    title: { display: true, text: 'Taux de présence (%)' }
                                }
                            }
                        }
                    });
                });

            // Mise à jour du graphique de volume de cours
            fetch(`/coordinateur/graphiques/volume-cours?${new URLSearchParams(formData)}`)
                .then(response => response.json())
                .then(data => {
                    if (volumeCoursChart) volumeCoursChart.destroy();
                    volumeCoursChart = new Chart(document.getElementById('volumeCoursChart'), {
                        type: 'bar',
                        data: {
                            labels: ['Présentiel', 'E-learning', 'Workshop'],
                            datasets: [{
                                data: data,
                                backgroundColor: ['#4F46E5', '#10B981', '#F59E0B']
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: { display: true, text: 'Nombre d\'heures' }
                                }
                            }
                        }
                    });
                });

            // Mise à jour du graphique de volume cumulé
            fetch(`/coordinateur/graphiques/volume-cumule?${new URLSearchParams(formData)}`)
                .then(response => response.json())
                .then(data => {
                    if (volumeCoursCumuleChart) volumeCoursCumuleChart.destroy();
                    volumeCoursCumuleChart = new Chart(document.getElementById('volumeCoursCumuleChart'), {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Présentiel',
                                data: data.presentiel,
                                borderColor: '#4F46E5',
                                fill: false
                            }, {
                                label: 'E-learning',
                                data: data['e-learning'],
                                borderColor: '#10B981',
                                fill: false
                            }, {
                                label: 'Workshop',
                                data: data.workshop,
                                borderColor: '#F59E0B',
                                fill: false
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: true }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: { display: true, text: 'Nombre d\'heures cumulées' }
                                }
                            }
                        }
                    });
                });
        }

        // Événements pour les filtres
        document.querySelectorAll('#filterForm select, #filterForm input').forEach(element => {
            element.addEventListener('change', () => {
                debugData();
                updateCharts();
            });
        });

        // Initialisation
        document.addEventListener('DOMContentLoaded', () => {
            debugData();
            updateCharts();
        });
    </script>
@endpush

</x-app-layout>
