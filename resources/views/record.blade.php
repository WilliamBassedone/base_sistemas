<!doctype html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gravador de Áudio</title>
        <style>
            :root {
                color-scheme: light;
            }
            body {
                margin: 0;
                font-family: "Georgia", "Times New Roman", serif;
                background: linear-gradient(160deg, #f7f2e8 0%, #f1e6d4 100%);
                color: #2c241b;
            }
            main {
                max-width: 720px;
                margin: 60px auto;
                padding: 24px;
                background: #fff7ea;
                border: 2px solid #d5c3a1;
                box-shadow: 8px 8px 0 #b89c6c;
            }
            h1 {
                margin-top: 0;
                font-size: 28px;
            }
            .controls {
                display: flex;
                gap: 12px;
                flex-wrap: wrap;
                margin: 16px 0;
            }
            button {
                border: 2px solid #2c241b;
                background: #f1c27b;
                color: #2c241b;
                padding: 10px 16px;
                font-size: 16px;
                cursor: pointer;
            }
            button:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }
            .status {
                font-size: 14px;
                margin-top: 8px;
            }
            audio {
                width: 100%;
                margin-top: 12px;
            }
            pre {
                background: #fdf2dc;
                border: 1px solid #e0cfae;
                padding: 12px;
                white-space: pre-wrap;
            }
        </style>
    </head>
    <body>
        <main>
            <h1>Gravar e enviar áudio</h1>
            <p>Grave um áudio rápido e envie para a triagem.</p>

            <div class="controls">
                <button id="start">Iniciar gravação</button>
                <button id="stop" disabled>Parar</button>
                <button id="send" disabled>Enviar para IA</button>
            </div>

            <div class="status" id="status">Pronto para gravar.</div>
            <audio id="player" controls></audio>

            <h2>Resposta</h2>
            <pre id="result">-</pre>
        </main>

        <script>
            const startBtn = document.getElementById('start');
            const stopBtn = document.getElementById('stop');
            const sendBtn = document.getElementById('send');
            const statusEl = document.getElementById('status');
            const player = document.getElementById('player');
            const result = document.getElementById('result');

            let mediaRecorder;
            let chunks = [];
            let audioBlob;

            function setStatus(text) {
                statusEl.textContent = text;
            }

            startBtn.addEventListener('click', async () => {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    chunks = [];
                    mediaRecorder = new MediaRecorder(stream);
                    mediaRecorder.ondataavailable = (event) => {
                        if (event.data.size > 0) {
                            chunks.push(event.data);
                        }
                    };
                    mediaRecorder.onstop = () => {
                        audioBlob = new Blob(chunks, { type: mediaRecorder.mimeType });
                        player.src = URL.createObjectURL(audioBlob);
                        sendBtn.disabled = false;
                        setStatus('Gravação pronta para envio.');
                    };
                    mediaRecorder.start();
                    startBtn.disabled = true;
                    stopBtn.disabled = false;
                    sendBtn.disabled = true;
                    setStatus('Gravando...');
                } catch (error) {
                    setStatus('Permissão de microfone negada ou indisponível.');
                }
            });

            stopBtn.addEventListener('click', () => {
                if (mediaRecorder && mediaRecorder.state === 'recording') {
                    mediaRecorder.stop();
                    mediaRecorder.stream.getTracks().forEach(track => track.stop());
                    startBtn.disabled = false;
                    stopBtn.disabled = true;
                }
            });

            sendBtn.addEventListener('click', async () => {
                if (!audioBlob) {
                    return;
                }
                setStatus('Enviando...');
                result.textContent = '-';
                const formData = new FormData();
                formData.append('audio', audioBlob, 'gravacao.webm');

                try {
                    const response = await fetch('/audio/triage', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                    });
                    const data = await response.json();
                    result.textContent = JSON.stringify(data, null, 2);
                    setStatus('Resposta recebida.');
                } catch (error) {
                    setStatus('Falha ao enviar o áudio.');
                }
            });
        </script>
    </body>
</html>
