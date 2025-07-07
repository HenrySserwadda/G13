@extends('components.dashboard')
@section('title', 'Sales Report- DURABAG')
@section('content')
@section('page-title', 'Sales Report')
@section('page-description', 'Visual overview of products ordered per month')

{{-- this is the div for the products ordered per month --}}
<div class="mb-6">
    <div>
        <h3 class="underline font-bold uppercase">Products and their quantities ordered per month</h3>
        <div>
            <canvas id="barChartforpdtspermonth"></canvas>
        </div>
    </div>

    <div class="mt-4">
        <table id="pdtsPerMonth" class="w-full table-auto text-left border-gray-300 border-collapse ">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Month</th>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Product</th>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Quantity</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
{{-- for the orders per month --}}
<div class="mt-12">
    <div>
        <h3 class="underline font-bold uppercase">Orders made per month</h3>
        <div>
            <canvas id="noOfOrders"></canvas>
        </div>
    </div>
    <div class="mt-4">
        <table id="noOfOrdersTable" class="w-full table-auto text-left border-gray-300 border-collapse ">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Month</th>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Total orders</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
    fetch('/reports/pdtsPerMonth')
        .then(response => response.json())
        .then(pdtsPerMonth => {
            {{-- for the chart --}}
            const grouped = {};
            pdtsPerMonth.forEach(item => {
                const month = item.order_month;
                const product = item.product_name;
                const quantity = item.total_quantity_ordered;

                if (!grouped[product]) grouped[product] = {};
                grouped[product][month] = quantity;
            });

            const months = [...new Set(pdtsPerMonth.map(item => item.order_month))].sort();

            const datasets = Object.keys(grouped).map((product, index) => {
                const color = `hsl(${index * 60}, 70%, 60%)`;
                return {
                    label: product,
                    backgroundColor: color,
                    data: months.map(month => grouped[product][month] || 0),
                };
            });

            new Chart(document.getElementById('barChartforpdtspermonth'), {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, beginAtZero: true }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Products Ordered Per Month'
                        }
                    }
                }
            });

            {{--for the table--}}
            const tableBody = document.querySelector('#pdtsPerMonth tbody');
            pdtsPerMonth.forEach(item => {
                const row = document.createElement('tr');

                const monthCell = document.createElement('td');
                monthCell.textContent = new Date(item.order_month + "-01").toLocaleString('default', { month: 'long', year: 'numeric' });

                const productCell = document.createElement('td');
                productCell.textContent = item.product_name;

                const qtyCell = document.createElement('td');
                qtyCell.textContent = item.total_quantity_ordered;

                row.appendChild(monthCell);
                row.appendChild(productCell);
                row.appendChild(qtyCell);
                tableBody.appendChild(row);
            });
        });
</script>
<script>
    fetch('/noOfOrders')
    .then(response => response.json())
    .then(noOfOrders => {
        const labels = noOfOrders.map(order => order.month);
        const data = noOfOrders.map(order => order.total);
        new Chart(document.getElementById('noOfOrders'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: "Total Orders",
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    data: data
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Orders per month'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        {{-- for the table --}}
        const tBody = document.querySelector('#noOfOrdersTable tbody');
        noOfOrders.forEach(item => {
            const row = document.createElement('tr');
            const monthCell = document.createElement('td');
            monthCell.className = 'py-2 px-4 border-b border-gray-200 dark:border-gray-700';
            monthCell.textContent = new Date(item.month + "-01").toLocaleString('default', {
                month: 'long',
                year: 'numeric'
            });
            const totalCell = document.createElement('td');
            totalCell.className = 'py-2 px-4 border-b border-gray-200 dark:border-gray-700';
            totalCell.textContent = item.total;
            row.appendChild(monthCell);
            row.appendChild(totalCell);
            tBody.appendChild(row);
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="app.js"></script> 
@endpush