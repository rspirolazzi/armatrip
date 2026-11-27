{{-- Versiones desplegadas (App\Support\BuildInfo). --}}
<footer class="main-footer app-version">
    <span>{{ \App\Support\BuildInfo::api() }}</span>
    <span class="app-version-separator" aria-hidden="true">·</span>
    <span>{{ \App\Support\BuildInfo::web() }}</span>
</footer>
