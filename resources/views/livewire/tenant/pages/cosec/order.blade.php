<div class="mb-4 grid grid-cols-1 gap-6">
    <div class="">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title">Cosec Orders</h3>
                </div>
            </div>
            <div class="card-body">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="text-xs uppercase bg-gray">
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                Order ID
                            </th>
                            <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                Comapny Name
                            </th>
                            <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                Requested Director
                            </th>
                            <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                Resolution Option
                            </th>
                            <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                Cost of Order
                            </th>
                            <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                Status
                            </th>
                            <th class="p-3.5 text-sm text-black font-semibold min-w-[10rem] text-center">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach ($orders as $order)
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                                {{ $order->uuid }}
                            </td>
                            <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                                {{ $order->company_name }}
                            </td>
                            <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                                {{ $order->user }}
                            </td>
                            <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                                {{ $order->form_name }}
                            </td>
                            <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                {{ $order->cost }}
                            </th>
                            <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                                {{
                                    $order->status == 0 ? 'Requested' : ($order->status == 1 ?  'Approval' : 'Denied')
                                }}
                            </td>
                            <td class="p-3.5 text-sm text-black font-semibold min-w-[10rem] text-center">
                                @if($order->status == 1)
                                <a href="{{ route('cosec.view', $order->id) }}" class="btn bg-primary text-white">
                                    View
                                </a>
                                <button wire:click="printForm({{ $order->id }})" class="btn bg-primary text-white">
                                    Print PDF
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
