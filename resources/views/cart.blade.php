<table>
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Remove from cart</th>
    </tr>
    @foreach($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
            <td>unit price times quantity</td>
            <td><a href="">get a button from bootstrap icons</a></td>
        </tr>
    @endforeach
</table>
<div>
    <h3>Cart Summary</h3>
    Subtotal
    Shipping fee
    Total
    prooceed to checkout(get a button from bootstrap icons)
    @if($user)
    <!--a user should be able to change their delivery info so a prompt should be made whther they want to keep or change their delivery information-->
        <x-primary-button>
            <a href="">Checkout</a>  
        </x-primary-button>
    @endif
</div>