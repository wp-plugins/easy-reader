$j = jQuery.noConflict();

var easyReaderBlockout;
var easyReaderBlock;

function easyReaderClick(link){
	// Sniff for IE6
	isIE6 = /MSIE 6/i.test(navigator.userAgent);
	if(isIE6){
		document.location = $j(link).attr('href');
	}
	
	link = $j(link);
	easyReaderLoad(link);
	
	return false;
}

function easyReaderLoad(link){
	$j(document.body).css('overflow', 'hidden');
	
	if(easyReaderBlock != undefined){
		// One already exists
		easyReaderBlockout.show();
		if(easyReaderBlock.attr('src') != link.attr('href')){
			easyReaderBlock.attr('src', link.attr('href'));
		}
		easyReaderBlock.slideDown('slow');
	}
	else{
		// Create the two elements
		easyReaderBlockout = $j("<div id='easy-reader-blockout'></div>").prependTo(document.body);
		easyReaderBlock = $j("<iframe id='easy-reader-block' name='easyreaderblock'></iframe>").prependTo(document.body);
		
		// Set them up
		easyReaderBlockout
			.click(closeEasyReader);
		
		easyReaderBlock.attr('src', $j(link).attr('href'));
		
		// Center the block
		easyReaderPosition();
		easyReaderBlock
			.hide()
			.slideDown('slow')
		;
	}
}

function easyReaderPosition(){
	if(easyReaderBlock != null){
		easyReaderBlock
			.css('width', 770)
			.css('height', $j(window).height()-1)
			.css('left', Math.round(($j(document.body).outerWidth() - easyReaderBlock.outerWidth())/2));
	}
}

function closeEasyReader(){
	$j('#easy-reader-blockout').fadeOut();
	$j('#easy-reader-block').slideUp();
	$j(document.body).css('overflow', 'visible');
	return false;
}

$j(document).ready(function(){
	$j(window).resize(easyReaderPosition);
	jQuery("<img>")
		.attr("src", EASY_READER_FOLDER+'/images/loader.gif')
		.appendTo('body')
		.hide();
})