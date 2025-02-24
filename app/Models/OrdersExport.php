<?php

namespace App\Models;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithMapping, WithHeadings
{
    protected $orders;

    // Constructor to accept the filtered orders
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    // Return the collection of filtered orders
    public function collection()
    {
        return $this->orders;
    }

    // Define the headings for the Excel file
    public function headings(): array
    {
        return [
            'Order ID',
            'Customer Name',
            'Customer Phone',
            'Seller Name',
            'Seller Email',
            'Courier Name',
            'Courier Email',
            'Shipping Address',
            'Courier Shipping Cost',
            'Shipping Type',
            'Delivery Status',
            'Payment Status',
            'Grand Total',
            'Order Code',
            'Tracking Code',
        ];
    }

    // Map the order data to match the headings
    public function map($order): array
    {
        $customer = User::find($order->user_id);
        $seller   = User::find($order->seller_id);
        $courier  = $order->courier_id ? User::find($order->courier_id) : null;

        // Decode and format the shipping address
        $shipping_address = json_decode($order->shipping_address, true);
        $formatted_address = implode("\n", array_filter([
            'Customer Name: ' . ($shipping_address['name'] ?? ''),
            'Address: ' . ($shipping_address['address'] ?? ''),
            'City: ' . ($shipping_address['city'] ?? ''),
            'State: ' . ($shipping_address['state'] ?? ''),
            'Postal Code: ' . ($shipping_address['postal_code'] ?? ''),
            'Country: ' . ($shipping_address['country'] ?? ''),
            'Phone: ' . ($shipping_address['phone'] ?? ''),
        ]));

        return [
            $order->code,
            optional($customer)->name,  // Customer Name
            optional($customer)->phone, // Customer Phone
            optional($seller)->name,    // Seller Name
            optional($seller)->email,   // Seller Email
            optional($courier)->name,   // Courier Name
            optional($courier)->email,  // Courier Email
            $formatted_address,         // Shipping Address
            $order->courier_shipping_cost,  // Courier Shipping Cost
            $order->shipping_type,      // Shipping Type
            $order->delivery_status,    // Delivery Status
            $order->payment_status,     // Payment Status
            $order->grand_total,        // Grand Total
            $order->code,               // Order Code
            $order->tracking_code,      // Tracking Code
        ];
    }
}
