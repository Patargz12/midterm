<script src="assets/js/bootstrap.min.js"></script>

<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-1.13.6/datatables.min.js"></script>

<script src="assets/js/main.js"></script>

<script>
  $(document).ready(function() {
    $("#checkAll").click(function() {
      if ($(this).is(":checked")) {
        $(".checkItem").prop("checked", true);
      } else {
        $(".checkItem").prop("checked", false);
      }
    });
  });
</script>

</body>

</html>