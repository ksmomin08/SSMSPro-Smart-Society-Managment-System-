<!DOCTYPE html>
<html>

<head>

    <title>Maintenance Report</title>

    <style>

        body{
            font-family:Arial;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table, th, td{
            border:1px solid black;
        }

        th, td{
            padding:10px;
            text-align:left;
        }

        th{
            background:#2563eb;
            color:white;
        }

    </style>

</head>

<body>

<h2>Maintenance Report</h2>

<table>

<tr>

    <th>ID</th>
    <th>Resident</th>
    <th>Month</th>
    <th>Amount</th>
    <th>Status</th>

</tr>

@foreach($maintenances as $maintenance)

<tr>

    <td>{{ $maintenance->id }}</td>

    <td>{{ $maintenance->resident->name }}</td>

    <td>{{ $maintenance->month }}</td>

    <td>₹ {{ $maintenance->amount }}</td>

    <td>{{ $maintenance->payment_status }}</td>

</tr>

@endforeach

</table>

</body>
</html>