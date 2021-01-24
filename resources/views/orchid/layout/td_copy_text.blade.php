@if(null !== $value)
    <div class="row">
        <div class="col-md-6" style="white-space: initial;">
            {{ $value ?? '' }}
        </div>
        <div class="col-md-2 text-center">
            <button type="button" class="btn btn-light" onclick="copyToClipboard('{{ $value ?? '' }}')">Копировать</button>
        </div>
    </div>

    <script type="text/javascript">
        function copyToClipboard(str) {
            var el = document.createElement('textarea');
            el.value = str;
            el.setAttribute('readonly', '');
            el.style.position = 'absolute';
            el.style.left = '-9999px';
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            alert('Ссылка скопирована в буфер обмена');
        }
    </script>
@endif