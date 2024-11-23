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
    <h1>Relatório anual de gestão</h1>
    <p><strong>Cantina:</strong> {{ $cantina->cantina->canteen_name }}</p>

    <h2>Dados mensais:</h2>
<table>
    <tr>
        <th>Data</th>
        <th>Vendas Totais</th>
        <th>Lucro</th>
        <th>Média por Venda</th>
        <th>Produto mais pedido do mes</th>
    </tr>
    @foreach ($monthManagements as $months)
        <tr>
            <td>{{ \Carbon\Carbon::parse($months->created_at)->format('m/Y') }}</td>
            <td>R$ {{ number_format($months->total_monthly_sales, 2, ',', '.') }}</td>
            <td>R$ {{ number_format($months->monthly_profit, 2, ',', '.') }}</td>
            <td>R$ {{ number_format($months->average_value_of_monthly_sales, 2, ',', '.') }}</td>
            <td>{{ $months->monthly_best_seling_product }}</td>
        </tr>
    @endforeach
</table>

    <h2>Resumo anual:</h2>
    <p><strong>Total de vendas anuais:</strong> R$ {{ number_format($annualManagement->total_sales_for_the_year, 2, ',', '.') }}</p>
    <p><strong>Lucro anual:</strong> R$ {{ number_format($annualManagement->annual_profit, 2, ',', '.') }}</p>
    <p><strong>Valor médio por venda:</strong> R$ {{ number_format($annualManagement->average_value_of_annual_sales, 2, ',', '.') }}</p>
    <p><strong>Produto mais vendido do ano: </strong>{{$annualManagement->annual_best_seling_product }}</p>    
</body>
</html>
