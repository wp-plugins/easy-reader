$j = jQuery.noConflict();

$j(document).ready(function(){
	// If this is an iframe, then close links should call closeEasyReader instead.
	if(window.location != window.parent.location){
		$j('.easy-reader-content a[target!="_blank"]').attr('target', '_parent')
		$j('.share-icons a').attr('target', '_parent')
		$j('a.close-link').click(function(){
			parent.closeEasyReader();
			return false;
		});
	}
	
	$j('#print-button').click(function(){
		$j(this).blur();
		$j('#easy-reader-content').print();
		return false;
	})
	
	if(document.location != parent.location){
		$j('#close-button').click(function(){
			$j(this).blur();
			parent.closeEasyReader();
		});
	}
})