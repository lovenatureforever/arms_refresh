<div class="mb-4 grid grid-cols-1 gap-6">
    <div class="">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Order Cart</h3>
            </div>

            <div class="card-body p-6">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600g mb-6">
                    <thead class="text-xs uppercase bg-gray">
                        <tr>
                            <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                Item ID
                            </th>
                            <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                Item
                            </th>
                            <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                Modified Date
                            </th>
                            <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">
                                Cost
                            </th>
                            <th class="p-3.5 text-sm text-start text-black font-semibold min-w-[10rem]">

                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($orders as $index => $row)
                            <tr class="bg-white hover:bg-gray-50">
                                <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                                    {{ $row['itemID'] }}
                                </td>
                                <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                                    {{ $row['itemName'] }}
                                </td>
                                <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                                    {{ $row['modifiedAt'] }}
                                </td>
                                <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                                    {{ $row['cost'] }}
                                </td>
                                <td class="p-3.5 text-sm text-gray-700 dark:text-gray-400">
                                    <a href="#" wire:click="removeOrder({{ $index }})">
                                        <i class="mgc_delete_2_fill text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mb-8" x-show="$wire.needTopUp == true">
                    <div class="bg-red-500 text-sm text-white rounded-md p-4" role="alert">
                        You should top up your credit balance or remove items from cart.
                    </div>
                </div>

                <div class="text-right mb-6">
                    <span class="bg-[#555555] py-4 px-6 text-white">
                        Total {{ $credit }} Credits
                    </span>
                    <span class="bg-primary py-4 px-6 text-white">
                        Available: {{ $creditBalance }} Credits
                    </span>
                </div>

                <div class="text-right" x-show="!$wire.needTopUp">
                    <button wire:click="checkoutOrder" class="btn bg-success text-white mb-4">
                        Checkout Order
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
