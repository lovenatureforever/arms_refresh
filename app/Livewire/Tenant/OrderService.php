<?php

namespace App\Livewire\Tenant;

class OrderService
{
    public function addToOrder($serviceType, $serviceName, $serviceCost, $data)
    {
        $orders = session()->get('orders', []);
        $orders[] = [
            "itemID" => "#009090", // unique id for each item for each company
            "itemType" => $serviceType,
            "itemName" => $serviceName,
            "modifiedAt" => date('Y-m-d'),
            "cost" => $serviceCost,
            "data" => $data
        ];
        session()->put('orders', $orders);
    }

    public function getOrders()
    {
        return session()->get('orders', []);
    }

    public function setOrders($orders) {
        return session()->put('orders', $orders);
    }
}
