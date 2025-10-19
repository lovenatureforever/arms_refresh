<?php

namespace App\Livewire\Tenant;

class OrderService
{
    public function addToOrder($serviceType, $serviceName, $serviceCost, $data)
    {
        $orders = session()->get('orders', []);
        $orders[] = [
            "itemID" => $this->generateOrderNumber(), // unique id for each item for each company
            "itemType" => $serviceType,
            "itemName" => $serviceName,
            "modifiedAt" => now()->format('Y-m-d'),
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

    private function generateOrderNumber()
    {
        $dateTime = date('YmdHis');
        $random = str_pad(mt_rand(0, 99), 2, '0', STR_PAD_LEFT);
        return $dateTime . $random;
    }
}
