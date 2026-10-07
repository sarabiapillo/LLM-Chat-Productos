<x-app-layout>
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="font-outfit text-2xl font-bold text-white flex items-center space-x-2">
                <i class="fa-solid fa-robot text-indigo-400"></i>
                <span>Asistente de Inteligencia Artificial LLM</span>
            </h1>
            <p class="text-slate-400 text-sm mt-1">Interactúa con modelos de lenguaje de última generación integrados en la plataforma</p>
        </div>

        <div class="flex items-center space-x-2">
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                <i class="fa-solid fa-circle text-[8px] mr-1 animate-pulse"></i> Modelos En Línea
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- Panel Izquierdo: Selección de Modelo y Parámetros -->
        <div class="space-y-4">
            <div class="glass-card p-5">
                <h3 class="font-outfit text-sm font-bold text-white uppercase tracking-wider mb-3 flex items-center space-x-2">
                    <i class="fa-solid fa-sliders text-indigo-400"></i>
                    <span>Modelo LLM Activo</span>
                </h3>

                <div class="space-y-2" id="model-selector-container">
                    @foreach($models as $index => $model)
                        <label class="block p-3 rounded-xl bg-slate-900/80 border border-slate-800 hover:border-indigo-500/50 cursor-pointer transition-all">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <input type="radio" name="model_choice" value="{{ $model['id'] }}" {{ $index === 0 ? 'checked' : '' }} onchange="selectModel('{{ $model['id'] }}', '{{ $model['name'] }}')" class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-xs font-semibold text-white">{{ $model['name'] }}</span>
                                </div>
                            </div>
                            <span class="text-[10px] text-indigo-300 bg-indigo-950/60 border border-indigo-800/60 px-2 py-0.5 rounded mt-2 inline-block">
                                {{ $model['badge'] }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Plantillas de Prompts Rápidos -->
            <div class="glass-card p-5">
                <h3 class="font-outfit text-sm font-bold text-white uppercase tracking-wider mb-3 flex items-center space-x-2">
                    <i class="fa-solid fa-lightbulb text-amber-400"></i>
                    <span>Prompts Rápidos</span>
                </h3>
                <div class="space-y-2">
                    <button onclick="insertPrompt('Genera una estrategia de ventas para clientes corporativos de desarrollo de software.')" 
                            class="w-full text-left p-2.5 rounded-lg bg-slate-900/60 hover:bg-slate-800 border border-slate-800 text-xs text-slate-300 hover:text-indigo-300 transition-colors">
                        📊 Estrategia de Ventas
                    </button>
                    <button onclick="insertPrompt('Explica las ventajas de la arquitectura Laravel Blade y roles de usuario.')" 
                            class="w-full text-left p-2.5 rounded-lg bg-slate-900/60 hover:bg-slate-800 border border-slate-800 text-xs text-slate-300 hover:text-indigo-300 transition-colors">
                        ⚙️ Ventajas Laravel Blade
                    </button>
                    <button onclick="insertPrompt('Redacta una respuesta de soporte técnico para una consulta de API.')" 
                            class="w-full text-left p-2.5 rounded-lg bg-slate-900/60 hover:bg-slate-800 border border-slate-800 text-xs text-slate-300 hover:text-indigo-300 transition-colors">
                        ✉️ Respuesta de Soporte
                    </button>
                </div>
            </div>
        </div>

        <!-- Panel Derecho: Interfaz del Chat de Conversación -->
        <div class="lg:col-span-3 flex flex-col h-[600px] glass-card overflow-hidden">
            
            <!-- Encabezado del Chat -->
            <div class="px-6 py-4 bg-slate-900/80 border-b border-slate-800 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <div>
                        <span class="text-sm font-semibold text-white block" id="current-model-display">Gemini 1.5 Pro (Google)</span>
                        <span class="text-xs text-slate-400">Sesión interactiva en tiempo real</span>
                    </div>
                </div>
                <button onclick="clearChat()" class="text-xs text-slate-400 hover:text-rose-400 transition-colors">
                    <i class="fa-solid fa-trash-can mr-1"></i> Limpiar Chat
                </button>
            </div>

            <!-- Flujo de Mensajes -->
            <div id="chat-messages" class="flex-grow p-6 overflow-y-auto space-y-4 font-sans text-sm">
                <!-- Mensaje de bienvenida inicial -->
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white flex-shrink-0 shadow-md">
                        <i class="fa-solid fa-robot text-sm"></i>
                    </div>
                    <div class="bg-slate-900/80 border border-slate-800 p-4 rounded-2xl max-w-2xl text-slate-200">
                        <p class="font-semibold text-indigo-400 mb-1">Asistente LLM Chat</p>
                        <p>¡Hola <strong>{{ Auth::user()->name }}</strong>! ¿En qué puedo ayudarte hoy? Selecciona tu modelo favorito y escribe tu consulta.</p>
                    </div>
                </div>
            </div>

            <!-- Área de Entrada de Mensaje -->
            <div class="p-4 bg-slate-900/90 border-t border-slate-800">
                <form id="chat-form" onsubmit="handleSend(event)" class="relative">
                    @csrf
                    <input type="hidden" id="selected-model" name="model" value="gemini-1.5-pro">

                    <textarea id="prompt-input" name="prompt" rows="2" required placeholder="Escribe tu mensaje o consulta al modelo LLM aquí..."
                              class="w-full pl-4 pr-12 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none text-sm"></textarea>

                    <button type="submit" id="btn-send"
                            class="absolute right-3 bottom-4 p-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-lg shadow-md transition-transform active:scale-95">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>

        </div>

    </div>

    <script>
        function selectModel(id, name) {
            document.getElementById('selected-model').value = id;
            document.getElementById('current-model-display').textContent = name;
        }

        function insertPrompt(text) {
            document.getElementById('prompt-input').value = text;
            document.getElementById('prompt-input').focus();
        }

        function clearChat() {
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.innerHTML = `
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white flex-shrink-0">
                        <i class="fa-solid fa-robot text-sm"></i>
                    </div>
                    <div class="bg-slate-900/80 border border-slate-800 p-4 rounded-2xl max-w-2xl text-slate-200">
                        <p class="font-semibold text-indigo-400 mb-1">Asistente LLM Chat</p>
                        <p>Chat reiniciado. ¿Qué deseas consultar ahora?</p>
                    </div>
                </div>
            `;
        }

        async function handleSend(e) {
            e.preventDefault();
            const promptInput = document.getElementById('prompt-input');
            const prompt = promptInput.value.trim();
            if (!prompt) return;

            const model = document.getElementById('selected-model').value;
            const chatMessages = document.getElementById('chat-messages');

            // Renderizar mensaje del usuario
            const userMsgHtml = `
                <div class="flex items-start space-x-3 justify-end">
                    <div class="bg-indigo-600/20 border border-indigo-500/40 p-4 rounded-2xl max-w-2xl text-indigo-100">
                        <p class="font-semibold text-indigo-300 text-xs mb-1">Tú</p>
                        <p>${escapeHtml(prompt)}</p>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-indigo-800 flex items-center justify-center text-white flex-shrink-0">
                        <i class="fa-solid fa-user text-xs"></i>
                    </div>
                </div>
            `;
            chatMessages.insertAdjacentHTML('beforeend', userMsgHtml);
            promptInput.value = '';
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Indicador de "Generando..."
            const loadingId = 'loading-' + Date.now();
            const loadingHtml = `
                <div id="${loadingId}" class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white flex-shrink-0 animate-pulse">
                        <i class="fa-solid fa-robot text-sm"></i>
                    </div>
                    <div class="bg-slate-900/80 border border-slate-800 p-4 rounded-2xl text-slate-400 text-xs italic">
                        <i class="fa-solid fa-spinner fa-spin mr-2"></i> Generando respuesta con ${model}...
                    </div>
                </div>
            `;
            chatMessages.insertAdjacentHTML('beforeend', loadingHtml);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            try {
                const response = await fetch("{{ route('chat.send') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ model: model, prompt: prompt })
                });

                const data = await response.json();
                document.getElementById(loadingId).remove();

                const botReplyHtml = `
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white flex-shrink-0 shadow-md">
                            <i class="fa-solid fa-robot text-sm"></i>
                        </div>
                        <div class="bg-slate-900/90 border border-slate-800 p-4 rounded-2xl max-w-2xl text-slate-200">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-semibold text-indigo-400 text-xs">${model}</span>
                                <span class="text-[10px] text-slate-500">${data.timestamp || ''}</span>
                            </div>
                            <div class="prose prose-invert text-sm whitespace-pre-line">${escapeHtml(data.response)}</div>
                        </div>
                    </div>
                `;
                chatMessages.insertAdjacentHTML('beforeend', botReplyHtml);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            } catch (err) {
                document.getElementById(loadingId).remove();
                alert('Error al conectar con la API LLM.');
            }
        }

        function escapeHtml(text) {
            return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
    </script>
</x-app-layout>
