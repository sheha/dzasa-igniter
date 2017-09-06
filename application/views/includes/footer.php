<!--JS-->
<!-- CDN -->
<!--<script-->

<script>
        var baseUrl = '<?php echo base_url(); ?>';
        var siteUrl = '<?php echo site_url(); ?>'
                                                  </script>

<!--  jQuery is high dependency package, needs to be loaded first -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>
<!--  Bootstrap, compiled minimized -->
<script type="text/javascript"
        src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<!--  Datatables module, pre compiled on same packages as above -->
<script type="text/javascript"
        src="https://cdn.datatables.net/v/bs-3.3.7/jq-2.2.4/dt-1.10.15/datatables.min.js"></script>
<!--  And a custom bootstrap datetimepicker -->
<script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/eonasdan-bootstrap-datetimepicker@4.17.47/src/js/bootstrap-datetimepicker.min.js">
</script>
<!--  Last, local phonebook app JS module -->
<script type="text/javascript"
        src="<?php echo site_url( 'assets/local-assets/js/phonebook.module.js' ); ?>">
</script>


</body>
</html>
