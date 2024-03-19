<!-- BEGIN: Vendor JS-->

@vite(['resources/assets/vendor/libs/jquery/jquery.js', 'resources/assets/vendor/libs/popper/popper.js', 'resources/assets/vendor/js/bootstrap.js', 'resources/assets/vendor/libs/node-waves/node-waves.js', 'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js', 'resources/assets/vendor/libs/hammer/hammer.js', 'resources/assets/vendor/libs/typeahead-js/typeahead.js', 'resources/assets/vendor/js/menu.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
@vite(['resources/assets/js/main.js'])

<!-- END: Theme JS-->

<!-- BEGIN: Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->

<!-- custom, public -->
<script src="{{ asset('js/custom-sweetAlert2.js') }}"></script>

<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
