{{--
    This is a standalone print page — NO sidebar or navbar.
    We use a clean print-friendly layout.
    window.print() is called automatically on load.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Business Permit — {{ $application->application_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
        body { font-family: 'Times New Roman', serif; }
    </style>
</head>
<body class="bg-gray-200 min-h-screen p-8">

    {{-- Print & Back Buttons (hidden when printing) --}}
    <div class="no-print flex gap-3 mb-6 justify-center">
        <button onclick="window.print()"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold
                       hover:bg-blue-700 transition-colors">
            <i class="bi bi-printer mr-2"></i>Print Permit
        </button>
        <button onclick="window.history.back()"
                class="px-6 py-2 bg-slate-600 text-white rounded-lg font-semibold
                       hover:bg-slate-700 transition-colors">
            ← Go Back
        </button>
    </div>

    {{-- PERMIT DOCUMENT --}}
    {{-- A4-like white card centered on screen --}}
    <div class="bg-white max-w-3xl mx-auto shadow-2xl rounded-lg overflow-hidden"
         style="min-height: 1050px;">

        {{-- Header Banner --}}
        <div class="bg-slate-800 text-white text-center py-6 px-8">
            <div class="flex items-center justify-center gap-4 mb-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo"
                     class="h-16 w-16 object-contain"
                     onerror="this.style.display='none'" />
                <div>
                    <p class="text-xs tracking-widest uppercase text-slate-300">Republic of the Philippines</p>
                    <h1 class="text-xl font-bold">Municipal Government</h1>
                    <p class="text-sm text-slate-300">Business Permit and Licensing Office</p>
                </div>
            </div>
        </div>

        {{-- MAYOR'S PERMIT Title --}}
        <div class="text-center py-6 border-b-2 border-slate-800">
            <h2 class="text-3xl font-bold text-slate-900 tracking-wide uppercase">
                Mayor's Business Permit
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                This certifies that the business described below is permitted to operate.
            </p>
        </div>

        {{-- Permit Body --}}
        <div class="p-8 space-y-6">

            {{-- Application Details Row --}}
            <div class="grid grid-cols-3 gap-4 bg-slate-50 rounded-lg p-4 text-sm">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Permit No.</p>
                    <p class="font-bold text-slate-900 mt-1">{{ $application->application_number }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Date Issued</p>
                    <p class="font-bold text-slate-900 mt-1">
                        {{ $application->permit_issued_at?->format('F d, Y') ?? date('F d, Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Valid Until</p>
                    <p class="font-bold text-slate-900 mt-1">
                        {{ $application->permit_valid_until?->format('F d, Y') ?? 'December 31, ' . date('Y') }}
                    </p>
                </div>
            </div>

            {{-- Business Information --}}
            <div class="space-y-4">

                {{-- Business Name (large, prominent) --}}
                <div class="text-center border-2 border-slate-800 rounded-lg p-4">
                    <p class="text-xs text-slate-500 uppercase tracking-widest mb-1">Business Name</p>
                    <h3 class="text-3xl font-bold text-slate-900">
                        {{ strtoupper($application->business_name) }}
                    </h3>
                    @if($application->trade_name)
                        <p class="text-sm text-slate-500 mt-1">
                            (Trade Name: {{ $application->trade_name }})
                        </p>
                    @endif
                </div>

                {{-- Two Column Details --}}
                <div class="grid grid-cols-2 gap-4 text-sm">

                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                                Business Owner / Representative
                            </p>
                            <p class="text-slate-900 font-medium mt-0.5">
                                @if($application->business_info === 'Sole Proprietorship')
                                    {{ $application->sp_owner_lname }},
                                    {{ $application->sp_owner_fname }}
                                    {{ $application->sp_owner_mname }}
                                @else
                                    {{ $application->corp_owner_lname }},
                                    {{ $application->corp_owner_fname }}
                                    {{ $application->corp_owner_mname }}
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                                Business Type
                            </p>
                            <p class="text-slate-900 font-medium mt-0.5">
                                {{ $application->business_info }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                                Business Activity
                            </p>
                            <p class="text-slate-900 font-medium mt-0.5">
                                {{ $application->business_act }}
                                @if($application->business_act === 'Others' && $application->business_act_other)
                                    — {{ $application->business_act_other }}
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                                DTI / SEC / CDA Registration No.
                            </p>
                            <p class="text-slate-900 font-medium mt-0.5">{{ $application->reg_num }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                                Tax Identification Number (TIN)
                            </p>
                            <p class="text-slate-900 font-medium mt-0.5">{{ $application->business_tin }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                                Business Address
                            </p>
                            <p class="text-slate-900 font-medium mt-0.5 leading-relaxed">
                                @if($application->house_num) {{ $application->house_num }}, @endif
                                @if($application->building_name) {{ $application->building_name }}, @endif
                                @if($application->street) {{ $application->street }}, @endif
                                @if($application->barangay) Brgy. {{ $application->barangay }}, @endif
                                @if($application->city_muni) {{ $application->city_muni }}, @endif
                                @if($application->province) {{ $application->province }} @endif
                                @if($application->zip_code) {{ $application->zip_code }} @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                                Contact Number
                            </p>
                            <p class="text-slate-900 font-medium mt-0.5">
                                {{ $application->phone_number }}
                                @if($application->telephone_num)
                                    / {{ $application->telephone_num }}
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                                Email Address
                            </p>
                            <p class="text-slate-900 font-medium mt-0.5">{{ $application->business_email }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                                Billing Frequency
                            </p>
                            <p class="text-slate-900 font-medium mt-0.5">{{ $application->billing_freq }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                                Permit Fee Paid
                            </p>
                            <p class="text-slate-900 font-bold mt-0.5">
                                ₱{{ number_format($application->permit_fee, 2) }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Conditions / Note --}}
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-xs text-yellow-800">
                <p class="font-semibold mb-1">⚠ Important Conditions:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>This permit is valid only for the period and business activity stated above.</li>
                    <li>This permit must be posted in a conspicuous place at the business establishment.</li>
                    <li>Violation of any ordinance shall be grounds for revocation of this permit.</li>
                    <li>This permit is non-transferable.</li>
                </ul>
            </div>

            {{-- Signature Section --}}
            {{-- <div class="grid grid-cols-2 gap-12 mt-8 pt-6 border-t-2 border-slate-200"> --}}
                {{-- <div class="text-center">
                    <div class="h-16 border-b-2 border-slate-800 mb-2"></div>
                    <p class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                        Authorized Signatory
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Business Permit and Licensing Officer
                    </p>
                </div> --}}
                <div class="text-center">
                    {{-- <div class="h-16 border-b-2 border-slate-800 mb-2"> --}}
                        {{-- QR code placeholder --}}
                        {{-- <div class="w-14 h-14 mx-auto bg-slate-800 rounded flex items-center
                                    justify-center text-white text-[8px] text-center">
                            QR<br>CODE
                        </div>
                    </div> --}}
                    <p class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                        Verification Code
                    </p>
                    <p class="text-xs text-slate-500 font-mono mt-0.5">
                        {{ strtoupper(substr(md5($application->application_number), 0, 12)) }}
                    </p>
                </div>
            {{-- </div> --}}

        </div>

        {{-- Footer --}}
        <div class="bg-slate-800 text-white text-center py-3 px-8 text-xs text-slate-400">
            <p>This is an official document of the Municipal Business Permit and Licensing Office.</p>
            <p class="mt-0.5">Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        </div>

    </div>

    {{-- Auto-trigger print dialog when page loads --}}
    {{-- <script>
        window.addEventListener('load', function () {
            // Small delay so the page renders first before print dialog opens
            setTimeout(() => window.print(), 500);
        });
    </script> --}}

</body>
</html>