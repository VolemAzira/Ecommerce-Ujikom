<!-- add_to_cart_modal.php -->
<div id="addToCartModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-5 rounded-lg shadow-lg max-w-sm w-full">
        <div class="flex flex-col gap-3">
            <!-- Product Name -->
            <h2 id="modalProductName" class="text-xl font-bold"></h2>
            
            <!-- Product Image -->
            <img id="modalProductImage" class="w-40 h-40 object-cover mx-auto" alt="Product Image" />

            <!-- Product Price -->
            <p id="modalProductPriceDisplay" class="text-xl text-green-500 font-bold"></p>

            <!-- Quantity Selector -->
            <div class="flex items-center justify-between">
                <button id="decreaseQty" class="text-xl font-bold bg-gray-200 p-2 rounded">-</button>
                <span id="qtyDisplay" class="text-xl">1</span>
                <button id="increaseQty" class="text-xl font-bold bg-gray-200 p-2 rounded">+</button>
            </div>

            <!-- Add to Cart Button -->
            <form method="POST" action="add_cart.php" class="mt-3">
                <input type="hidden" name="product_id" id="modalProductId">
                <input type="hidden" name="product_name" id="modalProductHiddenName">
                <input type="hidden" name="product_price" id="modalProductPrice">
                <input type="hidden" name="product_qty" id="modalProductQty" value="1">
                <button type="submit" name="add_to_cart" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add to Cart</button>
            </form>

            <!-- Close Button -->
            <button id="closeModal" class="text-red-500 hover:text-red-700 font-bold mt-2">Close</button>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('addToCartModal');
    const productName = document.getElementById('modalProductName');
    const productImage = document.getElementById('modalProductImage');
    const productPriceDisplay = document.getElementById('modalProductPriceDisplay');
    const productId = document.getElementById('modalProductId');
    const productHiddenName = document.getElementById('modalProductHiddenName');
    const productPrice = document.getElementById('modalProductPrice');
    const productQty = document.getElementById('modalProductQty');
    const qtyDisplay = document.getElementById('qtyDisplay');

    const closeModal = document.getElementById('closeModal');
    const decreaseQty = document.getElementById('decreaseQty');
    const increaseQty = document.getElementById('increaseQty');

    let qty = 1;

    function openModal(id, name, price, image) {
        productName.textContent = name;
        productImage.src = image;
        productPriceDisplay.textContent = 'Rp. ' + price;
        productId.value = id;
        productHiddenName.value = name;
        productPrice.value = price;
        qty = 1;
        qtyDisplay.textContent = qty;
        productQty.value = qty;

        modal.classList.remove('hidden');
    }

    closeModal.addEventListener('click', function() {
        modal.classList.add('hidden');
    });

    increaseQty.addEventListener('click', function() {
        qty++;
        qtyDisplay.textContent = qty;
        productQty.value = qty;
    });

    decreaseQty.addEventListener('click', function() {
        if (qty > 1) {
            qty--;
            qtyDisplay.textContent = qty;
            productQty.value = qty;
        }
    });
</script>
