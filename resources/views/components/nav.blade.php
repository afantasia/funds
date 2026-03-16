<div class="container-fluid">
    <a class="navbar-brand">주식망겜</a>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-outline-secondary btn-sm theme-toggle" id="themeToggle" title="테마 전환" aria-label="테마 전환">
            <i class="xi-moon theme-icon-dark" style="display:none;"></i>
            <i class="xi-sun theme-icon-light"></i>
        </button>
        <a class="btn btn-primary" href="@if(Auth::check()) logout @else login @endif"> @if(Auth::check()) 로그아웃 @else 로그인 @endif</a>
    </div>
</div>
<script>
(function() {
    function updateThemeIcon() {
        var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        document.querySelectorAll('.theme-icon-dark').forEach(function(el) { el.style.display = isDark ? '' : 'none'; });
        document.querySelectorAll('.theme-icon-light').forEach(function(el) { el.style.display = isDark ? 'none' : ''; });
    }
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('themeToggle');
        if (btn) btn.addEventListener('click', function() { toggleTheme(); updateThemeIcon(); });
        updateThemeIcon();
    });
})();
</script>
