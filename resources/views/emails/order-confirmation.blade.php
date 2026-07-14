@component('mail::message')
# ¡Gracias por tu compra, {{ $order->user->name }}! 🎉

Hemos recibido tu pedido **#{{ $order->id }}** y está siendo procesado.

---

### 📦 Detalles del pedido:

@component('mail::table')
| Producto        | Cantidad | Precio     | Subtotal   |
|----------------|----------|------------|------------|
@foreach ($order->orderItems as $item)
| {{ $item->product->name ?? 'Costo de Envío' }} | {{ $item->quantity }} | ${{ number_format($item->price, 2) }} | ${{ number_format($item->subtotal, 2) }} |
@endforeach
@endcomponent

---

### 🏠 Dirección de envío:
- {{ $order->shippingAddress->address }}
- {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}
- {{ $order->shippingAddress->postal_code }}, {{ $order->shippingAddress->country }}

---

### 💰 Total pagado:
**${{ number_format($order->total_price, 2) }} USD**

---

@component('mail::button', ['url' => route('home')])
Volver a la tienda
@endcomponent

¿Tienes dudas? Escríbenos a: {{ $support_email }}

Gracias por comprar con nosotros,<br>
**{{ config('app.name') }}**
@endcomponent
