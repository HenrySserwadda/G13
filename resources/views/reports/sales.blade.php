@extends('components.dashboard')
@section('title', 'Sales Report- DURABAG')
@section('content')
@section('page-title', 'Sales Report')
@section('page-description', 'Visual overview of products ordered per month')

{{-- this is the div for the filters --}}
<div>

</div>
{{-- this is the div for the actual chart --}}
<div>
    <div>
        <div>
            <canvas id="barChartforpdtspermonth"></canvas>
        </div>
    </div>
</div>
<div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="app.js"></script> 
@endpush