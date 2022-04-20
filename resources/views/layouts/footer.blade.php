        <footer>
            <div class="footer clearfix mb-0 text-muted">
                <div class="float-start">
                    <p>{{ date('Y') }} &copy; Generator by
                        <a href="https://github.com/Zzzul" target="_blank">M. Zulfahmi</a>
                    </p>
                </div>
                <div class="float-end">
                    <p>Mazer Admin by
                        <a href="http://ahmadsaugi.com" target="_blank">A. Saugi</a>
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <script src="{{ asset('mazer') }}/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('mazer') }}/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('mazer') }}/js/mazer.min.js"></script>
    @stack('js')
</body>

</html>
