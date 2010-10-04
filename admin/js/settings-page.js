$j = jQuery.noConflict();

$j(document).ready(function(){
	// When the user clicks on one of the share icons
	$j('#easy-reader-share-icons input').click(function(){
		id = $j(this).attr('id');
		if($j(this).attr('checked')){
			$j('label[for="'+id+'"]').css('opacity',1);
		}
		else{
			$j('label[for="'+id+'"]').css('opacity',0.1);
		}
	});
	
	$j('#easy-reader-share-icons input').hide();
	$j('#easy-reader-share-icons input').each(function(i, el){
		id = $j(el).attr('id');
		if($j(el).attr('checked')){
			$j('label[for="'+id+'"]').css('opacity',1);
		}
		else{
			$j('label[for="'+id+'"]').css('opacity',0.1);
		}
	});
});