<script type="text/javascript">
$(function(){
	$.getJSON('/test.php?debug=1', function(data){
		console.log(data);
		if (data == 'ERROR')
			alert('error');
	});
});
</script>