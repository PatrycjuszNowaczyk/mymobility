$('document').ready(function() {
    $(document).on('click', '#skopiuj-kod span', function(e) {
		e.preventDefault();
		const kod = $(this).text();
		copyToClipboard(kod);
		alert(text_copy_code);
	});
    
	function copyToClipboard(text) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val(text).select();
		document.execCommand("copy");
		$temp.remove();
	}
});