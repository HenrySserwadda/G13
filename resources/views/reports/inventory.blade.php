@extends('components.dashboard')
@section('title', 'Inventories Report- DURABAG')
@section('page-title', 'Inventories Report')
@section('page-description', 'Visual overview of products\' price')

@section('content')
<div>
    <div>
        <h3 class="underline font-bold uppercase">Raw Materials and their available quantities</h3>
        <div>
            <canvas id="RawMaterialsOnHand"></canvas>
        </div>
    </div>
</div>
<div>
    <table id="forRawMaterialsOnHand" class="w-full table-auto text-left border-gray-300 border-collapse ">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Name</th>
                <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Available quantity</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@endsection
@push('scripts')
<script>
    fetch('/reports/onHand')
        .then(response => response.json())
        .then(onHand => {
            const labels = onHand.map(item => item.name);
            const data = onHand.map(item => item.quantity);

            new Chart(document.getElementById('RawMaterialsOnHand'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Quantity',
                        backgroundColor: 'rgba(26, 105, 173,0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        data: data
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Quantites of raw materials on hand'
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
            const tableBody = document.querySelector('#forRawMaterialsOnHand tbody');
            onHand.forEach(item => {
                const row = document.createElement('tr');

                const nameCell = document.createElement('td');
                nameCell.className='py-2 px-4 border-b border-gray-200 dark:border-gray-700';
                nameCell.textContent = item.name;

                const qtyCell = document.createElement('td');
                qtyCell.className='py-2 px-4 border-b border-gray-200 dark:border-gray-700';
                qtyCell.textContent = item.quantity;

                row.appendChild(nameCell);
                row.appendChild(qtyCell);
                tableBody.appendChild(row);
            });
        });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="app.js"></script> 
@endpush