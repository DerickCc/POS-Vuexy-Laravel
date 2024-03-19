@php
    $containerFooter =
        isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact'
            ? 'container-xxl'
            : 'container-fluid';
@endphp

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
    <div class="{{ $containerFooter }}">
        <div class="footer-container d-flex align-items-center py-2">
            <div>
                © 2024, Vitara Audio
            </div>
        </div>
    </div>
</footer>
<!--/ Footer-->
