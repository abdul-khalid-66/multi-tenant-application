<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($details as $detail)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $detail->product->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $detail->variant?->name ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $detail->quantity_returned }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($detail->refund_amount_per_unit, 2) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($detail->total_refund_amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>