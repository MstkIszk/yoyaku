<tr class="hover:bg-gray-50">
    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
        <input type="hidden" name="prices[{{ $index }}][id]" value="{{ $price->id ?? '' }}">
        {{ $price->id ?? '-' }}
    </td>
    <td class="px-3 py-2 whitespace-nowrap">
        <input type="number" name="prices[{{ $index }}][courseCode]" value="{{ $price->courseCode ?? 0 }}" 
               class="form-input block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="表示順">
    </td>
    <td class="px-3 py-2 whitespace-nowrap">
        <input type="number" name="prices[{{ $index }}][priceCode]" value="{{ $price->priceCode ?? 0 }}" 
               class="form-input block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="表示順">
    </td>
    <td class="px-6 py-2 whitespace-nowrap">
        <input type="text" name="prices[{{ $index }}][courseName]" value="{{ $price->courseName ?? '' }}" required
               class="form-input block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="料金名 (必須)">
    </td>
    <td class="px-6 py-2 whitespace-nowrap">
        <input type="number" name="prices[{{ $index }}][weekdayPrice]" value="{{ $price->weekdayPrice ?? 0 }}"
               class="form-input block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="0">
    </td>
    <td class="px-6 py-2 whitespace-nowrap">
        <input type="number" name="prices[{{ $index }}][weekendPrice]" value="{{ $price->weekendPrice ?? 0 }}"
               class="form-input block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="0">
    </td>
    <td class="px-6 py-2 whitespace-nowrap text-center">
        <input type="checkbox" name="prices[{{ $index }}][IsEnabled]" value="1" 
               {{ ($price->IsEnabled ?? 1) == 1 ? 'checked' : '' }} 
               class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
    </td>
    <td class="px-6 py-2 whitespace-nowrap">
        <input type="text" name="prices[{{ $index }}][memo]" value="{{ $price->memo ?? '' }}"
               class="form-input block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="備考">
    </td>
    <td class="px-3 py-2 whitespace-nowrap text-center">
        <button type="button" class="remove-row text-red-600 hover:text-red-900 text-lg">&times;</button>
    </td>
</tr>
