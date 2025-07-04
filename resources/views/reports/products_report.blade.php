@extends('components.dashboard')
@section('title', 'Users Report- DURABAG')
@section('page-title', 'Sales Report')
@section('page-description', 'Visual overview of products\' price')

@section('content')
<div>
    <div>
        <div>
            <canvas id="forpdtsByPrice"></canvas>
        </div>
    </div>
</div>
<div>
    <table id="pdtsByPrice" class="w-full table-auto text-left border-gray-300 border-collapse ">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Name</th>
                <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Price</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@endsection
@push('scripts')
<script>
    fetch('/reports/pdtsByPrice')
        .then(response => response.json())
        .then(pdtsByPrice => {
            const labels = pdtsByPrice.map(item => item.name);
            const data = pdtsByPrice.map(item => item.price);

            new Chart(document.getElementById('forpdtsByPrice'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Price',
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
                            text: 'Prices for Different Products'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Fill the table
            const tableBody = document.querySelector('#pdtsByPrice tbody');
            pdtsByPrice.forEach(item => {
                const row = document.createElement('tr');

                const nameCell = document.createElement('td');
                nameCell.textContent = item.name;

                const priceCell = document.createElement('td');
                priceCell.textContent = item.price;

                row.appendChild(nameCell);
                row.appendChild(priceCell);
                tableBody.appendChild(row);
            });
        });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="app.js"></script> 
@endpush