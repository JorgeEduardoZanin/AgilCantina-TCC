<!DOCTYPE html>
<html>
<head>
    <title>Monthly Management Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1, h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Relatório mensal de gestão</h1>
    <p><strong>Cantina:</strong> {{ $cantina->cantina->canteen_name }}</p>

    <h2>Dados diários:</h2>
<table>
    <tr>
        <th>Data</th>
        <th>Vendas Totais</th>
        <th>Lucro</th>
        <th>Média por Venda</th>
        <th>Produto mais pedido do dia</th>
    </tr>
    @foreach ($dailyManagements as $daily)
        <tr>
            <td>{{ \Carbon\Carbon::parse($daily->created_at)->format('d/m/Y') }}</td>
            <td>R$ {{ number_format($daily->total_sales_for_the_day, 2, ',', '.') }}</td>
            <td>R$ {{ number_format($daily->day_profit, 2, ',', '.') }}</td>
            <td>R$ {{ number_format($daily->average_value_of_day_sales, 2, ',', '.') }}</td>
            <td>{{ $daily->day_best_seling_product }}</td>
        </tr>
    @endforeach
</table>

    <h2>Resumo mensal:</h2>
    <p><strong>Total de vendas mensais:</strong> R$ {{ number_format($monthManagement->total_monthly_sales, 2, ',', '.') }}</p>
    <p><strong>Lucro mensal:</strong> R$ {{ number_format($monthManagement->monthly_profit, 2, ',', '.') }}</p>
    <p><strong>Valor médio por venda:</strong> R$ {{ number_format($monthManagement->average_value_of_monthly_sales, 2, ',', '.') }}</p>
    <p><strong>Produto mais vendido do mes: </strong>{{$monthManagement->monthly_best_seling_product }}</p>    
</body>
</html>
