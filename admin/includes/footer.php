<script src="../assets/js/classie.js"></script>
		<script>
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				showLeftPush = document.getElementById( 'showLeftPush' ),
				body = document.body;
				
			showLeftPush.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeftPush' );
			};
			
			function disableOther( button ) {
				if( button !== 'showLeftPush' ) {
					classie.toggle( showLeftPush, 'disabled' );
				}
			}
		</script>



<style>
       /* Ayusin ang "Show entries" at "Search" */
.dataTables_wrapper .dataTables_length {
    float: left;
    margin-bottom: 20px;

}

.dataTables_wrapper .dataTables_filter {
    float: right;
    text-align: right;
}

.dataTables_wrapper .dataTables_filter label {
    display: flex;
    align-items: center;
}

.dataTables_wrapper .dataTables_filter input {
    margin-left: 8px; /* Maglagay ng space sa pagitan ng label at input */
}
.cbp-spmenu-vertical {
    background-color:#cf7dad;
}
.active {
    color: #8D7984 !important; 

}

li.active a i, .act a i {
    color: #8D7984 !important;
}

.logo {
    background: #cf7dad;
    text-align: center;
    float: left;
	width: 44.7%;
}

 </style>



<script>
$(document).ready(function () {
    $('#serviceTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "lengthMenu": [10, 25, 50, 100],
        "order": [],

        // Ayusin ang layout: Ilipat ang "Show entries" sa kaliwa, "Search" sa kanan
        "dom": '<"top d-flex justify-content-between"l f>rt<"bottom d-flex justify-content-between"i p><"clear">'
    });
});

</script>


	<!--scrolling js-->
	<script src="../assets/js/jquery.nicescroll.js"></script>
	<script src="../assets/js/scripts.js"></script>
	<!--//scrolling js-->
	<!-- Bootstrap Core JavaScript -->
   <script src="../assets/js/bootstrap.js"> </script>
       <!-- DataTables JS (kung wala pa sa project mo) -->
	   <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="../vendor/tinymce/tinymce/tinymce.min.js"></script> <!-- Siguraduhin na ito ay tama -->

</body>
</html>