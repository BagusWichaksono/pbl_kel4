<x-filament-panels::page>
    <div class="fi-section rounded-2xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6" style="border: 1px solid rgba(83, 116, 172, 0.1);">
        
        <div class="mb-4 border-b border-gray-100 pb-4">
            <h2 class="text-xl font-bold" style="color: #141c33;">Live Chat Support</h2>
            <p class="text-sm text-gray-500 mt-1">Admin TesYuk! biasanya membalas dalam beberapa menit.</p>
        </div>

        <div class="h-96 bg-slate-50 border border-slate-100 rounded-xl p-4 mb-4 overflow-y-auto flex flex-col gap-4">
            
            @foreach($riwayatChat as $chat)
                @if($chat['pengirim'] === 'admin')
                    <div class="self-start flex gap-3 max-w-[80%]">
                        <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center text-white" style="background-color: #5374ac;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        </div>
                        <div class="bg-white border border-slate-200 text-slate-800 py-3 px-4 rounded-br-2xl rounded-tr-2xl rounded-tl-2xl shadow-sm text-sm">
                            {{ $chat['teks'] }}
                        </div>
                    </div>
                @else
                    <div class="self-end flex gap-3 max-w-[80%] flex-row-reverse">
                        <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center text-white bg-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        </div>
                        <div class="text-white py-3 px-4 rounded-bl-2xl rounded-tr-2xl rounded-tl-2xl shadow-sm text-sm" style="background-color: #141c33;">
                            {{ $chat['teks'] }}
                        </div>
                    </div>
                @endif
            @endforeach
            
        </div>

        <div class="flex gap-3">
            <input type="text" wire:model="pesanBaru" wire:keydown.enter="kirimPesan" placeholder="Ketik pesan keluhan atau pertanyaanmu..." class="flex-1 rounded-xl border-slate-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3">
            
            <button wire:click="kirimPesan" class="px-6 py-3 rounded-xl text-white font-semibold transition-all shadow-sm" style="background-color: #5374ac;" onmouseover="this.style.backgroundColor='#425d8a'" onmouseout="this.style.backgroundColor='#5374ac'">
                Kirim
            </button>
        </div>
        
    </div>
</x-filament-panels::page>