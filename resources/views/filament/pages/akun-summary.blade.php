<x-filament-panels::page>
    <div class="space-y-6">

        <h2 class="text-xl font-bold">
            Ringkasan Saldo Akun per Jenis
        </h2>

        <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-xl shadow">
            <table class="w-full border-collapse">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">
                            Jenis Akun
                        </th>
                        <th class="px-4 py-3 text-right text-sm font-semibold">
                            Total Saldo
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($akunSummary as $row)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-2">
                                {{ $row->jenis }}
                            </td>
                            <td class="px-4 py-2 text-right font-semibold">
                                Rp {{ number_format($row->total_saldo, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-6 text-center text-gray-500">
                                Tidak ada data akun.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if($akunSummary->count())
                    <tfoot class="bg-gray-50 dark:bg-gray-800">
                        <tr class="font-bold">
                            <td class="px-4 py-3 text-right">
                                Total Semua Akun
                            </td>
                            <td class="px-4 py-3 text-right text-green-600">
                                Rp {{ number_format($akunSummary->sum('total_saldo'), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

    </div>
</x-filament-panels::page>
