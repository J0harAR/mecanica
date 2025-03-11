
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                let alert = document.getElementById('success-alert');
                if (alert) {
                    alert.classList.remove('show'); // Oculta con animación
                    setTimeout(() => alert.remove(), 500); // Remueve después de la animación
                }
            }, 3000); // Cambia el valor (en ms) si quieres más o menos duración
        });
    </script>
@endif


@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>¡Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Advertencia:</strong> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <strong>Información:</strong> {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


@if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endforeach
@endif
