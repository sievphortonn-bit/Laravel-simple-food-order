@extends('frontend.layouts.app')

@section('content')

@php
    $bakongData = $bakong ? $bakong->toArray() : null;
@endphp

<style>
    .checkout-card {
        border-radius: 12px;
        padding: 25px;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    .checkout-title {
        font-weight: 700;
        border-left: 5px solid #28a745;
        padding-left: 10px;
    }
</style>

<h3 class="checkout-title mb-4">Checkout</h3>

<div class="row">

    <!-- LEFT -->
    <div class="col-md-7 mb-4">
        <div class="checkout-card">

            <h5 class="fw-bold mb-3">Customer Information</h5>

            <form id="checkout_form" action="{{ route('user.checkout.process') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input type="text" id="customer_name" name="customer_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" id="customer_email" name="customer_email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="text" id="customer_phone" name="customer_phone" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Shipping Address</label>
                    <textarea id="shipping_address" name="shipping_address" class="form-control" rows="3" required></textarea>
                </div>

                <h5 class="fw-bold mt-4 mb-3">Payment Method</h5>

                <select name="payment_method" id="payment_method" class="form-select" required>
                    <option value="cash">💵 Cash on Delivery</option>
                    <option value="khqr">📱 KHQR</option>
                </select>

                <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                <input type="hidden" name="tax" value="{{ $tax }}">
                <input type="hidden" name="shipping_fee" value="{{ $shippingFee }}">
                <input type="hidden" name="total" value="{{ $total }}">

                <button id="confirm_order_btn" class="btn btn-success btn-lg w-100 mt-3">
                    Confirm Order
                </button>

            </form>

            <!-- KHQR Modal -->
            <div class="modal fade" id="khqrModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Scan to Pay (KHQR)</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body text-center">

                            <p class="text-muted mb-2">Please scan the KHQR code below.</p>

                            <img id="khqr_image" class="border rounded shadow mb-3" width="250">

                            <div class="alert alert-info p-2">
                                Transaction ID: <strong id="tran_id"></strong>
                            </div>

                            <button id="confirm_payment_btn" class="btn btn-primary w-100">
                                ✅ I Have Paid — Confirm Payment
                            </button>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- RIGHT -->
    <div class="col-md-5">
        <div class="checkout-card">

            <h5 class="fw-bold mb-3">Order Summary</h5>

            <table class="table">
                <tbody>
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-end">$ {{ number_format($subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Tax</td>
                        <td class="text-end">$ {{ number_format($tax, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Shipping Fee</td>
                        <td class="text-end">$ {{ number_format($shippingFee, 2) }}</td>
                    </tr>
                    <tr class="table-success">
                        <th>Total</th>
                        <th class="text-end">$ {{ number_format($total, 2) }}</th>
                    </tr>
                </tbody>
            </table>

            <hr>

            <h6 class="fw-bold">Items</h6>
            <ul class="list-group">
                @foreach($cart as $item)
                    <li class="list-group-item d-flex justify-content-between">
                        {{ $item->food->name }}
                        <span>x {{ $item->quantity }}</span>
                    </li>
                @endforeach
            </ul>

        </div>
    </div>

</div>



@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const paymentSelect = document.getElementById('payment_method');
    const khqrModalEl   = document.getElementById('khqrModal');
    const khqrImage     = document.getElementById('khqr_image');
    const tranIdEl      = document.getElementById('tran_id');
    const confirmBtn    = document.getElementById('confirm_payment_btn');

    const totalAmount = Number("{{ $total}}"); // Convert USD → KHR

    if (!paymentSelect || !khqrModalEl) return;

    // -----------------------
    // Validate customer info
    // -----------------------
    function validateCustomer() {
        const fields = [
            'customer_name',
            'customer_email',
            'customer_phone',
            'shipping_address'
        ];

        for (let id of fields) {
            const el = document.getElementById(id);
            if (!el || !el.value.trim()) {
                alert("⚠ Please fill all customer information before using KHQR.");
                return false;
            }
        }

        return true;
    }

    // -----------------------
    // Generate KHQR
    // -----------------------
    function generateKHQR() {
        try {
            if (!window.BakongKHQR) {
                console.error("BakongKHQR library missing.");
                return null;
            }

            const { BakongKHQR, khqrData, IndividualInfo } = window.BakongKHQR;

            const optionalData = {
                currency: khqrData.currency.usd,
                transactionCurrency: khqrData.currency.khr,
                amount: totalAmount,
                countryCode: "KH",
                merchantType: "29"
            };

            const info = new IndividualInfo(
                "sievphor_tonn@aclb",
                "Sievphor Tonn",
                "Phnom Penh",
                optionalData
            );

            const generator = new BakongKHQR();
            const qrResponse = generator.generateIndividual(info);

            console.log("Generated KHQR:", qrResponse);

            if (!qrResponse || !qrResponse.data || !qrResponse.data.qr) {
                console.error("Invalid KHQR response:", qrResponse);
                return null;
            }

            // Convert KHQR string → QR picture
            khqrImage.src =
                "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" +
                encodeURIComponent(qrResponse.data.qr);

            tranIdEl.innerText = qrResponse.data.md5;
            return qrResponse.data;
        } catch (err) {
            console.error("KHQR generation error:", err);
            return null;
        }
    }

    // -----------------------
    // Show modal
    // -----------------------
    function showModal() {
        try {
            const modal = new bootstrap.Modal(khqrModalEl);
            modal.show();
        } catch (err) {
            console.error("Modal error:", err);
        }
    }

    // -----------------------
    // Payment method change
    // -----------------------
    paymentSelect.addEventListener('change', function () {

        if (this.value !== 'khqr') return;

        if (!validateCustomer()) {
            this.value = 'cash';
            return;
        }

        const qr = generateKHQR();

        if (!qr) {
            alert("❌ Failed to generate KHQR. Check console.");
            this.value = 'cash';
            return;
        }

        showModal();
    });
    async function checkTransaction(md5) {
        const url = "https://api-bakong.nbc.gov.kh/v1/check_transaction_by_md5";
        const email = "tonnsievphor@gmail.com";
        let retries = 0;

        while (retries < 5) {
            try {
                console.log(`Attempt ${retries + 1} to check transaction.`);

                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Authorization": currentToken,
                    },
                    body: JSON.stringify({ md5 }),
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.responseCode === 0) {
                        // Livewire.emit('transactionsEmit');
                        alert("Payment was successful!");
                        break;
                    } else {
                        console.log("Transaction not completed:", data.responseMessage);
                    }
                } else if (response.status === 401) {
                    console.log("Token expired. Attempting renewal...");
                    await renewToken(email);
                } else {
                    console.error("Failed to check transaction. Status:", response.status, response.statusText);
                }
            } catch (error) {
                console.error("Error during transaction check:", error);
            }

            retries++;
            await new Promise(resolve => setTimeout(resolve, Math.pow(2, retries) * 1000)); // Exponential backoff
        }
    }
    // -----------------------
    // Confirm payment
    // -----------------------
    if (confirmBtn) {
        confirmBtn.addEventListener('click', async function () {
            this.disabled = true;
            this.innerText = "Verifying...";

            const md5 = tranIdEl.innerText.trim();
            if (!md5) {
                alert("Transaction ID not found.");
                this.disabled = false;
                this.innerText = "✅ I Have Paid — Confirm Payment";
                return;
            }

            try {
                const resp = await fetch("https://api-bakong.nbc.gov.kh/v1/check_transaction_by_md5", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Authorization": "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhIjp7ImlkIjoiZWRhMTBmZWY2Y2VmNDhhYyJ9LCJpYXQiOjE3NjQzMzg4MzgsImV4cCI6MTc3MjExNDgzOH0.z42q_OJsReeYlPbmp8_TO_Lpl6kEfs3nwksViv_9_80"
                    },
                    body: JSON.stringify({ md5 })
                });

                const data = await resp.json();
                console.log("Verify response:", data);

                if (data.responseCode === 0) {
                    alert("✔ Payment verified successfully!");
                    document.getElementById('checkout_form').submit();
                } else {
                    alert("Payment not found yet. Try again in 5–10 sec.");
                    this.disabled = false;
                    this.innerText = "✅ I Have Paid — Confirm Payment";
                }

            } catch (err) {
                console.error(err);
                alert("Verification error. Check console.");
                this.disabled = false;
                this.innerText = "✅ I Have Paid — Confirm Payment";
            }
        });
    }

});
</script>
@endpush
