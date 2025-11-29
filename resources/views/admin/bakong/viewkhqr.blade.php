@extends('admin.layouts.app')
@section('title', 'View Bakong KHQR')
@section('content')
<div>
    <nav>
        <ol class="breadcrumb">
            {{-- <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('admin_dash.home')</a></li> --}}
            <li class="breadcrumb-item">View Bakong KHQR</li>
        </ol>
    </nav>

    @if (session('message'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            toastr.success(@json(session('message')));
        });
    </script>
    @endif
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    
                    <div class="card-card">
                        <!-- Header -->
                        <div class="card-card-header">
                            <center>
                                <img src="{{asset('phoenix-assets/images/KHQR_available_here_-_logo_with_bg-removebg-preview.png')}}" class="" width="20%" alt="">
                            </center>
                           
                        </div>
                
                        <!-- Content -->
                        <div class="card-card-content">
                            <div class="name" style="font-family: 'Nunito Sans', sans-serif;">{{$bakong->merchantName}}</div>
                            <div class="" style="display: flex;gap: 4px;margin-top: -7px;font-family: 'Nunito Sans', sans-serif;">
                                <div class="amount">
                                    {{-- @if(isset($carttotal) && $carttotal->total_amount)
                                        <div class="border-dashed-y">
                                           
                                            <p wire:ignore class="mb-2">$</p>
                                        </div>
                                    @else
                                        <div class="border-dashed-y">
                                            
                                            <p wire:ignore class="mb-2">$</p>
                                        </div>
                                    @endif --}}0
                                </div>
                                <div class="currency" style="margin-top: 14px;">USD</div>
                            </div>
                           
                        </div>
                
                        <!-- Divider -->
                        <!-- <div class="divider"></div> -->
                        <div style=" border-top: 1.5px dashed #ccc;margin-top: -10px; height: 0;">
                            
                        </div>
                        
                
                        <!-- QR Code -->
                        <center>
                            <div class="qr-code" style="margin-top: 10px;">
                                <img src="{{asset('phoenix-assets/images/Dollar.png')}}" style="position: absolute;width:40px;height:40px;margin-top:90px;"alt="">
                                <img src="" id="qrgen" alt="QR Code for payment">
                            </div>
                        </center>
                        
                    </div>
                    
                    {{-- <img src="" id="qrgen" alt=""> --}}
                </div>
            </div>
        </div>
        <div class="col-md-6 ">
            <div class="card h-100">
                <div class="card-header border-bottom">
                    Bakong KHQR Information
                </div>
                <div class="card-body">
                    <table class="table card-table">
                        <tr>
                            <td>Merchant Type</td>
                            <td>{{$bakong->merchantType}}</td>
                        </tr>
                        <tr>
                            <td>Bakong Account ID</td>
                            <td>{{$bakong->bakongAccountID}}</td>
                        </tr>
                        <tr>
                            <td>Merchant Name</td>
                            <td>{{$bakong->merchantName}}</td>
                        </tr>
                        <tr>
                            <td>Merchant City</td>
                            <td>{{$bakong->merchantCity}}</td>
                        </tr>
                        <tr>
                            <td>Transaction Currency</td>
                            <td>{{$bakong->transactionCurrency}}</td>
                        </tr>
                        <tr>
                            <td>Country Code</td>
                            <td>{{$bakong->countryCode}}</td>
                        </tr>
                        <tr>
                            <td>CRC</td>
                            <td>{{$bakong->crc}}</td>
                        </tr>
                        
                    </table>
                    <br>
                    <center>
                        <div class="">
                            <a class="text-center btn btn-label-primary" href="{{url('admin/bakongkhqr')}}">Change Bakong KHQR</a>
                        </div>
                    </center>
                    
                </div>
            </div>
        </div>
    </div>
    
    
</div>
@endsection
@push('scripts')
<script>
    var {
        objData,
        BakongKHQR,
        khqrData,
        IndividualInfo,
        MerchantInfo,
        SourceInfo,
    } = BakongKHQR;
    const optionalData = {
        currency: khqrData.currency.khr,
        merchantType: "{{$bakong->merchantType}}",
        transactionCurrency: "{{$bakong->transactionCurrency}}",
        countryCode: "{{$bakong->countryCode}}",
        crc: "{{$bakong->crc}}",
    };
    const individualInfo = new IndividualInfo(
        "{{$bakong->bakongAccountID}}",
        "{{$bakong->merchantName}}",
        "{{$bakong->merchantCity}}",
        optionalData
    );
    var boolen = true;

    setTimeout(function () {
        boolen = false;
        console.log('Value changed to:', boolen);

        if (!boolen) {
            // Refresh the QR code generation when boolen becomes false
            // generateQRCode();
        }
    }, 20000);

    function generateQR() {
        function generateQRCode() {
            const newKhqr = new BakongKHQR();
            response = newKhqr.generateIndividual(individualInfo);
            qrcode = response.data.qr;
            console.log(response.data.md5);
            console.log(qrcode);
            console.log(response.status.data);

            document.getElementById("qrgen").src =
                "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" +
                qrcode;
        }

        if (boolen) {
            generateQRCode();

            async function checkTransaction(md5) {
                const url = "https://api-bakong.nbc.gov.kh/v1/check_transaction_by_md5";
                const token =
                    "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhIjp7ImlkIjoiZmNkNWI3N2NiN2JjNGMzYyJ9LCJpYXQiOjE3MjI5NTgyNzMsImV4cCI6MTczMDczNDI3M30.gVQOpfxXN6XOl-pSNOyIsmWNnFKXgadOjSzRdWzhzsY";

                while (boolen) { // Continue only if boolen is true
                    try {
                        const response = await fetch(url, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Authorization": token,
                            },
                            body: JSON.stringify({
                                md5
                            }),
                        });

                        const data = await response.json();

                        if (data.responseCode === 0) {
                            Livewire.dispatch('Success');
                            console.log(data);
                            console.log(data.data.fromAccountId);
                            break; // Exit the loop if responseCode is 0
                        } else {
                            console.log("Not successful, retrying...");
                        }
                    } catch (error) {
                        console.error("Error:", error);
                    }

                    await new Promise((resolve) => setTimeout(resolve, 5000)); // Wait 5 seconds before retrying
                }

                console.log("Stopping the request loop as boolen is now false.");
            }

            // Example usage
            checkTransaction(response.data.md5);
        }
    }
    generateQR()
</script>
@endpush