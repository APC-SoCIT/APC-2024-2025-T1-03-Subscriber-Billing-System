<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <div class="flex flex-col items-center">
            <div class="w-16 h-16 bg-green-500 flex items-center justify-center rounded-full">
                <svg class="w-10 h-10 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 15.172l-3.536-3.536-1.414 1.414L10 18l9-9-1.414-1.414L10 15.172z" />
                </svg>
            </div>
            <h2 class="text-lg font-semibold mt-4">Payment Receipt</h2>
            <p class="text-sm text-gray-600">D&D IT Network and Data Solutions</p>
        </div>

        <div class="mt-4 border-t pt-4 text-sm mb-4">
            <p class="flex justify-between"><span class="font-semibold">Account ID:</span> <span>{{ $account_id }}</span></p>
            <p class="flex justify-between"><span class="font-semibold">Plan:</span> <span>{{ $plan->subscription }}</span></p>
            <p class="flex justify-between"><span class="font-semibold">Account Name:</span> <span>{{ $account_name }}</span></p>
            <p class="flex justify-between"><span class="font-semibold">Reference Number:</span> <span>{{ $reference_number }}</span></p>
            <p class="flex justify-between"><span class="font-semibold">Date:</span> <span>{{ $date }}</span></p>
            <p class="flex justify-between"><span class="font-semibold">Amount:</span> <span class="text-green-600 font-bold">{{ $amount }}</span></p>
        </div>
        
        <a href="{{ route('home') }}">
    
            <button class="btnContinue">Continue &gt;</button>
    
        </a>
    </div>
</body>

<style>
    .btnContinue{
        border: none;
        color: white;
        background-color: #0AB1C5;
        border-radius: 10px;
        padding: 5px 15px;
        width: 100%;
    }
</style>


</html>
