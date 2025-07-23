@extends('components.dashboard')

@section('title', 'User Segments')
@section('page-title', 'User Segments')
@section('page-description', 'View users grouped by purchase patterns, preferences, and bio-data.')

@push('styles')
<style>
    .segment-group {
        border-radius: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        background: #fff;
        margin-bottom: 2rem;
        border: 1px solid #e5e7eb;
    }
    .segment-header {
        cursor: pointer;
        background: #f3f4f6;
        padding: 1rem 1.5rem;
        font-size: 1.25rem;
        font-weight: 600;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .segment-header .toggle-icon {
        transition: transform 0.2s;
    }
    .segment-header.collapsed .toggle-icon {
        transform: rotate(-90deg);
    }
    .segment-content {
        padding: 1.5rem;
        display: block;
    }
    .segment-content.collapsed {
        display: none;
    }
    .user-table th, .user-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    .user-table th {
        background: #f9fafb;
        position: sticky;
        top: 0;
        z-index: 1;
    }
    .user-table tr:hover {
        background: #f3f4f6;
        transition: background 0.2s;
    }
    .search-bar {
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .search-bar input {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        width: 300px;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">User Segments</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div>
            <h2 class="text-lg font-bold mb-2">User Distribution by Gender</h2>
            <canvas id="genderChart" height="200"></canvas>
        </div>
        <div>
            <h2 class="text-lg font-bold mb-2">User Count by Segment</h2>
            <canvas id="segmentChart" height="200"></canvas>
        </div>
    </div>
    <div class="mb-8">
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <h2 class="text-lg font-bold">Number of Orders or Amount Spent per Day (Last 30 Days)</h2>
            <select id="orders-user-filter" class="border rounded px-2 py-1">
                <option value="all">All Users</option>
                @foreach($allUsers as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
            <select id="orders-metric-filter" class="border rounded px-2 py-1">
                <option value="orders">Orders</option>
                <option value="amount">Amount Spent</option>
            </select>
            <input type="date" id="orders-start" class="border rounded px-2 py-1">
            <input type="date" id="orders-end" class="border rounded px-2 py-1">
        </div>
        <canvas id="ordersOverTimeChart" height="120"></canvas>
    </div>
    <div class="search-bar">
        <input type="text" id="user-search" placeholder="Search users by name, email, or category...">
        <button id="expand-all" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Expand All</button>
        <button id="collapse-all" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Collapse All</button>
    </div>
    @foreach($segments as $segmentName => $segmentGroup)
        <div class="segment-group">
            <div class="segment-header" onclick="toggleSegment(this)">
                <span>{{ $segmentName }}</span>
                <span class="toggle-icon">&#9660;</span>
            </div>
            <div class="segment-content">
            @if(is_array($segmentGroup))
                @foreach($segmentGroup as $subGroupName => $users)
                    <div class="mb-4">
                        @if(!is_int($subGroupName))
                            <h3 class="text-xl font-medium mb-2">{{ $subGroupName }}</h3>
                        @endif
                        @php
                            $userList = is_array($users) || $users instanceof \Illuminate\Support\Collection ? $users : [$users];
                        @endphp
                        @if(count($userList))
                            <div class="overflow-x-auto">
                                <table class="user-table min-w-full bg-white border border-gray-200 rounded-lg">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Gender</th>
                                            <th>Category</th>
                                            <th>Orders</th>
                                            <th>Last Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($userList as $user)
                                            @php
                                                $orderSummary = $user->orders->map(function($order) {
                                                    return [
                                                        'id' => $order->id,
                                                        'total' => $order->total,
                                                        'created_at' => $order->created_at ? $order->created_at->format('Y-m-d') : '',
                                                    ];
                                                });
                                            @endphp
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->gender ?? 'N/A' }}</td>
                                                <td>{{ $user->category ?? 'N/A' }}</td>
                                                <td>{{ $user->orders->count() }}</td>
                                                <td>
                                                    @php $lastOrder = $user->orders->sortByDesc('created_at')->first(); @endphp
                                                    {{ $lastOrder ? $lastOrder->created_at->format('Y-m-d') : 'N/A' }}
                                                </td>
                                                <td>
                                                    <button class="view-orders-btn bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded"
                                                        data-user="{{ $user->id }}"
                                                        data-orders='@json($orderSummary)'>
                                                        View Orders
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-gray-500">No users in this group.</div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="text-gray-500">No users in this segment.</div>
            @endif
            </div>
        </div>
    @endforeach
</div>
<!-- Modal for order summary -->
<div id="orders-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
        <button id="close-orders-modal" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-2xl">&times;</button>
        <h2 class="text-xl font-bold mb-4">Order Summary</h2>
        <div id="orders-modal-content"></div>
    </div>
</div>
@endSection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let genderChart, segmentChart, ordersOverTimeChart;

function updateCharts(filteredUsers) {
    // Gender data
    const genderCounts = {};
    filteredUsers.forEach(user => {
        const gender = user.gender || 'Unknown';
        genderCounts[gender] = (genderCounts[gender] || 0) + 1;
    });
    const genderLabels = Object.keys(genderCounts);
    const genderValues = Object.values(genderCounts);
    genderChart.data.labels = genderLabels;
    genderChart.data.datasets[0].data = genderValues;
    genderChart.update();

    // Segment data
    const segmentCounts = {};
    document.querySelectorAll('.segment-group').forEach((group, i) => {
        const label = group.querySelector('.segment-header span').textContent;
        const rows = group.querySelectorAll('tbody tr');
        let count = 0;
        rows.forEach(row => {
            if (row.style.display !== 'none') count++;
        });
        segmentCounts[label] = count;
    });
    segmentChart.data.labels = Object.keys(segmentCounts);
    segmentChart.data.datasets[0].data = Object.values(segmentCounts);
    segmentChart.update();
}

document.addEventListener('DOMContentLoaded', function() {
    // Prepare initial user data for dynamic filtering
    const allUsers = [];
    document.querySelectorAll('.user-table tbody tr').forEach(row => {
        allUsers.push({
            name: row.children[0].textContent,
            email: row.children[1].textContent,
            gender: row.children[2].textContent,
            category: row.children[3].textContent,
        });
    });

    // Gender Pie Chart
    const genderData = @json($genderCounts);
    const genderLabels = Object.keys(genderData);
    const genderValues = Object.values(genderData);
    genderChart = new Chart(document.getElementById('genderChart'), {
        type: 'pie',
        data: {
            labels: genderLabels,
            datasets: [{
                data: genderValues,
                backgroundColor: ['#60a5fa', '#f472b6', '#fbbf24', '#a3e635', '#f87171', '#818cf8'],
            }]
        },
        options: {
            responsive: true,
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1200,
                easing: 'easeOutQuart'
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.parsed;
                            const percent = total ? ((value / total) * 100).toFixed(1) : 0;
                            return `${context.label}: ${value} (${percent}%)`;
                        }
                    }
                },
                legend: { display: true }
            }
        }
    });

    // Segment Bar Chart
    const segmentData = @json($segmentCounts);
    const segmentLabels = Object.keys(segmentData);
    const segmentValues = Object.values(segmentData);
    segmentChart = new Chart(document.getElementById('segmentChart'), {
        type: 'bar',
        data: {
            labels: segmentLabels,
            datasets: [{
                label: 'Users',
                data: segmentValues,
                backgroundColor: '#60a5fa'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            animation: {
                duration: 1200,
                easing: 'easeOutQuart',
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed.x}`;
                        }
                    }
                }
            },
            onClick: (e, elements) => {
                if (elements.length > 0) {
                    const idx = elements[0].index;
                    const label = segmentChart.data.labels[idx];
                    // Scroll to the segment group
                    document.querySelectorAll('.segment-group').forEach(group => {
                        const header = group.querySelector('.segment-header span');
                        if (header && header.textContent === label) {
                            group.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            group.querySelector('.segment-header').classList.remove('collapsed');
                            group.querySelector('.segment-content').classList.remove('collapsed');
                        }
                    });
                }
            }
        }
    });

    // Orders Over Time (Daily) with user, metric, and date range filters
    const ordersByDayUser = @json($ordersByDayUser);
    const amountByDayUser = @json($amountByDayUser);
    const allDays = Object.keys(ordersByDayUser['all']).sort();
    const last30Days = allDays.slice(-30);
    // Default: all users, orders, last 30 days
    function getOrdersData(userId, metric, start, end) {
        let dataObj = metric === 'orders' ? ordersByDayUser : amountByDayUser;
        let days = allDays;
        if (start || end) {
            days = days.filter(d => (!start || d >= start) && (!end || d <= end));
        } else {
            days = last30Days;
        }
        const data = days.map(d => (dataObj[userId] && dataObj[userId][d]) ? dataObj[userId][d] : 0);
        return { days, data };
    }
    let { days, data } = getOrdersData('all', 'orders');
    ordersOverTimeChart = new Chart(document.getElementById('ordersOverTimeChart'), {
        type: 'line',
        data: {
            labels: days,
            datasets: [{
                label: 'Orders',
                data: data,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.2)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            animation: { duration: 1200, easing: 'easeOutQuart' },
            plugins: { legend: { display: false } }
        }
    });
    function updateOrdersChart() {
        const userId = document.getElementById('orders-user-filter').value;
        const metric = document.getElementById('orders-metric-filter').value;
        const start = document.getElementById('orders-start').value;
        const end = document.getElementById('orders-end').value;
        const { days, data } = getOrdersData(userId, metric, start, end);
        ordersOverTimeChart.data.labels = days;
        ordersOverTimeChart.data.datasets[0].data = data;
        ordersOverTimeChart.data.datasets[0].label = metric === 'orders' ? 'Orders' : 'Amount Spent';
        ordersOverTimeChart.update();
    }
    document.getElementById('orders-user-filter').addEventListener('change', updateOrdersChart);
    document.getElementById('orders-metric-filter').addEventListener('change', updateOrdersChart);
    document.getElementById('orders-start').addEventListener('change', updateOrdersChart);
    document.getElementById('orders-end').addEventListener('change', updateOrdersChart);

    // Search/filter users
    const searchInput = document.getElementById('user-search');
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const filteredUsers = [];
        document.querySelectorAll('.user-table tbody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            const show = text.includes(query);
            row.style.display = show ? '' : 'none';
            if (show) {
                filteredUsers.push({
                    name: row.children[0].textContent,
                    email: row.children[1].textContent,
                    gender: row.children[2].textContent,
                    category: row.children[3].textContent,
                });
            }
        });
        updateCharts(filteredUsers.length ? filteredUsers : allUsers);
    });
});

// Collapsible segment groups
function toggleSegment(header) {
    const content = header.nextElementSibling;
    header.classList.toggle('collapsed');
    content.classList.toggle('collapsed');
}

document.getElementById('expand-all').onclick = function() {
    document.querySelectorAll('.segment-header').forEach(header => header.classList.remove('collapsed'));
    document.querySelectorAll('.segment-content').forEach(content => content.classList.remove('collapsed'));
};
document.getElementById('collapse-all').onclick = function() {
    document.querySelectorAll('.segment-header').forEach(header => header.classList.add('collapsed'));
    document.querySelectorAll('.segment-content').forEach(content => content.classList.add('collapsed'));
};

// Modal for viewing orders
function closeOrdersModal() {
    document.getElementById('orders-modal').classList.add('hidden');
}
document.getElementById('close-orders-modal').onclick = closeOrdersModal;
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('view-orders-btn')) {
        const orders = JSON.parse(e.target.getAttribute('data-orders'));
        let html = '';
        if (orders.length === 0) {
            html = '<div class="text-gray-500">No orders found for this user.</div>';
        } else {
            html = '<table class="min-w-full border"><thead><tr><th class="px-4 py-2 border">Order ID</th><th class="px-4 py-2 border">Total</th><th class="px-4 py-2 border">Date</th></tr></thead><tbody>';
            orders.forEach(order => {
                html += `<tr>
                    <td class="px-4 py-2 border">${order.id}</td>
                    <td class="px-4 py-2 border">${order.total}</td>
                    <td class="px-4 py-2 border">${order.created_at}</td>
                </tr>`;
            });
            html += '</tbody></table>';
        }
        document.getElementById('orders-modal-content').innerHTML = html;
        document.getElementById('orders-modal').classList.remove('hidden');
    }
});
document.getElementById('orders-modal').addEventListener('click', function(e) {
    if (e.target === this) closeOrdersModal();
});
</script>
@endpush 