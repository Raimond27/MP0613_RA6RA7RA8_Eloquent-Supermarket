<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Product {{ $product->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .details {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #444;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .fees {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $product->name }}</h1>
    </div>

    <!-- Product Details Section -->
    <div class="details">
        <p><strong>Description:</strong> {{ $product->description }}</p>
        <!-- Category, or "N/A" if no category exists -->
        <p>
            <strong>Category:</strong>
            {{ $product->category ? $product->category->name : 'N/A' }}
        </p>
        <!-- Base Price formatted as currency -->
        <p><strong>Base Price:</strong> ${{ number_format($product->price, 2) }}</p>
        <!-- Final Price after fees (if any) -->
        <p><strong>Final Price:</strong> ${{ number_format($product->final_price, 2) }}</p>
    </div>

    <!-- Fees Section: Display fee details if fees exist for the product -->
    @if ($product->fees->isNotEmpty())
        <div class="fees">
            <h3>Fees</h3>
            <table>
                <thead>
                    <tr>
                        <th>Fee Name</th>
                        <th>Period</th>
                        <th>Variation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->fees as $fee)
                        <tr>
                            <td>{{ $fee->name }}</td>
                            <td>{{ $fee->date_start }} to {{ $fee->date_end }}</td>
                            <!-- Display fee variation type and amount -->
                            <td>
                                {{ ucfirst($fee->pivot->variation_type) }}
                                ({{ $fee->pivot->variation_ammount }})
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</body>

</html>
