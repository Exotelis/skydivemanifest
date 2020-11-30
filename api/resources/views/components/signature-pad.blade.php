@php
    $uuid = Str::uuid();
@endphp

@once
@push('scripts')
    {{-- https://github.com/szimek/signature_pad --}}
    <script src="{{ url(Storage::url('js/signature_pad.min.js')) }}"
            integrity="sha384-9fkfvbMb67EHLKwRXU7TI5Fd9vkiBFHkH4qpzEnYvR+UWdCpbRcXNfKSAAPOaMJP"
            crossorigin="anonymous"></script>
@endpush

@push('scripts')
    <script>
        let container = document.getElementById('signature-pad-container_{{ $uuid }}');
        let pad = document.getElementById('signature-pad_{{ $uuid }}');
        let signaturePad = new SignaturePad(pad);

        // Pad config
        signaturePad.maxWidth = 1;
        signaturePad.minDistance = 1;
        signaturePad.minWidth = 1;
        signaturePad.throttle = 0;
        signaturePad.onEnd = function () {
            // Wait 50ms to be rendered completely
            setTimeout(function () {
                let dataUrl = cropCanvas(pad);
                let input = container.querySelector('input[name="signature"]')
                input.value = dataUrl;
            }, 50)
        }

        function resizeCanvas() {
            let ratio = Math.max(window.devicePixelRatio || 1, 1);
            pad.width = pad.offsetWidth * ratio;
            pad.height = pad.offsetHeight * ratio;
            pad.getContext("2d").scale(ratio, ratio)

            clearSignaturePad();
        }

        function clearSignaturePad() {
            let input = document.getElementById('signature_{{ $uuid }}');

            // Clear
            input.value = null;
            signaturePad.clear();
        }

        function cropCanvas (canvas) {
            // First duplicate the canvas to not alter the original
            let croppedCanvas = document.createElement('canvas');
            let croppedCtx    = croppedCanvas.getContext('2d');

            croppedCanvas.width  = canvas.width;
            croppedCanvas.height = canvas.height;
            croppedCtx.drawImage(canvas, 0, 0);

            // Next do the actual cropping
            let pix       = { x:[], y:[]};
            let imageData = croppedCtx.getImageData(0,0,croppedCanvas.width,croppedCanvas.height);

            for (let y = 0; y < croppedCanvas.height; y++) {
                for (let x = 0; x < croppedCanvas.width; x++) {
                    let index = (y * croppedCanvas.width + x) * 4;
                    if (imageData.data[index+3] > 0) {
                        pix.x.push(x);
                        pix.y.push(y);

                    }
                }
            }
            pix.x.sort(function(a,b){return a-b});
            pix.y.sort(function(a,b){return a-b});
            let n = pix.x.length-1;

            let cut = croppedCtx.getImageData(pix.x[0], pix.y[0], (pix.x[n] - pix.x[0]), (pix.y[n] - pix.y[0]));

            croppedCanvas.width = pix.x[n] - pix.x[0];
            croppedCanvas.height = pix.y[n] - pix.y[0];
            croppedCtx.putImageData(cut, 0, 0);

            return croppedCanvas.toDataURL();
        }

        window.onresize = resizeCanvas;
        resizeCanvas();
    </script>
@endpush

@push('styles')
    <style>
        .signature-pad-container {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            height: 300px;
            position: relative;
            width: 100%;
        }

        .signature-pad-container .signature-pad-body {
            border: 1px solid #f4f4f4;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            position: relative;
        }

        .signature-pad-container .signature-pad-body canvas {
            border: 1px solid #ced4da;
            border-radius: .25rem;
            height: 100%;
            left: 0;
            position: absolute;
            top: 0;
            width: 100%;
        }

        .signature-pad-container .signature-pad-body canvas.has-error {
            background-color: rgba(241, 70, 104, 0.1);
            background-image: none;
            border-color: #f14668;
            padding-right: calc(1.5em + .75rem);
        }

        .signature-pad-container .signature-pad-actions {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            margin-top: 8px;
        }
    </style>
@endpush
@endonce
<p>{{ old('signature') }}</p>
<div id="signature-pad-container_{{ $uuid }}" class="signature-pad-container">
    <div class="signature-pad-body">
        <canvas id="signature-pad_{{ $uuid }}" class="@error('signature') has-error @enderror">
            Your browser does not support the signature pad.
        </canvas>
    </div>
    <div class="signature-pad-actions">
        <div>
            <button type="button" class="secondary small" onclick="clearSignaturePad()">
                {{ __('messages.clear_signature_pad') }}
            </button>
        </div>
    </div>
    <input id="signature_{{ $uuid }}" name="signature" type="hidden">
</div>
